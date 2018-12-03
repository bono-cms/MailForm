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
use Cms\Service\AbstractManager;
use Krystal\Stdlib\VirtualEntity;

final class SubmitLogService extends AbstractManager
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

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'], VirtualEntity::FILTER_INT)
               ->setDatetime($row['datetime'])
               ->setMessage($row['message'], VirtualEntity::FILTER_TAGS);

        return $entity;
    }

    /**
     * Fetch all submission logs
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->submitLogMapper->fetchAll());
    }
}
