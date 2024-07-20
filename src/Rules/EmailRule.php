<?php
namespace Validator\Rules;

class EmailRule extends BaseRule
{
    public function validate($value) : bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public function getMessage(): string
    {
        return 'This field must be a valid email address';
    }
}