<?php
namespace Validator\Rules;

class IntegerRule extends Rule
{
    public function validate($value) : bool
    {
        return is_int($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is integer';
    }
}