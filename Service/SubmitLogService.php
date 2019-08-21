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
               ->setMessage($row['message'], VirtualEntity::FILTER_TAGS)
               ->setSubject($row['subject'], VirtualEntity::FILTER_TAGS)
               ->setAttachments($row['attachments'], VirtualEntity::FILTER_INT);

        return $entity;
    }

    /**
     * Remove all logs
     * 
     * @return boolean
     */
    public function clearAll()
    {
        return $this->submitLogMapper->clearAll();
    }

    /**
     * Saves new log
     * 
     * @param string $subject Message subject
     * @param string $message Message body
     * @param array $files Request files
     * @return boolean
     */
    public function log($subject, $message, array $files = array())
    {
        $data = array(
            'datetime' => TimeHelper::getNow(),
            'message' => $message,
            'subject' => $subject,
            'attachments' => count($files)
        );

        return $this->submitLogMapper->persist($data);
    }

    /**
     * Deletes a log by its ID
     * 
     * @param int $id Submit log ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->submitLogMapper->deleteByPk($id);
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
