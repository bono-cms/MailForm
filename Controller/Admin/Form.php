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
use MailForm\Service\FormEntity;
use MailForm\Collection\FormTypeCollection;
use MailForm\Collection\FlashPositionCollection;

final class Form extends AbstractController
{
    /**
     * Generates a message
     * 
     * @param int $id Form Id
     * @return string
     */
    public function messageAction($id)
    {
        return $this->getModuleService('fieldService')->createMessageTemplate($id);
    }

    /**
     * Creates a form
     * 
     * @param \MailForm\Service\FormEntity|array $form
     * @param string $title Page title
     * @return string
     */
    private function createForm($form, $title)
    {
        $new = is_object($form);

        // Entity object
        $id = $new ? $form->getId() : $form[0]->getId();

        // Load view plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                       ->addOne($title);

        $fields = $this->getModuleService('fieldService')->fetchAll($id, false);

        $flashPolCol = new FlashPositionCollection();

        return $this->view->render('form', array(
            'new' => $new,
            'form' => $form,
            'fields' => $fields,
            'subjectVars' => FieldService::createSubjectVars($fields),
            'flashPositions' => $flashPolCol->getAll()
        ));
    }

    /**
     * Creates add form
     * 
     * @param boolean $seo Whether to enable SEO
     * @param int $type Form type constant
     * @return string
     */
    private function createAddForm($seo, $type)
    {
        $form = new FormEntity();
        $form->setSeo($seo)
             ->setType($type)
             ->setSubject($this->translator->translate('You have received a new message'));

        return $this->createForm($form, 'Add a form');
    }

    /**
     * Renders form for AJAX form
     * 
     * @return string
     */
    public function addAjaxAction()
    {
        return $this->createAddForm(false, FormTypeCollection::TYPE_AJAX);
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createAddForm(true, FormTypeCollection::TYPE_REGULAR);
    }

    /**
     * Renders edit form
     * 
     * @param string $id Mail form id
     * @return string
     */
    public function editAction($id)
    {
        $form = $this->getModuleService('formManager')->fetchById($id, true);

        // if $form isn't false, then its must be entity object
        if ($form !== false) {
            $name = $this->getCurrentProperty($form, 'name');
            return $this->createForm($form, $this->translator->translate('Edit the form "%s"', $name));
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
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

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

        return $this->view->render('index', array(
            'forms' => $this->getModuleService('formManager')->fetchAll()
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

            if ($this->getModuleService('formManager')->updateSeo($seo)) {
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
