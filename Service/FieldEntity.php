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
use MailForm\Collection\FieldTypeCollection;

final class FieldEntity extends VirtualEntity
{
    /**
     * Checks whether current type belongs to at least one constant in collection
     * 
     * @param array $consts
     * @return boolean
     */
    private function inType(array $consts)
    {
        return in_array($this->getType(), $consts);
    }

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

    /**
     * Determines field type by its constant
     * 
     * @param int $const
     * @return boolean
     */
    public function is($const)
    {
        return $this->getType() == $const;
    }

    /**
     * Checks whether current type is input
     * 
     * @return boolean
     */
    public function isInput()
    {
        return !$this->isButton();
    }

    /**
     * Checks whether current type is button
     * 
     * @return boolean
     */
    public function isButton()
    {
        return $this->inType(array(
            FieldTypeCollection::TYPE_SUBMIT,
            FieldTypeCollection::TYPE_RESET
        ));
    }
}
