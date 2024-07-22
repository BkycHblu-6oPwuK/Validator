<?php
namespace Validator\Rules;

abstract class Rule implements RuleInterface
{
    public static function getRuleName() : string
    {
        $className = end(explode("\\", static::class));
        return mb_strtolower(mb_substr($className,0,-4));
    }
}