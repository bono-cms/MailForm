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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;
use Cms\Controller\Admin\AbstractController;
use MailForm\Service\FieldService;

final class Form extends AbstractController
{
    /**
     * Returns form manager
     * 
     * @return \MailForm\Service\FormManager
     */
    private function getFormManager()
    {
        return $this->getModuleService('formManager');
    }

    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $form
     * @param string $title
     * @return string
     */
    private function createForm($form, $title)
    {
        $new = is_object($form);

        if ($new) {
            $id = $form->getId();
        } else {
            $id = $form[0]->getId();
        }

        // Load view plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                       ->addOne($title);

        $fields = $this->getModuleService('fieldService')->fetchAll($id, false);
        
        return $this->view->render('form', array(
            'form' => $form,
            'fields' => $fields,
            'subjectVars' => FieldService::createSubjectVars($fields)
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        $form = new VirtualEntity();
        $form->setSeo(true)
             ->setMessageView('message')
             ->setSubject($this->translator->translate('You have received a new message'));

        return $this->createForm($form, 'Add a form');
    }
    
    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $form = $this->getFormManager()->fetchById($id, true);

        // if $form isn't false, then its must be entity object
        if ($form !== false) {
            return $this->createForm($form, 'Edit the form');
        } else {
            return false;
        }
    }

    /**
     * Deletes a form by its associated id
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('formManager');

        // Batch removal
        if ($this->request->hasPost('toDelete')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
        }

        return '1';
    }

    /**
     * Renders a grid
     * 
     * @return string
     */
    public function gridAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()
                   ->addOne('Mail forms');

        return $this->view->render('browser', array(
            'forms' => $this->getFormManager()->fetchAll()
        ));
    }

    /**
     * Saves changes
     * 
     * @return string
     */
    public function tweakAction()
    {
        if ($this->request->hasPost('seo')) {
            $seo = $this->request->getPost('seo');

            if ($this->getFormManager()->updateSeo($seo)) {
                $this->flashBag->set('success', 'Settings have been updated successfully');
                return '1';
            }
        }
    }

    /**
     * Persists a form
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('form');

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name()
                )
            )
        ));

        if (1) {
            $service = $this->getModuleService('formManager');

            if (!empty($input['id'])) {
                if ($service->update($this->request->getPost())) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($this->request->getPost())) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
