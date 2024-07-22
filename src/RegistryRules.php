<?php
namespace Validator;

use Validator\Rules\Rule;

class RegistryRules
{
    private $instances;

    public function get($key)
    {   
        return $this->instances[$key];
    }

    public function set($key, Rule $rule)
    {
        if(!$this->has($key)){
            $this->instances[$key] = $rule;
        }
    }

    public function has($key)
    {
        return isset($this->instances[$key]);
    }
}