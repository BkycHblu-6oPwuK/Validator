<?php
namespace Validator\Rules;

class InRule extends Rule
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate($value) : bool
    {
        return is_array($value) && in_array($this->value, $value);
    }
    
    public function getMessage(): string
    {
        return "Значение {$this->value} не найдено в переданном массиве";
    }
}