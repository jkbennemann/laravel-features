<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Builders\Traits;

trait HasByName
{
    public function name(string $name): self
    {
        $this->where('name', $name);

        return $this;
    }
}
