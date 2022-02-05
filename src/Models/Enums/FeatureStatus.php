<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Enums;

use ReflectionClass;

class FeatureStatus implements StatusContract
{
    public const INACTIVE = 0;
    public const ACTIVE = 1;
    public const PLANNED = 2;
    public const ABANDONED = 3;

    public static function getLabel($value): int|string
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = array_flip($class->getConstants());

        return $constants[$value];
    }
}
