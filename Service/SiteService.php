<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Krystal\Application\View\ViewManagerInterface;
use RuntimeException;

final class SiteService
{
    /**
     * View manager
     * 
     * @var \Krystal\Application\View\ViewManagerInterface
     */
    private $view;

    /**
     * Form manager service
     * 
     * @var \MailForm\Service\FormManager
     */
    private $formManager;

    /**
     * Field service
     * 
     * @var \MailForm\Service\FieldService
     */
    private $fieldService;

    /**
     * Field value service
     * 
     * @var \MailForm\Service\FieldValueService
     */
    private $fieldValueService;

    /**
     * State initialization
     * 
     * @param \MailForm\Service\FormManager $formManager
     * @param \MailForm\Service\FieldService $fieldService
     * @param \MailForm\Service\FieldValueService $fieldValueService
     * @return void
     */
    public function __construct(FormManager $formManager, FieldService $fieldService, FieldValueService $fieldValueService)
    {
        $this->formManager = $formManager;
        $this->fieldService = $fieldService;
        $this->fieldValueService = $fieldValueService;
    }

    /**
     * Sets configured view instance
     * 
     * @param \Krystal\Application\View\ViewManagerInterface $view
     * @return void
     */
    public function setView(ViewManagerInterface $view)
    {
        // The view is already configured
        $this->view = $view;
    }

    /**
     * Renders the partial form by its id
     * 
     * @param string $id Form id
     * @throws \RuntimeException If trying to render non-AJAX form
     * @return string
     */
    public function render($id)
    {
        $page = $this->formManager->fetchById($id, false);

        if ($page !== false) {
            // Don't let render non-AJAX forms
            if (!$page->isAjaxForm()) {
                throw new RuntimeException('Non-AJAX forms can not be rendered in templates this way');
            }

            // Append dynamic fields
            $this->fieldService->addFields($page, $this->fieldValueService);

            $this->view->disableLayout();

            // Save initial page entity
            $originalPage = $this->view->getVariable('page');

            $response = $this->view->render($page->getTemplate(), array(
                'action' => '/module/mail-form/partial/'. $id,
                'page' => $page
            ));

            // Restore initial page entity
            $this->view->addVariable('page', $originalPage);
            unset($originalPage);

            return $response;

        } else {
            // Wrong form id supplied
            return false;
        }
    }
}
