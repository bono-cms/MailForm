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

use Cms\Service\AbstractManager;
use MailForm\Storage\FieldValueMapperInterface;

final class FieldValueService extends AbstractManager
{
    /**
     * Any compliant field value mapper
     * 
     * @var \MailForm\Storage\FieldValueMapperInterface
     */
    private $fieldValueMapper;

    /**
     * State initialization
     * 
     * @param \MailForm\Storage\FieldValueMapperInterface $fieldValueMapper
     * @return void
     */
    public function __construct(FieldValueMapperInterface $fieldValueMapper)
    {
        $this->fieldValueMapper = $fieldValueMapper;
    }
}
