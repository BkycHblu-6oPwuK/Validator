<?php
namespace Validator\Rules;

interface RuleInterface
{
    public function validate($value) : bool;
    public function getMessage(): string;
}