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

interface FieldMapperInterface
{
    /**
     * Fetch field Ids by attached form Id
     * 
     * @param int $formid Form Id
     * @return array
     */
    public function fetchIdsByFormId($formId);

    /**
     * Fetch fields by their IDs
     * 
     * @param array $ids Field IDs
     * @return array
     */
    public function fetchByIds(array $ids);

    /**
     * Fetch field by its ID
     * 
     * @param int $id Field ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);

    /**
     * Fetch all fields by form ID
     * 
     * @param int $formId
     * @param boolean $sort Whether to sort fields
     * @return array
     */
    public function fetchAll($formId, $sort);
}
