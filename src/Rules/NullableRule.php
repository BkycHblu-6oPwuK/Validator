<?php
namespace Validator\Rules;

class NullableRule extends Rule
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