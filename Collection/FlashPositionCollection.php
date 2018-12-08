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

final class FlashPositionCollection extends ArrayCollection
{
    const POS_UP = 1;
    const POS_DOWN = 2;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::POS_UP => 'Up',
        self::POS_DOWN => 'Down'
    );
}
