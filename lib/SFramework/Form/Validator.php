<?php

namespace SFramework\Form;

/**
 * Class Validator
 *
 * @package SFramework\Form
 */
class Validator implements ValidatorInterface
{
    public function validRequired($name, array $spec, array $data)
    {
        // $isRequired = $spec['required'] ?? false;
        $isRequired = isset($spec['required']) ? $spec['required'] : false;

        if (!$isRequired) {
            return true;
        }

        return !empty($data[$name]);
    }

    public function isValueAllowed($name, array $spec, array $data)
    {
        if (!isset($spec['allowedValues'])) {
            return true;
        }

        $value = isset($data[$name]) ? $data[$name] : null;

        return in_array($value, $spec['allowedValues']);
    }

    public function matchesRegex($name, array $spec, array $data)
    {
        if (!isset($spec['regex'])) {
            return true;
        }

        return preg_match($spec['regex'], $data[$name]);
    }
}