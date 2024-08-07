<?php
namespace Validator\Rules;

class StringRule extends Rule
{
    public function validate($value) : bool
    {
        return is_string($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is String';
    }
}