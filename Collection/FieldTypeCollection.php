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

use Krystal\Stdlib\ArrayCollection;

final class FieldTypeCollection extends ArrayCollection
{
    /* Common field types used in mail forms */
    const TYPE_TEXT = 1;
    const TYPE_NUMBER = 2;
    const TYPE_EMAIL = 3;
    const TYPE_DATE = 4;
    const TYPE_DATETIME = 5;
    const TYPE_TEXTAREA = 6;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::TYPE_TEXT => 'Text',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_EMAIL => 'Email',
        self::TYPE_DATE => 'Date',
        self::TYPE_DATETIME => 'Date and time',
        self::TYPE_TEXTAREA => 'Text Area',
    );
}
