<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    '/module/mail-form/partial/(:var)' => array(
        'controller' => 'Form@partialAction'
    ),

    '/module/mail-form/(:var)' => array(
        'controller' => 'Form@indexAction'
    ),

    '/%s/module/mail-form' => array(
        'controller' => 'Admin:Form@gridAction'
    ),
    
    '/%s/module/mail-form/add' => array(
        'controller' => 'Admin:Form@addAction'
    ),
    
    '/%s/module/mail-form/edit/(:var)' => array(
        'controller' => 'Admin:Form@editAction'
    ),
    
    '/%s/module/mail-form/save' => array(
        'controller' => 'Admin:Form@saveAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/mail-form/delete/(:var)' => array(
        'controller' => 'Admin:Form@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/mail-form/tweak' => array(
        'controller' => 'Admin:Form@tweakAction',
        'disallow' => array('guest')
    )
);
