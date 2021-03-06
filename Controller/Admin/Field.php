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
use Krystal\Stdlib\VirtualEntity;
use MailForm\Collection\FieldTypeCollection;
use MailForm\Collection\FieldStateCollection;

final class Field extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $field
     * @param string $title
     * @return string
     */
    private function createForm($field, $title)
    {
        $new = is_object($field);

        // Grab entity
        $entity = $new ? $field : $field[0];

        // First of all, find form entity by its id
        $form = $this->getModuleService('formManager')->fetchById($entity->getFormId(), false);

        if ($form !== false) {
            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                           ->addOne($this->translator->translate('Edit the form "%s"', $form->getName()), $this->createUrl('MailForm:Admin:Form@editAction', array($entity->getFormId())))
                                           ->addOne($title);

            $fTypeCol = new FieldTypeCollection;
            $fStateCol = new FieldStateCollection;

            $fieldValueService = $this->getModuleService('fieldValueService');
            
            return $this->view->render('field.form', array(
                'canHaveValue' => $entity->canHaveValue(),
                'field' => $field,
                'new' => $new,
                'types' => $fTypeCol->getAll(),
                'states' => $fStateCol->getAll(),
                'values' => !$new ? $fieldValueService->fetchAll($entity->getId()) : array(),
                'grouped' => !$new ? $fieldValueService->fetchGrouped($entity->getId()) : array()
            ));
        } else {
            // Wrong form id supplied
            return false;
        }
    }

    /**
     * Renders adding form
     * 
     * @param int $formId Attached form ID
     * @return string
     */
    public function addAction($formId)
    {
        $form = new VirtualEntity();
        $form->setFormId($formId)
             ->setType(FieldTypeCollection::TYPE_TEXT); // Set text type by default

        return $this->createForm($form, 'Add new field');
    }

    /**
     * Renders edit form
     * 
     * @param int $id Field ID
     * @return mixed
     */
    public function editAction($id)
    {
        $field = $this->getModuleService('fieldService')->fetchById($id, true);

        if ($field !== false) {
            $name = $this->getCurrentProperty($field, 'name');
            return $this->createForm($field, $this->translator->translate('Edit the field "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Deletes a form
     * 
     * @param int $id Form ID
     * @return mixed
     */
    public function deleteAction($id)
    {
        $this->getModuleService('fieldService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves a field
     * 
     * @return mixed
     */
    public function saveAction()
    {
        $input = $this->request->getPost();
        $new = (bool) !$input['field']['id'];

        $fieldService = $this->getModuleService('fieldService');
        $fieldService->save($input);

        if (!$new) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $fieldService->getLastId();
        }
    }
}
