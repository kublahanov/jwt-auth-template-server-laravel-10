<?php

namespace App\Traits;

trait TagCreator
{
    public const ENTITY_SEPARATOR = '::';

    public static function getTagName(?string $methodName = null): string
    {
        return implode(self::ENTITY_SEPARATOR, [
            __CLASS__,
            $methodName ?: __FUNCTION__,
        ]);
    }
}
