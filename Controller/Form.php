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
        $form = $this->getModuleService('formManager')->fetchById($id, false);

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
        $form = $this->getModuleService('formManager')->fetchById($id, false);

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
            'languages' => $this->getModuleService('formManager')->getSwitchUrls($form->getId())
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
        $result = $this->processForm($form);

        if ($result === true) {

            // Here you can add some logic to alter default behavior after successful form submission
            // By default 1 is returned and page gets refreshed showing flash message

            // Use explicit flash message if provided, otherwise fallback to default one
            $this->flashBag->set('success', $form->getFlash() ? $form->getFlash() : 'Your message has been sent!');
            return 1;
        } else if ($result === false) {
            $this->flashBag->set('warning', 'Could not send your message. Please again try later');
            return 1;
        } else {
            // Error messages
            return $result;
        }
    }
    
    /**
     * Submits a form and sends a message
     * 
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @return boolean|string
     */
    private function processForm(VirtualEntity $form)
    {
        // Get input data and files
        $input = $this->request->getAll();

        $fieldService = $this->getModuleService('fieldService');

        // Get all request data (POST data and files if present)
        $fields = $fieldService->parseInput($form->getId(), $input);

        $validationParser = new ValidationParser($input);

        // Generate rules depending on CAPTCHA requirement
        if ($form->getCaptcha()) {
            $rules = $validationParser->createProtected($fields, $this->captcha);
        } else {
            $rules = $validationParser->createStandart($fields);
        }

        $formValidator = $this->createValidator($rules);

        if ($formValidator->isValid()) {
            // Prepare subject
            $subject = FieldService::createSubject($fields, $form->getSubject());
            // Create body
            $body = $fieldService->createMessage($form->getMessage(), $fields);

            // Request files if available
            $files = isset($input['files']['field']) ? $input['files']['field'] : array();

            // It's time to send a message
            if ($this->getService('Cms', 'mailer')->send($subject, $body, null, $files)) {
                // Log current message
                $this->getModuleService('submitLogService')->log($subject, $body, $files);

                // Success
                return true;
            } else {
                // Error
                return false;
            }
        } else {
            return ValidationParser::normalizeErrors($formValidator->getErrors());
        }
    }
}
