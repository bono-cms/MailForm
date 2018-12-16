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

use Krystal\Stdlib\ArrayUtils;
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

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new FieldValueEntity();
        $entity->setId($row['id'], FieldValueEntity::FILTER_INT)
               ->setLangId($row['lang_id'], FieldValueEntity::FILTER_INT)
               ->setFieldId($row['field_id'], FieldValueEntity::FILTER_INT)
               ->setType($row['type'], FieldValueEntity::FILTER_INT)
               ->setFormId($row['form_id'], FieldValueEntity::FILTER_INT)
               ->setOrder($row['order'], FieldValueEntity::FILTER_INT)
               ->setValue($row['value'], FieldValueEntity::FILTER_TAGS)
               ->setDefault($row['default'], FieldValueEntity::FILTER_TAGS);

        return $entity;
    }

    /**
     * Fetch value by its ID
     * 
     * @param int $id Value ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->fieldValueMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->fieldValueMapper->fetchById($id, false));
        }
    }

    /**
     * Fetch values as a list
     * 
     * @param int $fieldId
     * @return array
     */
    public function fetchList($fieldId)
    {
        return ArrayUtils::arrayList($this->fieldValueMapper->fetchAll($fieldId), 'id', 'value');
    }

    /**
     * Fetch all values by associated field ID
     * 
     * @param int $fieldId
     * @return array
     */
    public function fetchGrouped($fieldId)
    {
        $rows = $this->fieldValueMapper->fetchAll($fieldId, true);
        return ArrayUtils::arrayDropdown($rows, 'lang_id', 'value', 'value');
    }

    /**
     * Fetch all values by associated field ID
     * 
     * @param int $fieldId
     * @return array
     */
    public function fetchAll($fieldId)
    {
        return $this->prepareResults($this->fieldValueMapper->fetchAll($fieldId));
    }

    /**
     * Returns last ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->fieldValueMapper->getMaxId();
    }

    /**
     * Saves a value
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->fieldValueMapper->saveEntity($input['value'], $input['translation']);
    }

    /**
     * Deletes a value by its ID
     * 
     * @param int $id Value ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->fieldValueMapper->deleteEntity($id);
    }    
}
