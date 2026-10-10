<?php

/**
 * This file is part of the Bono CMS
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
            return $this->json([
                'errors' => 'Invalid request'
            ]);
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

        // Append fields on demand (Not to be confused with dynamic input fields of current module)
        // These ones come from Block module
        $this->appendFieldsIfPossible($form);

        return $this->view->render($form->getTemplate(), [
            'page' => $form,
            'action' => $this->request->getCurrentUrl(),
            'languages' => $this->getModuleService('formManager')->getSwitchUrls($form->getId())
        ]);
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

            $this->flashBag->set('success', $form->getFlash() ? $form->getFlash() : 'Your message has been sent!');
            return $this->json(['refresh' => true]);
        }

        if ($result === false) {
            $this->flashBag->set('warning', 'Could not send your message. Please again try later');
            return $this->json(['refresh' => true]);
        }

        return $this->json($result);
    }

    /**
     * Submits a form and sends a message
     * 
     * @param \Krystal\Stdlib\VirtualEntity $form
     * @return boolean|array
     */
    private function processForm(VirtualEntity $form)
    {
        $input = $this->request->getAll();

        $fieldService = $this->getModuleService('fieldService');
        $fields = $fieldService->parseInput($form->getId(), $input);

        // Framework-provided validator (translator + payload already wired up)
        $validator = $this->createValidation();

        $parser = new ValidationParser($validator);

        if ($form->getCaptcha()) {
            $parser->createProtected($fields, $this->captcha);
        } else {
            $parser->createStandart($fields);
        }

        // Both methods return the same decorated validator, so no reassignment needed.
        // $validator is now fully populated.

        if ($validator->isPassed()) {
            $subject = FieldService::createSubject($fields, $form->getSubject());
            $body    = $fieldService->createMessage($form->getMessage(), $fields);
            $files   = isset($input['files']['field']) ? $input['files']['field'] : [];

            if ($this->getService('Cms', 'mailer')->send($subject, $body, null, $files)) {
                $this->getModuleService('submitLogService')->log($subject, $body, $files);
                return true;
            }

            return false;
        }

        return [
            'errors' => $validator->getErrors()
        ];
    }
}
