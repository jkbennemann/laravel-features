<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Enums;

use ReflectionClass;

class PartyStatus implements StatusContract
{
    public const INACTIVE = 0;
    public const ACTIVE = 1;

    public static function getLabel($value): int|string
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = array_flip($class->getConstants());

        return $constants[$value];
    }
}
