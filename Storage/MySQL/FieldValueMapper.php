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
use MailForm\Storage\FieldValueMapperInterface;

final class FieldValueMapper extends AbstractMapper implements FieldValueMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_mailform_fields_values');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return FieldValueTranslationMapper::getTableName();
    }

    /**
     * Creates shared select object
     * 
     * @return \Krystal\Db\Sql\Db
     */
    private function createSelect()
    {
        // To be selected
        $columns = array(
            self::column('id'),
            self::column('field_id'),
            self::column('order'),
            FieldMapper::column('form_id'),
            FieldValueTranslationMapper::column('lang_id'),
            FieldValueTranslationMapper::column('value')
        );

        $db = $this->createEntitySelect($columns)
                   // Field relation
                   ->leftJoin(FieldMapper::getTableName(), array(
                        FieldMapper::column('id') => self::getRawColumn('field_id')
                   ));

        return $db;
    }

    /**
     * Fetch value by its ID
     * 
     * @param int $id Value ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        $db = $this->createSelect()
                   ->whereEquals(self::column('id'), $id);

        if ($withTranslations === true) {
            return $db->queryAll();
        } else {
            return $db->andWhereEquals(FieldValueTranslationMapper::column('lang_id'), $this->getLangId())
                      ->query();
        }
    }

    /**
     * Fetch all values by field ID
     * 
     * @param int $fieldId
     * @return array
     */
    public function fetchAll($fieldId)
    {
        $db = $this->createSelect()
                   ->whereEquals(self::column('field_id'), $fieldId)
                   ->andWhereEquals(FieldValueTranslationMapper::column('lang_id'), $this->getLangId())
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }
}
