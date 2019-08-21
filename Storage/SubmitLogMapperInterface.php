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

interface SubmitLogMapperInterface
{
    /**
     * Remove all logs
     * 
     * @return boolean
     */
    public function clearAll();

    /**
     * Fetch all submission logs
     * 
     * @return array
     */
    public function fetchAll();
}
