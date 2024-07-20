<?php
namespace Validator\Rules;

abstract class BaseRule implements RuleInterface
{
    public function getRuleName()
    {
        $className = end(explode("\\", static::class));
        return mb_strtolower(mb_substr($className,0,-4));
    }
}