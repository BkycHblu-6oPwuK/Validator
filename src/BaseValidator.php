<?php

namespace Validator;

use Validator\Rules\BaseRule;

abstract class BaseValidator {
    protected $data;
    protected $rules = [];
    protected $messages = [];
    protected $validatedData = [];
    protected $errors = [];
    private $registry;
    private $customRules = [];

    public final function __construct(array $data) {
        $this->data = $data;
        $this->rules = $this->rules();
        $this->messages = $this->messages();
        $this->registry = new RegistryRules;
        $this->makeCustomRules();
    }

    abstract protected function rules() : array;

    protected function messages() : array
    {
        return [];
    }

    protected function rulesBuilder() : ?RulesBuilder
    {
        return null;
    }

    private function makeCustomRules()
    {
        $builder = $this->rulesBuilder();
        if($builder){
            foreach($builder->getRules() as $rule){
                $this->customRules[$rule->getName()] = $rule->getRule();
            }
        }
    }

    public final function validate() : bool
    {
        foreach ($this->rules as $field => $rule) {
            $fieldRules = $this->parseRules($rule);
            if (!empty($this->data[$field])) {
                $value = $this->data[$field];
                foreach ($fieldRules as $ruleInstance) {
                    if ($ruleInstance instanceof Rules\NullableRule) continue;
                    if (!$ruleInstance->validate($value)) $this->addError($field, $ruleInstance);
                }
            } else {
                foreach ($fieldRules as $ruleInstance) {
                    if ($ruleInstance instanceof Rules\RequiredRule) $this->addError($field, $ruleInstance);
                    if ($ruleInstance instanceof Rules\NullableRule) $this->unsetError($field);
                }
            }

            if (!isset($this->errors[$field])) {
                $this->validatedData[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    private function parseRules($rules)
    {
        $parsedRules = [];
        foreach (explode('|', $rules) as $rule) {
            if (strpos($rule, ':') !== false) {
                [$ruleName, $param] = explode(':', $rule, 2);
            } else {
                $ruleName = $rule;
                $param = null;
            }

            $ruleInstance = $this->createRuleInstance($ruleName, $param);
            if ($ruleInstance) {
                $parsedRules[] = $ruleInstance;
            }
        }

        return $parsedRules;
    }


    private function createRuleInstance($ruleName, $param) : ?BaseRule
    {
        if(isset($this->customRules[$ruleName])){
            $ruleClass = $this->customRules[$ruleName];
        } else {
            $ruleClass = __NAMESPACE__ . '\\Rules\\' . ucfirst($ruleName) . 'Rule';
        }
        
        if (class_exists($ruleClass)) {
            $key = $ruleClass.$param;
            if(!$this->registry->has($key)){
                $this->registry->set($key, new $ruleClass($param));
            }
            return $this->registry->get($key);
        }
        return null;
    }

    private function addError($field, BaseRule $ruleInstance)
    {
        $ruleName = $ruleInstance->getRuleName();
        $message = $this->messages["{$field}.{$ruleName}"] ?? $ruleInstance->getMessage();
        $this->errors[$field][$ruleName] = $message;
    }

    private function unsetError($field)
    {
        unset($this->errors[$field]);
    }

    public final function validated()
    {
        if (!empty($this->errors)) {
            throw new \InvalidArgumentException('Validation errors: ' . json_encode($this->errors));
        }
        return $this->validatedData;
    }

    public final function errors() 
    {
        return $this->errors;
    }
}
