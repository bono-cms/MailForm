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
    '/module/mail-form/(:var)' => array(
        'controller' => 'Form@indexAction'
    ),

    '/admin/module/mail-form' => array(
        'controller' => 'Admin:Form@gridAction'
    ),
    
    '/admin/module/mail-form/add' => array(
        'controller' => 'Admin:Form@addAction'
    ),
    
    '/admin/module/mail-form/edit/(:var)' => array(
        'controller' => 'Admin:Form@editAction'
    ),
    
    '/admin/module/mail-form/save' => array(
        'controller' => 'Admin:Form@saveAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/delete' => array(
        'controller' => 'Admin:Form@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/tweak' => array(
        'controller' => 'Admin:Form@tweakAction',
        'disallow' => array('guest')
    )
);
