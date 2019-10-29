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

        $extraFields = $this->getModuleService('fieldService')->fetchAll($id, false);

        $flashPolCol = new FlashPositionCollection();

        // Load fields, if possible
        $this->loadFields($id);

        return $this->view->render('form', array(
            'new' => $new,
            'form' => $form,
            'extraFields' => $extraFields,
            'subjectVars' => FieldService::createSubjectVars($extraFields),
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

        if ($type === FormTypeCollection::TYPE_REGULAR) {
            // CMS configuration object
            $config = $this->getService('Cms', 'configManager')->getEntity();

            $form->setChangeFreq($config->getSitemapFrequency())
                 ->setPriority($config->getSitemapPriority());
        }

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
        $historyService = $this->getService('Cms', 'historyManager');
        $service = $this->getModuleService('formManager');

        // Batch removal
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

            $service->delete($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

            // Save in the history
            $historyService->write('MailForm', 'Batch removal of %s mail forms', count($ids));

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $form = $this->getModuleService('formManager')->fetchById($id, false);

            $service->delete($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');

            // Save in the history
            $historyService->write('MailForm', 'Mail form "%s" has been removed', $form->getName());
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

        // Save dynamic fields, if present
        $this->saveFields('form');

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name()
                )
            )
        ));

        if (1) {
            // Current page name
            $name = $this->getCurrentProperty($this->request->getPost('translation'), 'name');

            $service = $this->getModuleService('formManager');
            $historyService = $this->getService('Cms', 'historyManager');

            // Save the form
            $service->save($this->request->getPost());

            if (!empty($input['id'])) {
                $this->flashBag->set('success', 'The element has been updated successfully');

                $historyService->write('MailForm', 'Mail form "%s" has been updated', $name);
                return '1';

            } else {
                $this->flashBag->set('success', 'The element has been created successfully');

                $historyService->write('MailForm', 'Mail form "%s" has been created', $name);
                return $service->getLastId();
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
