<?php
namespace Validator\Rules;

class MinRule extends BaseRule
{
    private $min;

    public function __construct($min)
    {
        $this->min = (int)$min;
    }

    public function validate($value) : bool
    {
        return is_string($value) && strlen($value) >= $this->min;
    }
    
    public function getMessage(): string
    {
        return "This field must be at least {$this->min} characters long.";
    }
}