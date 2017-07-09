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

use Site\Controller\AbstractController;
use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;
use RuntimeException;

final class Form extends AbstractController
{
    /**
     * Returns a list of validation rules for dynamic forms
     * 
     * @param array $input Raw post data
     * @return array
     */
    private function getValidationRules(array $input)
    {
        return array(
            '1' => array(
                'input' => array(
                    'source' => $input,
                    'definition' => array(
                        'name' => new Pattern\Name(),
                        'email' => new Pattern\Email(),
                        'message' => new Pattern\Message(),
                        'captcha' => new Pattern\Captcha($this->captcha)
                    )
                )
            )
        );
    }

    /**
     * Shows a form
     * 
     * @param string $id Form id
     * @return string
     */
    public function indexAction($id)
    {
        if ($this->request->isPost()) {
            return $this->submitAction($id);
        } else {
            return $this->showAction($id);
        }
    }

    /**
     * Shows a partial form (without layout)
     * 
     * @param string $id Form id
     * @return string
     */
    public function partialAction($id)
    {
        if ($this->request->isPost()) {
            return $this->submitAction($id);
        } else {
            return 'Invalid request';
        }
    }

    /**
     * Shows a form
     * 
     * @param string $id Form id
     * @return string
     */
    private function showAction($id)
    {
        $form = $this->getFormManager()->fetchById($id, false);

        if ($form !== false) {
            // Configure view
            $this->loadSitePlugins();
            $this->view->getBreadcrumbBag()
                       ->addOne($form->getName());

            return $this->view->render($form->getTemplate(), array(
                'page' => $form,
                'action' => $this->request->getCurrentUrl(),
                'languages' => $this->getFormManager()->getSwitchUrls($id)
            ));

        } else {
            // Returning false will trigger 404 error automatically
            return false;
        }
    }

    /**
     * Submits a form
     *  
     * @param string $id Form id
     * @return string
     */
    private function submitAction($id)
    {
        $formValidator = $this->getValidator($id, $this->request->getPost());

        if ($formValidator->isValid()) {
            // It's time to send a message
            if ($this->sendMessage($id, $this->request->getPost())) {
                $this->flashBag->set('success', 'Your message has been sent!');
            } else {
                $this->flashBag->set('warning', 'Could not send your message. Please again try later');
            }

            return '1';
        } else {
            return $formValidator->getErrors();
        }
    }

    /**
     * Sends a message from the input
     * 
     * @param string $id Form id
     * @param array $input
     * @throws \RuntimeException If can't fetch message view by associated page id
     * @return boolean
     */
    private function sendMessage($id, array $input)
    {
        $message = $this->getFormManager()->fetchMessageViewById($id, array(
            'input' => $input
        ));

        // Prepare a subject
        $subject = $this->translator->translate('You received a new message from %s <%s>', $input['name'], $input['email']);

        // Grab mailer service
        $mailer = $this->getService('Cms', 'mailer');
        return $mailer->send($subject, $message);
    }

    /**
     * Returns prepared form validator
     * 
     * @param string $id Form id
     * @param array $input Raw input data
     * @throws \RuntimeException if attempted to get non-attached validation rule
     * @return \Krystal\Validate\ValidatorChain
     */
    private function getValidator($id, array $input)
    {
        $rules = $this->getValidationRules($input);

        if (!isset($rules[$id])) {
            throw new RuntimeException(sprintf('No validation rules found for %s id', $id));
        } else {
            $options = $rules[$id];
        }

        return $this->createValidator($options);
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
