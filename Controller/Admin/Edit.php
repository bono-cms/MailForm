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

final class Edit extends AbstractForm
{
    /**
     * Shows edit form
     * 
     * @param string $id Form id
     * @return string
     */
    public function indexAction($id)
    {
        $form = $this->getFormManager()->fetchById($id);

        // if $form isn't false, then its must be entity object
        if ($form !== false) {
            $this->loadSharedPlugins();
            $this->loadBreadcrumbs('Edit the form');

            return $this->view->render('form', array(
                'title' => 'Edit the form',
                'form' => $form
            ));

        } else {
            return false;
        }
    }

    /**
     * Updates a form
     * 
     * @return string
     */
    public function updateAction()
    {
        $formValidator = $this->getValidator($this->request->getPost('form'));

        if ($formValidator->isValid()) {
            if ($this->getFormManager()->update($this->request->getPost())) {
                $this->flashBag->set('success', 'The form has been updated successfully');
                return '1';
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
