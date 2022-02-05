<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Models\Builders\Traits;

use Jkbennemann\Features\Models\Enums\StatusContract;

trait HasByStatus
{
    public function status(StatusContract|int $status): self
    {
        $this->where('status', $status);

        return $this;
    }

    public function inactive(): self
    {
        $this->where('status', 0);

        return $this;
    }

    public function active(): self
    {
        $this->where('status', 1);

        return $this;
    }
}
