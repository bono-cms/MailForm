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
use Krystal\Validate\Pattern;
use MailForm\Collection\FieldTypeCollection;

final class ValidationParser
{
    /**
     * All request data (POST and Files)
     * 
     * @var array
     */
    private $request;

    /**
     * State initialization
     * 
     * @param array $request All request data
     * @return void
     */
    public function __construct(array $request)
    {
        $this->request = $request;
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
    private function createRules(array $fields, Closure $inputCallback = null, $definitionCallback = null)
    {
        $values = array_column($fields, 'value');
        $ids = array_column($fields, 'id');

        $input = array_combine($ids, $values);

        // Apply input callback if defined
        if ($inputCallback instanceof Closure) {
            $input = array_replace($input, $inputCallback($input));
        }

        $files = isset($this->request['files']) ? $this->request['files']['field'] : array();

        // Fix missing keys
        foreach ($fields as $field) {
            if (!isset($files[$field['id']]) && $field['type'] == FieldTypeCollection::TYPE_FILE) {
                $files[$field['id']] = array();
            }
        }

        // Initial rules
        $rules = array(
            'input' => array(
                'source' => $input,
                'definition' => array()
            ),
            'file' => array(
                'source' => $files,
                'definition' => array()
            )
        );

        // Append field validation rules
        foreach ($fields as $field) {
            // If rule needs to be appended
            if ($field['required']) {
                // Check if file by type
                if ($field['type'] == FieldTypeCollection::TYPE_FILE) {
                    $rules['file']['definition'][$field['id']] = array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                // If no explicit error message provided, then use default one
                                'message' => !empty($field['error']) ? $field['error'] : 'Please select a file'
                            )
                        )
                    );
                } else {
                    // Append rule for current text-like field
                    $rules['input']['definition'][$field['id']] = array(
                        'required' => true,
                        'rules' => array(
                            'NotEmpty' => array(
                                // If no explicit error message provided, then use default one
                                'message' => !empty($field['error']) ? $field['error'] : 'This field is required'
                            )
                        )
                    );
                }
            }
        }

        // Apply input callback if defined
        if ($definitionCallback instanceof Closure) {
            $rules['input']['definition'] = array_replace($rules['input']['definition'], $definitionCallback());
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
    public function createStandart(array $fields)
    {
        return self::createRules($fields);
    }

    /**
     * Create validation rules depending on columns
     * 
     * @param array $fields
     * @param \Krystal\Captcha\CaptchaInterface $captcha
     * @return array
     */
    public function createProtected(array $fields, CaptchaInterface $captcha)
    {
        $data = $this->request['data'];

        return self::createRules($fields, function($input) use ($data){
            // To be appended
            return array(
                'captcha' => isset($data['captcha']) ? $data['captcha'] : null
            );
        }, function() use ($captcha){
            return array(
                'captcha' => new Pattern\Captcha($captcha)
            );
        });
    }
}
