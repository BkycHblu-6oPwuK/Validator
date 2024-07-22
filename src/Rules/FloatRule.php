<?php
namespace Validator\Rules;

class FloatRule extends Rule
{
    public function validate($value) : bool
    {
        return is_float($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is float';
    }
}