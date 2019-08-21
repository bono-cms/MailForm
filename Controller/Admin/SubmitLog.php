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

    /**
     * Clears all logs
     * 
     * @return string
     */
    public function clearAction()
    {
        $this->getModuleService('submitLogService')->clearAll();

        $this->flashBag->set('success', 'All message logs have been cleared successfully');
        return 1;
    }

    /**
     * Deletes a log by its ID
     * 
     * @param int $id Message log ID
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('submitLogService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * View message log by its ID
     * 
     * @param int $id Message log ID
     * @return string
     */
    public function viewAction($id)
    {
        $message = $this->getModuleService('submitLogService')->fetchById($id);

        if ($message) {
            return nl2br($message->getMessage());
        } else {
            return false;
        }
    }
}
