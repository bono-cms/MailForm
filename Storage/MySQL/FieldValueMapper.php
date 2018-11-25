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
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('field_id'),
            self::column('order'),
            FieldValueTranslationMapper::column('lang_id'),
            FieldValueTranslationMapper::column('value')
        );
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
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetch all values by field ID
     * 
     * @param int $fieldId
     * @return array
     */
    public function fetchAll($fieldId)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   ->whereEquals(self::column('field_id'), $fieldId)
                   ->andWhereEquals(FieldValueTranslationMapper::column('lang_id'), $this->getLangId())
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }
}
