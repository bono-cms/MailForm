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

use MailForm\Collection\FieldTypeCollection;

final class FieldEntity extends AbstractEntity
{
    /**
     * Checks whether current field can have a value (or many values)
     * 
     * @return boolean
     */
    public function canHaveValue()
    {
        return $this->inType(array(
            FieldTypeCollection::TYPE_SELECT,
            FieldTypeCollection::TYPE_CHECKBOX_LIST,
            FieldTypeCollection::TYPE_RADIO_LIST
        ));
    }

    /**
     * Checks whether current entity has values
     * 
     * @return boolean
     */
    public function hasValues()
    {
        $values = $this->getValues();
        return !empty($values);
    }
}
