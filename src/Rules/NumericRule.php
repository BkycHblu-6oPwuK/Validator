<?php
namespace Validator\Rules;

class NumericRule extends Rule
{
    public function validate($value) : bool
    {
        return is_numeric($value);
    }
    
    public function getMessage(): string
    {
        return 'This field is numeric';
    }
}