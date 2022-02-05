<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Builders\Traits;

trait HasBySlug
{
    public function slug(string $slug): self
    {
        $this->where('slug', $slug);

        return $this;
    }
}
