<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use MailForm\Collection\FieldStateCollection;
use MailForm\Service\FieldValueEntity;

final class FieldValue extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $value
     * @param string $title Page title
     * @return string
     */
    private function createForm($value, $title)
    {
        $new = is_object($value);

        // Grab entity
        $entity = $new ? $value : $value[0];

        // First of all, find form and field entities by their ids
        $form = $this->getModuleService('formManager')->fetchById($entity->getFormId(), false);
        $field = $this->getModuleService('fieldService')->fetchById($entity->getFieldId(), false);

        if ($form !== false && $field !== false) {
            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                           ->addOne($this->translator->translate('Edit the form "%s"', $form->getName()), $this->createUrl('MailForm:Admin:Form@editAction', array($entity->getFormId())))
                                           ->addOne($this->translator->translate('Edit the field "%s"', $field->getName()), $this->createUrl('MailForm:Admin:Field@editAction', array($entity->getFieldId())))
                                           ->addOne($title);

            $fStateCol = new FieldStateCollection;

            return $this->view->render('value.form', array(
                'value' => $value,
                'new' => $new,
                'states' => $fStateCol->getAll()
            ));
        } else {
            return false;
        }
    }

    /**
     * Renders adding form
     * 
     * @return string
     */
    public function addAction()
    {
        if ($this->request->hasQuery('form_id', 'field_id')) {
            // Request variables
            $fieldId = $this->request->getQuery('field_id');
            $formId = $this->request->getQuery('form_id');

            // Get field entity
            $field = $this->getModuleService('fieldService')->fetchById($fieldId, false);

            // If invalid field ID provided, then trigger 404 error
            if ($field === false) {
                return false;
            }

            // Otherwise continue flow
            $form = new FieldValueEntity();
            $form->setFieldId($fieldId)
                 ->setFormId($formId)
                 ->setType($field->getType());

            return $this->createForm($form, 'Add new value');
            
        } else {
            return false;
        }
    }

    /**
     * Renders edit form
     * 
     * @param int $id Value ID
     * @return mixed
     */
    public function editAction($id)
    {
        $value = $this->getModuleService('fieldValueService')->fetchById($id, true);

        if ($value !== false) {
            $name = $this->getCurrentProperty($value, 'value');
            return $this->createForm($value, $this->translator->translate('Edit the value "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Deletes a value
     * 
     * @param int $id Value ID
     * @return mixed
     */
    public function deleteAction($id)
    {
        $this->getModuleService('fieldValueService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves field value
     * 
     * @return mixed
     */
    public function saveAction()
    {
        $input = $this->request->getPost();
        $new = (bool) !$input['value']['id'];

        $fieldValueService = $this->getModuleService('fieldValueService');
        $fieldValueService->save($input);

        if (!$new) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $fieldValueService->getLastId();
        }
    }
}
