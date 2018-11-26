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

final class Field extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $field
     * @return string
     */
    private function createForm($field)
    {
        $new = is_object($field);

        // Grab ID
        if ($new) {
            $formId = $field->getFormId();
        } else {
            $formId = $field[0]->getFormId();
            $fieldId = $field[0]->getFieldId();
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                       ->addOne('Edit the form', $this->createUrl('MailForm:Admin:Form@editAction', array($formId)))
                                       ->addOne($new ? 'Add new field' : 'Edit the field');

        $fTypeCol = new FieldTypeCollection;

        return $this->view->render('field.form', array(
            'field' => $field,
            'new' => $new,
            'types' => $fTypeCol->getAll(),
            'values' => isset($fieldId) ? $this->getModuleService('fieldValueService')->fetchAll($fieldId) : array()
        ));
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
        $form->setFormId($formId);

        return $this->createForm($form);
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
            return $this->createForm($field);
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
