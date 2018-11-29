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

final class SiteService implements SiteServiceInterface
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
     * @var \MailForm\Service\FormManagerInterface
     */
    private $formManager;

    /**
     * State initialization
     * 
     * @param \MailForm\Service\FormManagerInterface $formManager
     * @return void
     */
    public function __construct(FormManagerInterface $formManager)
    {
        $this->formManager = $formManager;
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
