<?php
namespace Validator\Rules;

class IntegerRule extends BaseRule
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