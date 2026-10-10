<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Krystal\Validation\Validator;
use Krystal\Captcha\CaptchaInterface;
use MailForm\Collection\FieldTypeCollection;

/**
 * The validator is injected by the controller (typically via $this->createValidation()),
 * so translator wiring, locale, and data/files binding are handled by the framework.
 * This parser only attaches field and CAPTCHA definitions.
 */
final class ValidationParser
{
    /**
     * Validator instance prepared by the controller
     *
     * @var \Krystal\Validation\Validator
     */
    private $validator;

    /**
     * State initialization
     * 
     * @param \Krystal\Validation\Validator $validator Pre-built validator to decorate
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Applies dynamic field definitions without CAPTCHA protection
     *
     * @param array $fields Field metadata produced by FieldService::parseInput()
     * @return \Krystal\Validation\Validator
     */
    public function createStandart(array $fields): Validator
    {
        return $this->apply($fields, null);
    }

    /**
     * Applies dynamic field definitions with CAPTCHA protection
     *
     * @param array $fields
     * @param \Krystal\Captcha\CaptchaInterface $captcha
     * @return \Krystal\Validation\Validator
     */
    public function createProtected(array $fields, CaptchaInterface $captcha): Validator
    {
        return $this->apply($fields, $captcha);
    }

    /**
     * Attaches field and (optionally) CAPTCHA rules to the injected validator
     *
     * @param array $fields
     * @param \Krystal\Captcha\CaptchaInterface|null $captcha
     * @return \Krystal\Validation\Validator
     */
    private function apply(array $fields, CaptchaInterface $captcha = null): Validator
    {
        if ($captcha !== null) {
            $this->validator->field('captcha', 'CAPTCHA')
                            ->required()
                            ->addRule('captcha', null, ['expected' => (string) $captcha->getAnswer()]);
        }

        // Register dynamic field definitions
        foreach ($fields as $field) {
            $id       = $field['id'];
            $label    = isset($field['name']) && $field['name'] !== '' ? $field['name'] : null;
            $error    = !empty($field['error']) ? $field['error'] : null;
            $required = !empty($field['required']);
            $path     = 'field.' . $id;

            $definition = FieldTypeCollection::isFileType($field['type']) ? $this->validator->file($path, $label) : $this->validator->field($path, $label);

            if ($required) {
                $definition->required($error);
            }
        }

        return $this->validator;
    }
}
