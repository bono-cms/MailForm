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

final class FieldStateCollection extends ArrayCollection
{
    const STATE_UNCHECKED = 0;
    const STATE_CHECKED = 1;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::STATE_UNCHECKED => 'Unchecked',
        self::STATE_CHECKED => 'Checked'
    );
}
