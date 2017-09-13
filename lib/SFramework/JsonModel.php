<?php

namespace SFramework;

/**
 * Class JsonModel
 * Represents a Json view.
 *
 * @package SFramework
 */
class JsonModel
{
    protected $variables;

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function getVariable($name)
    {
        return $this->variables[$name];
    }
}