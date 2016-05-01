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
use Krystal\Validate\Pattern;

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
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $form, $title)
    {
        // Load view plugins
        $this->loadMenuWidget();
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                       ->addOne($title);

        return $this->view->render('form', array(
            'form' => $form
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
             ->setMessageView('message');

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
        $form = $this->getFormManager()->fetchById($id);

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
        return $this->invokeRemoval('formManager', $id);
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

        return $this->invokeSave('formManager', $input['id'], $this->request->getPost(), array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'title' => new Pattern\Title()
                )
            )
        ));
    }
}
