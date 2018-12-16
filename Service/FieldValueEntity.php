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

final class FieldValueEntity extends AbstractEntity
{
    /**
     * Checks whether current value must be checked by default
     * 
     * @return mixed
     */
    public function isChecked()
    {
        if ($this->isCheckboxList()) {
            return (bool) $this->getDefault();
        }

        return null;
    }
}
