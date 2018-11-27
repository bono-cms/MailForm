<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Closure;
use Krystal\Http\RequestInterface;
use Krystal\Captcha\CaptchaInterface;
use Krystal\Validate\Pattern\Captcha as CaptchaPattern;

final class ValidationParser
{
    /**
     * Create validation rules depending on columns
     * 
     * @param array $fields
     * @param \Closure $input Callback for input
     * @param \Closure $definitionCallback Callback
     * @return array
     */
    private static function createRules(array $fields, Closure $inputCallback = null, $definitionCallback = null)
    {
        $values = array_column($fields, 'value');
        $ids = array_column($fields, 'id');

        $input = array_combine($ids, $values);

        // Apply input callback if defined
        if ($inputCallback instanceof Closure) {
            $input = array_replace($input, $inputCallback($input));
        }

        // Initial rules
        $rules = array(
            'input' => array(
                'source' => $input,
                'definition' => array()
            )
        );

        // Reference shorthand
        $definition =& $rules['input']['definition'];

        // Append field validation rules
        foreach ($fields as $field) {
            // If rule needs to be appended
            if ($field['required']) {
                // Append rule for current field
                $definition[$field['id']] = array(
                    'required' => true,
                    'rules' => array(
                        'NotEmpty' => array(
                            'message' => $field['error']
                        )
                    )
                );
            }
        }

        // Apply input callback if defined
        if ($definitionCallback instanceof Closure) {
            $definition = array_replace($definition, $definitionCallback());
        }

        // Prepared and populated validation rules to be passed to validator component
        return $rules;        
    }

    /**
     * Create validation rules depending without CAPTCHA 
     * 
     * @param array $fields
     * @return array
     */
    public static function createStandart(array $fields)
    {
        return self::createRules($fields);
    }

    /**
     * Create validation rules depending on columns
     * 
     * @param array $fields
     * @param \Krystal\Http\RequestInterface $request
     * @param \Krystal\Captcha\CaptchaInterface $captcha
     * @return array
     */
    public static function createProtected(array $fields, RequestInterface $request, CaptchaInterface $captcha)
    {
        return self::createRules($fields, function($input) use ($request){
            // To be appended
            return array(
                'captcha' => $request->getPost('captcha')
            );
        }, function() use ($captcha){
            return array(
                'captcha' => new CaptchaPattern($captcha)
            );
        });
    }
}
