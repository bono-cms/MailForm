<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Collection;

use Krystal\Stdlib\ArrayGroupCollection;

final class FieldTypeCollection extends ArrayGroupCollection
{
    /* Common field types used in mail forms */
    const TYPE_HIDDEN = 0;
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
    protected $collection = [
        'Select' => [
            self::TYPE_SELECT => 'Dropdown',
            self::TYPE_CHECKBOX_LIST => 'Checkbox list',
            self::TYPE_RADIO_LIST => 'Radio list',
            self::TYPE_BOOLEAN => 'Boolean',
        ],

        'Date' => [
            self::TYPE_DATE => 'Date',
            self::TYPE_DATETIME => 'Date and time',
        ],

        'Text' => [
            self::TYPE_HIDDEN => 'Hidden field',
            self::TYPE_TEXT => 'Text',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_EMAIL => 'Email',
            self::TYPE_TEXTAREA => 'Textarea',
            self::TYPE_PASSWORD => 'Password'
        ],

        'Files' => [
            self::TYPE_FILE => 'File selection',
            self::TYPE_FILE_WORD => 'Word file selection',
            self::TYPE_FILE_EXCEL => 'Excel file selection',
            self::TYPE_FILE_POWER_POINT => 'PowerPoint file selection',
            self::TYPE_FILE_TEXT => 'Text file selection',
            self::TYPE_FILE_PDF => 'PDF file selection',
            self::TYPE_FILE_IMAGE => 'Image file selection'
        ]
    ];

    /**
     * Constant type map with its extensions
     * 
     * @var array
     */
    private static $extensions = [
        self::TYPE_FILE_WORD => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword'
        ],

        self::TYPE_FILE_EXCEL => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'application/excel'
        ],

        self::TYPE_FILE_POWER_POINT => [
            'application/vnd.ms-powerpoint', 
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow', 
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ],

        self::TYPE_FILE_TEXT => [
            'text/plain'
        ],

        self::TYPE_FILE_PDF => [
            'application/pdf'
        ],

        self::TYPE_FILE_IMAGE => [
            'image/*'
        ]
    ];

    /**
     * Guess MIME-type by constant
     * 
     * @param int $const
     * @return string
     */
    public static function guessMimeByConstant($const)
    {
        if (isset(self::$extensions[$const])) {
            return implode(', ', self::$extensions[$const]);
        } else {
            return null;
        }
    }

    /**
     * Whether constant type belongs to one of file types
     * 
     * @param int $const
     * @return boolean
     */
    public static function isFileType($const)
    {
        return in_array($const, self::getFileTypes());
    }

    /**
     * Returns file type constants
     * 
     * @return array
     */
    public static function getFileTypes()
    {
        return [
            self::TYPE_FILE,
            self::TYPE_FILE_WORD,
            self::TYPE_FILE_EXCEL,
            self::TYPE_FILE_POWER_POINT,
            self::TYPE_FILE_TEXT,
            self::TYPE_FILE_PDF,
            self::TYPE_FILE_IMAGE
        ];
    }

    /**
     * Returns simple types
     * 
     * @return array
     */
    public static function getSimpleTypes()
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_NUMBER,
            self::TYPE_EMAIL,
            self::TYPE_DATE,
            self::TYPE_DATETIME,
            self::TYPE_PASSWORD
        ];
    }
}