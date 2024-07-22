<?php
namespace Validator\Rules;

class MaxRule extends Rule
{
    private $max;

    public function __construct($max)
    {
        $this->max = (int)$max;
    }

    public function validate($value) : bool
    {
        return is_string($value) && strlen($value) <= $this->max;
    }
    
    public function getMessage(): string
    {
        return "This field may not be greater than {$this->max} characters.";
    }
}