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
            FieldTranslationMapper::column('lang_id'),
            FieldTranslationMapper::column('name')
        );
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
     * @return array
     */
    public function fetchAll($formId)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   ->whereEquals(self::column('form_id'), $formId)
                   ->andWhereEquals(FieldTranslationMapper::column('lang_id'), $this->getLangId())
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }
}
