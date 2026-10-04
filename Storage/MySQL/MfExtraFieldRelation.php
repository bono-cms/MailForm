<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;

final class MfExtraFieldRelation extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_mailform_extra_fields_cat_rel');
    }
}
