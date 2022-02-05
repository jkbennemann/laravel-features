<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Models;

use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;
use Jkbennemann\Features\Models\Party;

it('can create a model', function () {
    $model = Feature::factory()->create();

    $this->assertEquals(0, $model->status);
    $this->assertModelExists($model);
});

it('can create a model with different attributes', function () {
    $model = Feature::factory()->create([
        'name' => 'test-feature',
        'description' => 'test-description',
        'status' => FeatureStatus::ACTIVE,
    ]);

    $this->assertEquals('test-feature', $model->name);
    $this->assertEquals('test-description', $model->description);
    $this->assertEquals(1, $model->status);
    $this->assertModelExists($model);
});

it('will activate a feature', function () {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE,
    ]);

    expect(Feature::active()->get())
        ->toHaveCount(count: 0);

    $feature->activate();

    expect(Feature::inactive()->get())
        ->toHaveCount(count: 0)
        ->and(Feature::active()->get())
        ->toHaveCount(count: 1);
});

it('will deactivate a feature', function () {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE,
    ]);

    expect(Feature::active()->get())
        ->toHaveCount(count: 1);

    $feature->deactivate();

    expect(Feature::inactive()->get())
        ->toHaveCount(count: 1)
        ->and(Feature::active()->get())
        ->toHaveCount(count: 0);
});

it('will return the parties relation', function () {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE,
    ]);
    $party = Party::factory()->create();

    expect($feature->parties()->get())
        ->toHaveCount(count: 0);

    $party->addFeature($feature);

    expect($feature->parties()->get())
        ->toHaveCount(count: 1);
});
