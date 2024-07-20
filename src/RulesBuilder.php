<?php

namespace Validator;

class RulesBuilder
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
    /** @var RuleDTO[] $rules */
    private $rules = [];

    public function addRule(RuleDTO $rule)
    {
        $name = $rule->getName();
        if(isset($this->ruleNames[$name])) throw new \Exception("A rule with this name ($name) already exists");
        $this->ruleNames[$name] = true;
        $this->rules[] = $rule;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }
}
