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
    const TYPE_FILE = 11;
    const TYPE_PASSWORD = 12;

    /* File extras */
    const TYPE_FILE_WORD = 13;
    const TYPE_FILE_EXCEL = 14;
    const TYPE_FILE_POWER_POINT = 15;
    const TYPE_FILE_TEXT = 16;
    const TYPE_FILE_PDF = 17;
    const TYPE_FILE_IMAGE = 18;
    const TYPE_FILE_ARCHIVE = 19;

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
            self::TYPE_TEXTAREA => 'Textarea',
            self::TYPE_PASSWORD => 'Password'
        ),

        'Files' => array(
            self::TYPE_FILE => 'File selection',
            self::TYPE_FILE_WORD => 'Word file selection',
            self::TYPE_FILE_EXCEL => 'Excel file selection',
            self::TYPE_FILE_POWER_POINT => 'PowerPoint file selection',
            self::TYPE_FILE_TEXT => 'Text file selection',
            self::TYPE_FILE_PDF => 'PDF file selection',
            self::TYPE_FILE_IMAGE => 'Image file selection'
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
            self::TYPE_DATETIME,
            self::TYPE_PASSWORD
        );
    }
}
