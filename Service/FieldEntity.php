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
     * Checks whether field type is text
     * 
     * @return boolean
     */
    public function isText()
    {
        return $this->is(FieldTypeCollection::TYPE_TEXT);
    }

    /**
     * Checks whether field type is number
     * 
     * @return boolean
     */
    public function isNumber()
    {
        return $this->is(FieldTypeCollection::TYPE_NUMBER);
    }

    /**
     * Checks whether field type is email
     * 
     * @return boolean
     */
    public function isEmail()
    {
        return $this->is(FieldTypeCollection::TYPE_EMAIL);
    }

    /**
     * Checks whether field type is date
     * 
     * @return boolean
     */
    public function isDate()
    {
        return $this->is(FieldTypeCollection::TYPE_DATE);
    }

    /**
     * Checks whether field type is date & time
     * 
     * @return boolean
     */
    public function isDatetime()
    {
        return $this->is(FieldTypeCollection::TYPE_DATETIME);
    }

    /**
     * Checks whether field type is textarea
     * 
     * @return boolean
     */
    public function isTextarea()
    {
        return $this->is(FieldTypeCollection::TYPE_TEXTAREA);
    }

    /**
     * Checks whether field type is select
     * 
     * @return boolean
     */
    public function isSelect()
    {
        return $this->is(FieldTypeCollection::TYPE_SELECT);
    }

    /**
     * Checks whether field type is boolean
     * 
     * @return boolean
     */
    public function isBoolean()
    {
        return $this->is(FieldTypeCollection::TYPE_BOOLEAN);
    }

    /**
     * Checks whether field type is checkbox list
     * 
     * @return boolean
     */
    public function isCheckboxList()
    {
        return $this->is(FieldTypeCollection::TYPE_CHECKBOX_LIST);
    }

    /**
     * Checks whether field type is radio list
     * 
     * @return boolean
     */
    public function isRadioList()
    {
        return $this->is(FieldTypeCollection::TYPE_RADIO_LIST);
    }

    /**
     * Checks whether field type is submit button
     * 
     * @return boolean
     */
    public function isSubmit()
    {
        return $this->is(FieldTypeCollection::TYPE_SUBMIT);
    }

    /**
     * Checks whether field type is reset button
     * 
     * @return boolean
     */
    public function isReset()
    {
        return $this->is(FieldTypeCollection::TYPE_RESET);
    }

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
     * Checks whether current field is simple (i.e input text like)
     * 
     * @return boolean
     */
    public function isSimple()
    {
        return $this->inType(FieldTypeCollection::getSimpleTypes());
    }

    /**
     * Checks whether current field represents multi value
     * 
     * @return boolean
     */
    public function isMultiValue()
    {
        return $this->inType(array(
            FieldTypeCollection::TYPE_CHECKBOX_LIST
        ));
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
    public function isInputType()
    {
        return !$this->isButtonType();
    }

    /**
     * Checks whether current type is button
     * 
     * @return boolean
     */
    public function isButtonType()
    {
        return $this->inType(array(
            FieldTypeCollection::TYPE_SUBMIT,
            FieldTypeCollection::TYPE_RESET
        ));
    }
}
