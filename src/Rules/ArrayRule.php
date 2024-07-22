<?php
namespace Validator\Rules;

class ArrayRule extends Rule
{
    public function validate($value) : bool
    {
        return is_array($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is array';
    }
}