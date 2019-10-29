<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Storage\MySQL;

use Block\Storage\MySQL\AbstractFieldMapper;

final class MfExtraFieldMapper extends AbstractFieldMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_mailform_extra_fields');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return MfExtraFieldTranslationMapper::getTableName();
    }

    /**
     * {@inheritDoc}
     */
    public static function getRelationTable()
    {
        return MfExtraFieldRelation::getTableName();
    }
}
