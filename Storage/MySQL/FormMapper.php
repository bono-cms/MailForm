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
        return self::getWithPrefix('bono_module_mailform_translations');
    }

    /**
     * Return shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::getFullColumnName('id'),
            self::getFullColumnName('lang_id', self::getTranslationTable()),
            self::getFullColumnName('web_page_id', self::getTranslationTable()),
            self::getFullColumnName('description', self::getTranslationTable()),
            self::getFullColumnName('template'),
            self::getFullColumnName('message'),
            self::getFullColumnName('seo'),
            self::getFullColumnName('title', self::getTranslationTable()),
            self::getFullColumnName('name', self::getTranslationTable()),
            self::getFullColumnName('meta_description', self::getTranslationTable()),
            self::getFullColumnName('keywords', self::getTranslationTable()),

            // Web page meta columns
            WebPageMapper::getFullColumnName('slug')
        );
    }

    /**
     * Fetches form name by its associated id
     * 
     * @param string $id Form id
     * @return string
     */
    public function fetchNameById($id)
    {
        return $this->findColumnByPk($id, 'name');
    }

    /**
     * Fetches message view by associated form id
     * 
     * @param string $id Form id
     * @return string
     */
    public function fetchMessageViewById($id)
    {
        return $this->findColumnByPk($id, 'message');
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
     * Adds new form
     * 
     * @param array $input
     * @return boolean
     */
    public function insert(array $input)
    {
        return $this->persist($this->getWithLang($input));
    }

    /**
     * Updates a form
     * 
     * @param array $input
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->persist($input);
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
        return $this->createWebPageSelect($this->getColumns())
                    // Optional attribute filters
                    ->whereEquals(self::getFullColumnName('lang_id', self::getTranslationTable()), $this->getLangId())
                    ->orderBy(self::getFullColumnName('id'))
                    ->desc()
                    ->queryAll();
    }

    /**
     * Deletes a form by its associated id
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->deleteByPk($id);
    }
}
