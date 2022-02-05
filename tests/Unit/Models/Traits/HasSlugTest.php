<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Models\Traits;

use Jkbennemann\Features\Models\Feature;

it('assigns a feature to a user', function (): void {
    $feature = Feature::factory()->create([
        'name' => 'Test Feature',
    ]);

    expect($feature->slug)
        ->toBe('test-feature');
});
