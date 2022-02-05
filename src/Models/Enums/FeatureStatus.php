<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Enums;

class FeatureStatus implements StatusContract
{
    public const INACTIVE = 0;
    public const ACTIVE = 1;
    public const PLANNED = 2;
    public const ABANDONED = 3;
}
