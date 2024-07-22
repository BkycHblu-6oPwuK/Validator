<?php
namespace Validator\Rules;

class RequiredRule extends Rule
{
    public function validate($value) : bool
    {
        return !empty($value);
    }

    public function getMessage(): string
    {
        return 'This field is required';
    }
}