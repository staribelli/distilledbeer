<?php

namespace SFramework\Form;

/**
 * Class AbstractForm
 * Base Form class.
 *
 * @package SFramework\Form
 */
abstract class AbstractForm
{
    protected $validator;
    protected $data = [];
    protected $errorMessages = [];

    /**
     * Validation rules.
     *
     * @return array
     */
    abstract function rules();

    public function __construct(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;

        if (is_null($this->validator)) {
            $this->validator = new Validator();
        }
    }

    /**
     * Set the data in the form.
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($this->rules() as $name => $spec) {
            if (isset($data[$name])) {
                $this->data[$name] = $data[$name];
            }
        }
    }

    /**
     * Get the set data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Validate the data and populate
     * the error messages, if needed.
     */
    public function validate()
    {
        foreach ($this->rules() as $name => $spec) {
            $validRequired = $this->validator->validRequired($name, $spec,
                $this->data);

            if (!$validRequired) {
                $this->errorMessages[$name] = $name . ' is required';
            }

            if ($validRequired
                && !$this->validator->isValueAllowed($name, $spec, $this->data)
            ) {
                $this->errorMessages[$name]
                    = 'Value ' . $this->data[$name] . ' for field ' . $name
                    . ' is not allowed';
            }

            if ($validRequired
                && !$this->validator->matchesRegex($name, $spec, $this->data)
            ) {
                // This overrides previous messages, would be nice to have multiple messages
                // for the same field
                $this->errorMessages[$name]
                    = 'Value ' . $this->data[$name] . ' for field ' . $name
                    . ' is not valid';
            }
        }
    }

    /**
     * Get validation error messages.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errorMessages;
    }

    /**
     * Returns if the form is valid or not.
     *
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errorMessages);
    }
}