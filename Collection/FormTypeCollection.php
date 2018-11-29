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

final class FormTypeCollection extends ArrayCollection
{
    /* Supported form types */
    const TYPE_REGULAR = 1;
    const TYPE_AJAX = 2;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::TYPE_REGULAR => 'Regular form',
        self::TYPE_AJAX => 'Ajax form'
    );
}
