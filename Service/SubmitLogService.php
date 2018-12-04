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
use Krystal\Date\TimeHelper;

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
     * Saves new log
     * 
     * @param string $subject Message subject
     * @param string $message Message body
     * @return boolean
     */
    public function log($subject, $message)
    {
        $data = array(
            'datetime' => TimeHelper::getNow(),
            'message' => $message,
            'subject' => $subject
        );

        return $this->submitLogMapper->persist($data);
    }

    /**
     * Fetch log by its ID
     * 
     * @param int $id Log ID
     * @return \Krystal\Stdlib\VirtualEntity|boolean
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->submitLogMapper->findByPk($id));
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
