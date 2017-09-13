<?php

namespace SFramework\Form;

/**
 * Interface ValidatorInterface
 * Provides method to validate form data.
 *
 * @package SFramework\Form
 */
interface ValidatorInterface
{
    /**
     * Checks a required field.
     *
     * @param string $name
     * @param array  $spec
     * @param array  $data
     *
     * @return bool
     */
    public function validRequired($name, array $spec, array $data);

    /**
     * Checks if a value is contained in
     * a given set of values.
     *
     * @param string $name
     * @param array  $spec
     * @param array  $data
     *
     * @return bool
     */
    public function isValueAllowed($name, array $spec, array $data);

    /**
     * Checks if a value matches a regex.
     *
     * @param string $name
     * @param array  $spec
     * @param array  $data
     *
     * @return bool
     */
    public function matchesRegex($name, array $spec, array $data);
}
