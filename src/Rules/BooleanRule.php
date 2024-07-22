<?php
namespace Validator\Rules;

class BooleanRule extends Rule
{
    public function validate($value) : bool
    {
        return is_bool($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is float';
    }
}