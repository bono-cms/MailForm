<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage;

interface FormMapperInterface
{
    /**
     * Fetches form name by its associated id
     * 
     * @param string $id Form id
     * @return string
     */
    public function fetchNameById($id);

    /**
     * Fetches message view by associated form id
     * 
     * @param string $id Form id
     * @return string
     */
    public function fetchMessageViewById($id);

    /**
     * Updates SEO state by form's associated id
     * 
     * @param string $id
     * @param string $seo
     * @return boolean
     */
    public function updateSeoById($id, $seo);

    /** Adds new form
     * 
     * @param array $data
     * @return boolean
     */
    public function insert(array $data);

    /**
     * Updates a form
     * 
     * @param array $data
     * @return boolean
     */
    public function update(array $data);

    /**
     * Fetches form data by its associated id
     * 
     * @param string $id Form's id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetches all forms
     * 
     * @return array
     */
    public function fetchAll();

    /**
     * Deletes a form by its associated id
     * 
     * @param string $id
     * @return boolean
     */
    public function deleteById($id);
}
