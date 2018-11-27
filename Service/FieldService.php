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
     * Create message parameters from input fields
     * Later on, these ones expected to be rendered in email message template
     * 
     * @param array $fields Raw input data
     * @return array
     */
    public function createParams(array $fields)
    {
        $ids = array_keys($fields);
        $entities = $this->fetchByIds($ids);

        // To be returned
        $output = array();

        foreach ($entities as $entity) {
            $output[] = array(
                'name' => $entity->getName(),
                'value' => $fields[$entity->getId()]
            );
        }

        return $output;
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
               ->setOrder($row['order'])
               ->setRequired($row['required'], FieldEntity::FILTER_BOOL)
               ->setColumn($row['column'])
               ->setName($row['name'])
               ->setHint($row['hint'])
               ->setDefault($row['default'])
               ->setError($row['error']);

        return $entity;
    }

    /**
     * Fetches form entities by their associated IDs
     * 
     * @param string $ids
     * @return array
     */
    public function fetchByIds(array $ids)
    {
        return $this->prepareResults($this->fieldMapper->fetchByIds($ids));
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
     * @param boolean $sort Whether to sort fields
     * @return array
     */
    public function fetchAll($formId, $sort)
    {
        return $this->prepareResults($this->fieldMapper->fetchAll($formId, $sort));
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
