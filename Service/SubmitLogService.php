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

use MailForm\Storage\SubmitLogMapperInterface;

final class SubmitLogService
{
    /**
     * Any compliant submit log mapper
     * 
     * @var \MailForm\Storage\SubmitLogMapperInterface
     */
    private $submitLogMapper;

    /**
     * State initialization
     * 
     * @param \MailForm\Storage\SubmitLogMapperInterface $submitLogMapper
     * @return void
     */
    public function __construct(SubmitLogMapperInterface $submitLogMapper)
    {
        $this->submitLogMapper = $submitLogMapper;
    }
}
