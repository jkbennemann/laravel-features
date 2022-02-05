<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Commands;

use Illuminate\Support\Facades\Artisan;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

it('it can deactivate a feature with the command', function () {
    /** @var Feature $feature */
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE,
    ]);

    expect(Artisan::call('features:deactivate', ['identifier' => 1]))
        ->toBe(0);

    $feature = $feature->fresh();
    expect($feature->isActive())->toBeFalse();
    expect($feature->status)
        ->toBe(FeatureStatus::INACTIVE);
});

it('it can deactivate a feature by slug', function () {
    /** @var Feature $feature */
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE,
    ]);

    expect(Artisan::call('features:deactivate', ['identifier' => $feature->slug]))
        ->toBe(0);

    $feature = $feature->fresh();
    expect($feature->isActive())->toBeFalse();
    expect($feature->status)
        ->toBe(FeatureStatus::INACTIVE);
});
