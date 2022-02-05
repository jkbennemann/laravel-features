<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Commands;

use Illuminate\Support\Facades\Artisan;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

it('it can create a feature with the command', function () {
    expect(Artisan::call('features:add', [
        'name' => 'New feature',
        'description' => 'Test Feature',
    ]))->toBe(0);

    $feature = Feature::first();

    expect($feature->isActive())->toBeFalse();
    expect($feature->status)
        ->toBe(FeatureStatus::INACTIVE);
});
