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

use Cms\Service\AbstractSiteBootstrapper;

final class SiteBootstrapper extends AbstractSiteBootstrapper
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        $siteService = $this->moduleManager->getModule('MailForm')->getService('siteService');
        $siteService->setView($this->view);

        $this->view->addVariable('form', $siteService);

        // Append path to mf-* files
        $this->view->getPartialBag()
                   ->addPartialDir($this->view->createThemePath('MailForm', 'partials'));
    }
}
