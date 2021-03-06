<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use MailForm\Storage\FieldMapperInterface;
use Krystal\Db\Sql\RawSqlFragment;

final class FieldMapper extends AbstractMapper implements FieldMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_mailform_fields');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return FieldTranslationMapper::getTableName();
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('form_id'),
            self::column('type'),
            self::column('order'),
            self::column('required'),
            self::column('column'),
            FieldTranslationMapper::column('lang_id'),
            FieldTranslationMapper::column('name'),
            FieldTranslationMapper::column('hint'),
            FieldTranslationMapper::column('default'),
            FieldTranslationMapper::column('error')
        );
    }

    /**
     * Fetch basic fields data by attached form Id
     * 
     * @param int $formid Form Id
     * @return array
     */
    public function fetchByFormId($formId)
    {
        // To be selected
        $columns = array(
            'id', // Field ID
            'type' // Field type constant
        );

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       ->whereEquals('form_id', $formId);

        return $db->queryAll();
    }

    /**
     * Fetch fields by their IDs
     * 
     * @param array $ids Field IDs
     * @return array
     */
    public function fetchByIds(array $ids)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   // Constraints
                   ->whereIn(self::column(self::PARAM_COLUMN_ID), $ids)
                   ->andWhereEquals(FieldTranslationMapper::column('lang_id'), $this->getLangId());

        return $db->queryAll();
    }

    /**
     * Fetch field by its ID
     * 
     * @param int $id Field ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetch all fields by form ID
     * 
     * @param int $formId
     * @param boolean $sort Whether to sort fields
     * @param array $ignoreTypes Optional array of ignored type constants
     * @return array
     */
    public function fetchAll($formId, $sort, $ignoreTypes = array())
    {
        $db = $this->createEntitySelect($this->getColumns())
                   ->whereEquals(self::column('form_id'), $formId)
                   ->andWhereEquals(FieldTranslationMapper::column('lang_id'), $this->getLangId());

        // Filter type constraints if required
        if ($ignoreTypes) {
            $db->andWhereNotIn(self::column('type'), $ignoreTypes);
        }

        if ($sort === false) {
            $db->orderBy(self::column('id'))
               ->desc();
        } else {
            $db->orderBy(array(
                self::column('order'),
                new RawSqlFragment(sprintf('CASE WHEN %s = 0 THEN %s END DESC', self::column('order'), self::column('id')))
            ));
        }

        return $db->queryAll();
    }
}
