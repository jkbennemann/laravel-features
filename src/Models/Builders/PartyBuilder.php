<?php

namespace Jkbennemann\Features\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Jkbennemann\Features\Models\Builders\Traits\HasByStatus;

class PartyBuilder extends Builder
{
    use HasByStatus;

    public function slugOrName(string $value): self
    {
        $this->where('slug', $value)->orWhere('name', $value);

        return $this;
    }
}
