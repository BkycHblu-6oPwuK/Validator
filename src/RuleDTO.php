<?php

namespace Validator;

use Validator\Rules\BaseRule;

class RuleDTO
{
    private $rule;
    private $name;

    /**
     * @param string $ruleClass BaseRule class
     */
    public function __construct(string $ruleClass, string $name)
    {
        if(!is_subclass_of($ruleClass, BaseRule::class)) throw new \Exception("$ruleClass must be a descendant of the BaseRule class");
        $this->rule = $ruleClass;
        $this->name = strtolower($name);
    }

    /**
     * @return string ruleClass
     */
    public function getRule()
    {
        return $this->rule;
    }
    public function getName()
    {
        return $this->name;
    }
}