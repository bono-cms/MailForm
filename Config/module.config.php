<?php

/**
 * Module configuration container
 */

return array(
    'name' => 'MailForm',
    'description' => 'Mail forms module allows you to manager forms that send data from your site to your email',
    'bookmarks' => array(
        array(
            'name' => 'Email logs',
            'controller' => 'MailForm:Admin:SubmitLog@indexAction',
            'icon' => 'fas fa-envelope'
        )
    ),
    'menu' => array(
        'name' => 'Mail forms',
        'icon' => 'fas fa-envelope',
        'items' => array(
            array(
                'route' => 'MailForm:Admin:Form@gridAction',
                'name' => 'View all forms'
            ),
            array(
                'route' => 'MailForm:Admin:Form@addAction',
                'name' => 'Add new form'
            ),
            array(
                'route' => 'MailForm:Admin:Form@addAjaxAction',
                'name' => 'Add new AJAX form'
            ),
            array(
                'route' => 'MailForm:Admin:SubmitLog@indexAction',
                'name' => 'Submit logs'
            )
        )
    )
);