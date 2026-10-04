<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    '/module/mail-form/partial/(:var)' => [
        'controller' => 'Form@partialAction'
    ],

    '/module/mail-form/(:var)' => [
        'controller' => 'Form@indexAction'
    ],

    '/%s/module/mail-form' => [
        'controller' => 'Admin:Form@gridAction'
    ],
    
    '/%s/module/mail-form/generate-message/(:var)' => [
        'controller' => 'Admin:Form@messageAction'
    ],

    '/%s/module/mail-form/add' => [
        'controller' => 'Admin:Form@addAction'
    ],
    
    '/%s/module/mail-form/add-ajax' => [
        'controller' => 'Admin:Form@addAjaxAction'
    ],
    
    '/%s/module/mail-form/edit/(:var)' => [
        'controller' => 'Admin:Form@editAction'
    ],
    
    '/%s/module/mail-form/save' => [
        'controller' => 'Admin:Form@saveAction',
        'disallow' => ['guest']
    ],
    
    '/%s/module/mail-form/delete/(:var)' => [
        'controller' => 'Admin:Form@deleteAction',
        'disallow' => ['guest']
    ],
    
    '/%s/module/mail-form/tweak' => [
        'controller' => 'Admin:Form@tweakAction',
        'disallow' => ['guest']
    ],

    // Submit logger
    '/%s/module/mail-form/submit-logs' => [
        'controller' => 'Admin:SubmitLog@indexAction'
    ],

    '/%s/module/mail-form/submit-logs/clear' => [
        'controller' => 'Admin:SubmitLog@clearAction'
    ],

    '/%s/module/mail-form/submit-logs/view/(:var)' => [
        'controller' => 'Admin:SubmitLog@viewAction'
    ],

    '/%s/module/mail-form/submit-logs/delete/(:var)' => [
        'controller' => 'Admin:SubmitLog@deleteAction'
    ],

    // Dynamic fields
    '/%s/module/mail-form/field/add/(:var)' => [
        'controller' => 'Admin:Field@addAction'
    ],

    '/%s/module/mail-form/field/edit/(:var)' => [
        'controller' => 'Admin:Field@editAction'
    ],

    '/%s/module/mail-form/field/save' => [
        'controller' => 'Admin:Field@saveAction',
        'disallow' => ['guest']
    ],

    '/%s/module/mail-form/field/delete/(:var)' => [
        'controller' => 'Admin:Field@deleteAction',
        'disallow' => ['guest']
    ],

    // Field values
    '/%s/module/mail-form/field-value/add/(:var)' => [
        'controller' => 'Admin:FieldValue@addAction'
    ],

    '/%s/module/mail-form/field-value/edit/(:var)' => [
        'controller' => 'Admin:FieldValue@editAction'
    ],

    '/%s/module/mail-form/field-value/save' => [
        'controller' => 'Admin:FieldValue@saveAction',
        'disallow' => ['guest']
    ],

    '/%s/module/mail-form/field-value/delete/(:var)' => [
        'controller' => 'Admin:FieldValue@deleteAction',
        'disallow' => ['guest']
    ]
];