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

final class SubmitLog extends AbstractController
{
    /**
     * Renders grid
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Mail forms', 'MailForm:Admin:Form@gridAction')
                                       ->addOne('Submit logs');

        return $this->view->render('submit-logs', array(
            'logs' => $this->getModuleService('submitLogService')->fetchAll()
        ));
    }
}