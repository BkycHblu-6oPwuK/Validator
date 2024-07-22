<?php

namespace Validator;

use Validator\Rules\Rule;

class RegistryUserRules
{
    private $ruleNames = [
        'array' => true,
        'boolean' => true,
        'email' => true,
        'float' => true,
        'in' => true,
        'integer' => true,
        'max' => true,
        'min' => true,
        'nullable' => true,
        'numeric' => true,
        'required' => true,
        'string' => true,
    ];

    private $rules = [];

    /**
     * @param string $ruleClass Rule class
     */
    public function addRule(string $ruleClass)
    {
        if(!is_subclass_of($ruleClass, Rule::class)) throw new \Exception("$ruleClass must be a descendant of the \Validator\Rules\Rule class");
        $name = $ruleClass::getRuleName();
        if(isset($this->ruleNames[$name])) throw new \Exception("A rule with this name ($name) already exists");
        $this->ruleNames[$name] = true;
        $this->rules[$name] = $ruleClass;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }
}
