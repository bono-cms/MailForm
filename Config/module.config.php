<?php

/**
 * Module configuration container
 */

return [
    'name' => 'MailForm',
    'description' => 'Mail forms module allows you to manager forms that send data from your site to your email',
    'bookmarks' => [
        [
            'name' => 'Email logs',
            'controller' => 'MailForm:Admin:SubmitLog@indexAction',
            'icon' => 'fas fa-envelope'
        ]
    ],
    'menu' => [
        'name' => 'Mail forms',
        'icon' => 'fas fa-envelope',
        'items' => [
            [
                'route' => 'MailForm:Admin:Form@gridAction',
                'name' => 'View all forms'
            ],
            [
                'route' => 'MailForm:Admin:Form@addAction',
                'name' => 'Add new form'
            ],
            [
                'route' => 'MailForm:Admin:Form@addAjaxAction',
                'name' => 'Add new AJAX form'
            ],
            [
                'route' => 'MailForm:Admin:SubmitLog@indexAction',
                'name' => 'Submit logs'
            ]
        ]
    ]
];