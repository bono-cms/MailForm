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
use MailForm\Storage\FieldMapperInterface;

final class FieldService extends AbstractManager
{
    /**
     * Any compliant field mapper
     * 
     * @var \MailForm\Storage\FieldMapperInterface
     */
    private $fieldMapper;

    /**
     * State initialization
     * 
     * @param \MailForm\Storage\FieldMapperInterface $fieldMapper
     * @return void
     */
    public function __construct(FieldMapperInterface $fieldMapper)
    {
        $this->fieldMapper = $fieldMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new FieldEntity();
        $entity->setId($row['id'])
               ->setLangId($row['lang_id'])
               ->setFormId($row['form_id'])
               ->setType($row['type'])
               ->setName($row['name']);

        return $entity;
    }

    /**
     * Fetch field by ID
     * 
     * @param int $id Field ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->fieldMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->fieldMapper->fetchById($id, false));
        }
    }

    /**
     * Fetch all fields by associated form ID
     * 
     * @param int $formId
     * @return array
     */
    public function fetchAll($formId)
    {
        return $this->prepareResults($this->fieldMapper->fetchAll($formId));
    }

    /**
     * Returns last ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->fieldMapper->getMaxId();
    }

    /**
     * Saves a field
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->fieldMapper->saveEntity($input['field'], $input['translation']);
    }

    /**
     * Deletes a field by its ID
     * 
     * @param int $id Field ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->fieldMapper->deleteEntity($id);
    }
}
