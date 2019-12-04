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

use Krystal\Stdlib\VirtualEntity;
use MailForm\Collection\FormTypeCollection;

final class FormEntity extends VirtualEntity
{
    /**
     * Returns a single value of a field by its id
     * 
     * @param int $id Field id
     * @return string
     */
    public function getField($id)
    {
        $fields = $this->getFields();

        return isset($fields[$id]) ? $fields[$id] : null;
    }

    /**
     * Checks whether current form has terms filled in
     * 
     * @return boolean
     */
    public function hasTerms()
    {
        $terms = $this->getTerms();
        $terms = trim($terms);

        return !empty($terms);
    }

    /**
     * Returns a single value of a field by its id
     * 
     * @param int $id Field id
     * @return string
     */
    public function getDynamicField($id)
    {
        $fields = $this->getDynamicFields();

        return isset($fields[$id]) ? $fields[$id] : null;
    }

    /**
     * Checks whether current form is regular
     * 
     * @return boolean
     */
    public function isRegularForm()
    {
        return $this->getType() == FormTypeCollection::TYPE_REGULAR;
    }

    /**
     * Checks whether current form is AJAX one
     * 
     * @return boolean
     */
    public function isAjaxForm()
    {
        return $this->getType() == FormTypeCollection::TYPE_AJAX;
    }
}
