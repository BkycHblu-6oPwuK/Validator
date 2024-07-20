<?php
namespace Validator\Rules;

class NullableRule extends BaseRule
{
    public function validate($value) : bool
    {
        return empty($value);
    }

    public function getMessage(): string
    {
        return '';
    }
}