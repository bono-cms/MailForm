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

use Krystal\Db\Sql\RawSqlFragment;
use Cms\Storage\MySQL\AbstractMapper;
use Cms\Storage\MySQL\WebPageMapper;
use MailForm\Storage\FormMapperInterface;

final class FormMapper extends AbstractMapper implements FormMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_mailform');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return FormTranslationMapper::getTableName();
    }

    /**
     * Return shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('template'),
            self::column('message'),
            self::column('seo'),
            self::column('captcha'),
            self::column('subject'),
            self::column('type'),
            self::column('autocomplete'),
            self::column('flash_position'),
            FormTranslationMapper::column('lang_id'),
            FormTranslationMapper::column('web_page_id'),
            FormTranslationMapper::column('description'),
            FormTranslationMapper::column('title'),
            FormTranslationMapper::column('name'),
            FormTranslationMapper::column('meta_description'),
            FormTranslationMapper::column('keywords'),
            FormTranslationMapper::column('flash'),
            FormTranslationMapper::column('terms'),

            // Web page meta columns
            WebPageMapper::column('slug'),
            WebPageMapper::column('changefreq'),
            WebPageMapper::column('priority')
        );
    }

    /**
     * Updates SEO state by form's associated id
     * 
     * @param string $id
     * @param string $seo
     * @return boolean
     */
    public function updateSeoById($id, $seo)
    {
        return $this->updateColumnByPk($id, 'seo', $seo);
    }

    /**
     * Fetches form data by its associated id
     * 
     * @param string $id Form's id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findWebPage($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetches all forms
     * 
     * @return array
     */
    public function fetchAll()
    {
        $columns = $this->getColumns();
        $columns[] = new RawSqlFragment(sprintf('COUNT(%s) AS field_count', FieldMapper::column('id')));

        $db = $this->createWebPageSelect($columns)
                    // Field relation
                    ->leftJoin(FieldMapper::getTableName(), array(
                        FieldMapper::column('form_id') => self::getRawColumn('id')
                    ))
                    // Constraints
                    ->whereEquals(FormTranslationMapper::column('lang_id'), $this->getLangId())
                    ->groupBy($this->getColumns())
                    ->orderBy(self::column('id'))
                    ->desc();

        return $db->queryAll();
    }
}
