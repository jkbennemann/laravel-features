<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Commands;

use Illuminate\Support\Facades\Artisan;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

it('it can activate a feature with the command', function () {
    /** @var Feature $feature */
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE,
    ]);

    expect(Artisan::call('feature:activate', ['identifier' => $feature->getKey()]))
        ->toBe(0);

    $feature = $feature->fresh();
    expect($feature->isActive())->toBeTrue();
    expect($feature->status)
        ->toBe(FeatureStatus::ACTIVE);
});

it('it can activate a feature by slug', function () {
    /** @var Feature $feature */
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE,
    ]);

    expect(Artisan::call('feature:activate', ['identifier' => $feature->slug]))
        ->toBe(0);

    $feature = $feature->fresh();
    expect($feature->isActive())->toBeTrue();
    expect($feature->status)
        ->toBe(FeatureStatus::ACTIVE);
});
