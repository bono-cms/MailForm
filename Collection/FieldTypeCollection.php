<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Collection;

use Krystal\Stdlib\ArrayGroupCollection;

final class FieldTypeCollection extends ArrayGroupCollection
{
    /* Common field types used in mail forms */
    const TYPE_TEXT = 1;
    const TYPE_NUMBER = 2;
    const TYPE_EMAIL = 3;
    const TYPE_DATE = 4;
    const TYPE_DATETIME = 5;
    const TYPE_TEXTAREA = 6;
    const TYPE_SELECT = 7;
    const TYPE_CHECKBOX_LIST = 8;
    const TYPE_RADIO_LIST = 9;
    const TYPE_BOOLEAN = 10;
    const TYPE_SUBMIT = 11;
    const TYPE_RESET = 12;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        'Select' => array(
            self::TYPE_SELECT => 'Dropdown',
            self::TYPE_CHECKBOX_LIST => 'Checkbox list',
            self::TYPE_RADIO_LIST => 'Radio list',
            self::TYPE_BOOLEAN => 'Boolean',
        ),

        'Date' => array(
            self::TYPE_DATE => 'Date',
            self::TYPE_DATETIME => 'Date and time',
        ),

        'Text' => array(
            self::TYPE_TEXT => 'Text',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_EMAIL => 'Email',
            self::TYPE_TEXTAREA => 'Text Area',
        ),

        'Buttons' => array(
            self::TYPE_SUBMIT => 'Submit',
            self::TYPE_RESET => 'Reset'
        )
    );

    /**
     * Returns simple types
     * 
     * @return array
     */
    public static function getSimpleTypes()
    {
        return array(
            self::TYPE_TEXT,
            self::TYPE_NUMBER,
            self::TYPE_EMAIL,
            self::TYPE_DATE,
            self::TYPE_DATETIME
        );
    }
}
