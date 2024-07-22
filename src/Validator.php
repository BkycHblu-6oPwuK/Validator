<?php

namespace Validator;

use Validator\Rules\Rule;

abstract class Validator {
    private $data;
    private $rules = [];
    private $messages = [];
    private $validatedData = [];
    private $errors = [];
    private $registry;
    private $userRules = [];

    public final function __construct(array $data) {
        $this->data = $data;
        $this->rules = $this->rules();
        $this->messages = $this->messages();
        $this->registry = new RegistryRules;
        $this->setUserRules();
    }

    abstract protected function rules() : array;

    protected function messages() : array
    {
        return [];
    }

    protected function userRules() : ?RegistryUserRules
    {
        return null;
    }

    private function setUserRules()
    {
        $userRules = $this->userRules();
        if($userRules){
            $this->userRules = $userRules->getRules();
        }
    }

    public final function validate() : bool
    {
        foreach ($this->rules as $field => $rule) {
            $fieldRules = $this->parseRules($rule);
            $value = $this->data[$field] ?? null;
    
            $this->checkRequired($field, $fieldRules, $value);
            
            if(!isset($this->errors[$field])){
                $this->validateField($field, $fieldRules, $value);
                if(isset($this->errors[$field])) $this->checkNullable($field, $fieldRules, $value);
                if(!empty($value) && !isset($this->errors[$field])) $this->validatedData[$field] = $value;
            }
        }

        return empty($this->errors);
    }
    
    private function checkRequired($field, $fieldRules, $value)
    {
        foreach ($fieldRules as $ruleInstance) {
            if ($ruleInstance instanceof Rules\RequiredRule && !$ruleInstance->validate($value)) {
                $this->addError($field, $ruleInstance);
                return;
            }
        }
    }
    
    private function checkNullable($field, $fieldRules, $value)
    {
        foreach ($fieldRules as $ruleInstance) {
            if ($ruleInstance instanceof Rules\NullableRule && $ruleInstance->validate($value)) {
                $this->unsetError($field);
                return;
            }
        }
    }
    
    private function validateField($field, $fieldRules, $value)
    {
        foreach ($fieldRules as $ruleInstance) {
            if ($ruleInstance instanceof Rules\NullableRule) continue;
            if ($ruleInstance instanceof Rules\RequiredRule) continue;
            if (!$ruleInstance->validate($value)) {
                $this->addError($field, $ruleInstance);
            }
        }
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


    private function createRuleInstance($ruleName, $param) : ?Rule
    {
        if(isset($this->userRules[$ruleName])){
            $ruleClass = $this->userRules[$ruleName];
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

    private function addError($field, Rule $ruleInstance)
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
