<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm;

use Cms\AbstractCmsModule;
use MailForm\Service\FormManager;
use MailForm\Service\SiteService;
use MailForm\Service\FieldService;
use MailForm\Service\FieldValueService;
use MailForm\Service\SubmitLogService;

final class Module extends AbstractCmsModule
{
    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $formMapper = $this->getMapper('/MailForm/Storage/MySQL/FormMapper');
        $formManager = new FormManager($formMapper, $this->getWebPageManager());

        $fieldValueService = new FieldValueService($this->getMapper('/MailForm/Storage/MySQL/FieldValueMapper'));
        $fieldService = new FieldService($this->getMapper('/MailForm/Storage/MySQL/FieldMapper'));

        return array(
            'submitLogService' => new SubmitLogService($this->getMapper('/MailForm/Storage/MySQL/SubmitLogMapper')),
            'fieldValueService' => $fieldValueService,
            'fieldService' => $fieldService,
            'formManager' => $formManager,
            'siteService' => new SiteService($formManager, $fieldService, $fieldValueService)
        );
    }
}
