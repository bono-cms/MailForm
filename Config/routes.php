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
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/admin/module/mail-form/add' => array(
        'controller' => 'Admin:Add@indexAction'
    ),
    
    '/admin/module/mail-form/add.ajax' => array(
        'controller' => 'Admin:Add@addAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/edit/(:var)' => array(
        'controller' => 'Admin:Edit@indexAction'
    ),
    
    '/admin/module/mail-form/edit.ajax' => array(
        'controller' => 'Admin:Edit@updateAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/delete.ajax' => array(
        'controller' => 'Admin:Browser@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/delete-selected.ajax' => array(
        'controller' => 'Admin:Browser@deleteSelectedAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/mail-form/save-changes.ajax' => array(
        'controller' => 'Admin:Browser@saveChangesAction',
        'disallow' => array('guest')
    )
);
