<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Controller;

use Krystal\Stdlib\VirtualEntity;
use Site\Controller\AbstractController;
use MailForm\Service\ValidationParser;
use MailForm\Service\FieldService;

final class Form extends AbstractController
{
    /**
     * Shows a form
     * 
     * @param string $id Form id
     * @return string
     */
    public function indexAction($id)
    {
        // Grab form entity by its ID
        $form = $this->getFormManager()->fetchById($id, false);

        if ($form !== false) {
            if ($this->request->isPost()) {
                return $this->submitAction($form);
            } else {
                return $this->showAction($form);
            }

        } else {
            // Returning false will trigger 404 error automatically
            return false;
        }
    }

    /**
     * Handles a partial form (without layout)
     * 
     * @param string $id Form id
     * @return string
     */
    public function partialAction($id)
    {
        $form = $this->getFormManager()->fetchById($id, false);

        if ($form && $this->request->isPost()) {
            return $this->submitAction($form);
        } else {
            return 'Invalid request';
        }
    }

    /**
     * Shows a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @return string
     */
    private function showAction(VirtualEntity $form)
    {
        // Append dynamic fields
        $this->getModuleService('fieldService')->addFields($form, $this->getModuleService('fieldValueService'));

        // Configure view
        $this->loadSitePlugins();
        $this->view->getBreadcrumbBag()
                   ->addOne($form->getName());

        return $this->view->render($form->getTemplate(), array(
            'page' => $form,
            'action' => $this->request->getCurrentUrl(),
            'languages' => $this->getFormManager()->getSwitchUrls($form->getId())
        ));
    }

    /**
     * Submits a form
     *  
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @return string
     */
    private function submitAction(VirtualEntity $form)
    {
        $params = $this->getModuleService('fieldService')->createParams($this->request->getPost('field'));

        // Generate rules depending on CAPTCHA requirement
        if ($form->getCaptcha()) {
            $rules = ValidationParser::createProtected($params, $this->request, $this->captcha);
        } else {
            $rules = ValidationParser::createStandart($params);
        }

        $formValidator = $this->createValidator($rules);

        if ($formValidator->isValid()) {
            // Prepare subject
            $subject = FieldService::createSubject($params, $form->getSubject());

            // It's time to send a message
            if ($this->sendMessage($form->getId(), $subject, $this->request->getPost())) {
                $this->flashBag->set('success', 'Your message has been sent!');
            } else {
                $this->flashBag->set('warning', 'Could not send your message. Please again try later');
            }

            return '1';
        } else {
            return ValidationParser::normalizeErrors($formValidator->getErrors());
        }
    }

    /**
     * Sends a message from the input
     * 
     * @param string $id Form id
     * @param string $subject Message subject
     * @param array $input
     * @throws \RuntimeException If can't fetch message view by associated page id
     * @return boolean
     */
    private function sendMessage($id, $subject, array $input)
    {
        $body = $this->getFormManager()->fetchMessageViewById($id, array(
            'input' => $input
        ));

        // Grab mailer service
        return $this->getService('Cms', 'mailer')->send($subject, $body);
    }

    /**
     * Returns form manager
     * 
     * @return \MailForm\Service\FormManager
     */
    private function getFormManager()
    {
        return $this->getModuleService('formManager');
    }
}
