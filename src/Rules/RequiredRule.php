<?php
namespace Validator\Rules;

class RequiredRule extends BaseRule
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