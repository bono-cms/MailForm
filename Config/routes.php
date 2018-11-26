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
    ),
    
    // Dynamic fields
    '/%s/module/mail-form/field/add/(:var)' => array(
        'controller' => 'Admin:Field@addAction'
    ),

    '/%s/module/mail-form/field/edit/(:var)' => array(
        'controller' => 'Admin:Field@editAction'
    ),

    '/%s/module/mail-form/field/save' => array(
        'controller' => 'Admin:Field@saveAction',
        'disallow' => array('guest')
    ),

    '/%s/module/mail-form/field/delete/(:var)' => array(
        'controller' => 'Admin:Field@deleteAction',
        'disallow' => array('guest')
    ),

    // Field values
    '/%s/module/mail-form/field-value/add/(:var)' => array(
        'controller' => 'Admin:FieldValue@addAction'
    ),

    '/%s/module/mail-form/field-value/edit/(:var)' => array(
        'controller' => 'Admin:FieldValue@editAction'
    ),

    '/%s/module/mail-form/field-value/save' => array(
        'controller' => 'Admin:FieldValue@saveAction',
        'disallow' => array('guest')
    ),

    '/%s/module/mail-form/field-value/delete/(:var)' => array(
        'controller' => 'Admin:FieldValue@deleteAction',
        'disallow' => array('guest')
    )
);
