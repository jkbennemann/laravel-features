<?php

namespace Jkbennemann\Features\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Jkbennemann\Features\Models\Builders\Traits\HasByName;
use Jkbennemann\Features\Models\Builders\Traits\HasBySlug;
use Jkbennemann\Features\Models\Builders\Traits\HasByStatus;

class PartyBuilder extends Builder
{
    use HasByStatus;
    use HasBySlug;
    use HasByName;
}
