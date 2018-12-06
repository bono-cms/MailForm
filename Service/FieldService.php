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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Templating\StringTemplate;
use Krystal\Stdlib\ArrayUtils;
use Cms\Service\AbstractManager;
use MailForm\Storage\FieldMapperInterface;
use MailForm\Collection\FieldTypeCollection;

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
     * Normalizes raw input data
     * 
     * @param int $formId Form ID being processed
     * @param array $fields Field Ids with their values
     * @pram array $files An array of files if present
     * @return array
     */
    public function normalizeInput($formId, array $fields, array $files)
    {
        // To be returned
        $output = array(
            'data' => array(),
            'files' => array()
        );

        // Field IDs that belong to that form
        $rows = $this->fieldMapper->fetchByFormId($formId);

        foreach ($rows as $row) {
            // Current values
            $id =& $row['id'];
            $type =& $row['type'];

            // Recovery missing ID with empty value
            if (!isset($fields[$id])) {
                $fields[$id] = null;
            }

            // Non-file
            if ($type != FieldTypeCollection::TYPE_FILE) {
                // Append text
                $output['data'][$id] = $fields[$id]; // Value
            }

            // File
            if ($type == FieldTypeCollection::TYPE_FILE && isset($files[$id])) {
                // Append file
                $output['files'][$id] = $files[$id];
            }
        }

        return $output;
    }

    /**
     * Creates message from parameters
     * 
     * @param string $rawMessage Raw form message
     * @param array $params
     * @return string
     */
    public function createMessage($rawMessage, array $params)
    {
        $vars = self::extractValues($params);
        return StringTemplate::template($rawMessage, $vars);
    }

    /**
     * Extract field IDs and their corresponding values from parameters
     * 
     * @param array $params
     * @return array
     */
    private static function extractValues($params)
    {
        $output = array();

        foreach ($params as $param) {
            $output[$param['id']] = $param['value'];
        }

        return $output;
    }
    
    /**
     * Create message template
     * 
     * @param int $formId
     * @param string $before Text to be inserted before
     * @param string $after Text to be inserted after
     * @return string
     */
    public function createMessageTemplate($formId, $before = null, $after = null)
    {
        $fields = $this->fetchList($formId);

        // Target message
        $message = null;
        $message .= $before;

        foreach ($fields as $id => $name) {
            $message .= sprintf('%s : %s', $name, StringTemplate::wrap($id)) . PHP_EOL;
        }

        $message .= $after;

        return $message;
    }

    /**
     * Group fields entities by their column values
     * 
     * @param array $fields Field entities
     * @return array
     */
    public static function groupFields(array $fields)
    {
        return ArrayUtils::arrayPartition($fields, 'column');
    }

    /**
     * Extract column numbers from field entities
     * 
     * @param array $fields Field entities
     * @return array
     */
    private static function extractColumns(array $fields)
    {
        $columns = array();

        foreach ($fields as $field) {
            // Don't append duplicate values
            if (!in_array($field->getColumn(), $columns)) {
                $columns[] = $field->getColumn();
            }
        }

        return $columns;
    }

    /**
     * Checks whether fields have at lease two distinct columns
     * 
     * @param array $fields Field entities
     * @return boolean
     */
    public static function hasColumns(array $fields)
    {
        $columns = self::extractColumns($fields);
        return count($columns) > 1;
    }

    /**
     * Append dynamic fields on form entity
     * 
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @param \MailForm\Service\FieldValueService $fieldValueService
     * @return void
     */
    public function addFields(VirtualEntity $form, FieldValueService $fieldValueService)
    {
        // Fetch all fields by form ID
        $fields = $this->fetchAll($form->getId(), true);

        foreach ($fields as $field) {
            $values = $fieldValueService->fetchList($field->getId());
            // Append values
            $field->setValues($values);
        }

        // Finally, append prepared fields with their values
        $form->setFields($fields);
    }

    /**
     * Create subject variables
     * 
     * @param array $field Field entities
     * @return array
     */
    public static function createSubjectVars(array $fields)
    {
        $output = array();

        foreach ($fields as $field) {
            if ($field->isSimple()) {
                $key = StringTemplate::wrap($field->getId());
                $output[$key] = $field->getName();
            }
        }

        return $output;
    }

    /**
     * Prepares message subject substituting variables with their corresponding values
     * 
     * @param array $params
     * @param string $rawSubject Subject with variables
     * @return array
     */
    public static function createSubject(array $params, $rawSubject)
    {
        $vars = array(); // Variables to be used

        foreach ($params as $field) {
            // Filter by simple types
            if (in_array($field['type'], FieldTypeCollection::getSimpleTypes())) {
                $vars[$field['id']] = $field['value'];
            }
        }

        return StringTemplate::template($rawSubject, $vars);
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
            // Current input value
            $value = $fields[$entity->getId()];

            $output[] = array(
                'name' => $entity->getName(), // Field name
                'value' => is_array($value) ? implode(', ', $value) : $value, // Always convert to readable string
                'id' => $entity->getId(), // Field ID
                'type' => $entity->getType(), // Type constant
                'required' => $entity->getRequired(), // Whether this field is a must
                'error' => $entity->getError() // Error message, not error itself
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
        $entity->setId($row['id'], FieldEntity::FILTER_INT)
               ->setLangId($row['lang_id'], FieldEntity::FILTER_INT)
               ->setFormId($row['form_id'], FieldEntity::FILTER_INT)
               ->setType($row['type'], FieldEntity::FILTER_INT)
               ->setOrder($row['order'], FieldEntity::FILTER_INT)
               ->setRequired($row['required'], FieldEntity::FILTER_BOOL)
               ->setColumn($row['column'], FieldEntity::FILTER_INT)
               ->setName($row['name'], FieldEntity::FILTER_TAGS)
               ->setHint($row['hint'], FieldEntity::FILTER_TAGS)
               ->setDefault($row['default'], FieldEntity::FILTER_TAGS)
               ->setError($row['error'], FieldEntity::FILTER_TAGS);

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
     * Fetch field Ids and their names by form ID
     * 
     * @param int $formId
     * @return array
     */
    public function fetchList($formId)
    {
        return ArrayUtils::arrayList($this->fieldMapper->fetchAll($formId, true), 'id', 'name');
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
