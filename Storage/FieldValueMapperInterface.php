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

interface FieldValueMapperInterface
{
    /**
     * Fetch value by its ID
     * 
     * @param int $id Value ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetch all values by field ID
     * 
     * @param int $fieldId
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchAll($fieldId, $withTranslations = false);
}
