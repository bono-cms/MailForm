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
use RecursiveIteratorIterator;
use RecursiveArrayIterator;
use Krystal\Http\RequestInterface;
use Krystal\Captcha\CaptchaInterface;
use Krystal\Validate\Pattern\Captcha as CaptchaPattern;

final class ValidationParser
{
    /**
     * Normalize FILES data
     * Taken from here: http://php.net/manual/en/reserved.variables.files.php#118294
     * 
     * @param array $input
     * @return array
     */
    public static function normalizeFiles(array $input)
    {
        $output = array();

        foreach ($input as $name => $array) {
            foreach ($array as $field => $value) {
                $pointer = &$output[$name];
                if (!is_array($value)) {
                    $pointer[$field] = $value;
                    continue;
                }

                $stack = array(&$pointer);
                $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($value), RecursiveIteratorIterator::SELF_FIRST);

                foreach ($iterator as $key => $value) {
                    array_splice($stack, $iterator->getDepth() + 1);
                    $pointer = &$stack[count($stack) - 1];
                    $pointer = &$pointer[$key];
                    $stack[] = &$pointer;
                    if (!$iterator->hasChildren()) {
                        $pointer[$field] = $value;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Normalize error messages
     * 
     * @param $string Error JSON string
     * @return array
     */
    public static function normalizeErrors($string)
    {
        $errors = json_decode($string, true);

        foreach ($errors as $key => $message) {
            if (is_numeric($key)) {
                $errors[sprintf('field[%s]', $key)] = $message;
                unset($errors[$key]);
            }
        }

        return json_encode($errors);
    }

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
                // If no explicit error message provided, then use default one
                if (empty($field['error'])) {
                    // If found in corresponding messages.php file, then messaged is translated
                    $field['error'] = 'This field is required';
                }

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
