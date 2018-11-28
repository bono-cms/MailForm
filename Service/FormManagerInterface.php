<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

interface FormManagerInterface
{
    /**
     * Returns a collection of switching URLs
     * 
     * @param string $id Form ID
     * @return array
     */
    public function getSwitchUrls($id);

    /**
     * Updates SEO states by associated form ids
     * 
     * @param array $pair
     * @return boolean
     */
    public function updateSeo(array $pair);

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId();

    /**
     * Delete by collection of ids
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids);

    /**
     * Fetches all form entities
     * 
     * @return array
     */
    public function fetchAll();

    /**
     * Fetches form entity by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations);    
}
