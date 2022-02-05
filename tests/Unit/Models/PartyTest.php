<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Models;

use Jkbennemann\Features\Models\Enums\PartyStatus;
use Jkbennemann\Features\Models\Feature;
use Jkbennemann\Features\Models\Party;

it('can create a model', function () {
    $model = Party::factory()->create();

    $this->assertEquals(0, $model->status);
    $this->assertModelExists($model);
});

it('can create a model with different attributes', function () {
    $model = Party::factory()->create([
        'name' => 'test-party',
        'description' => 'test-description',
        'status' => PartyStatus::ACTIVE,
    ]);

    $this->assertEquals('test-party', $model->name);
    $this->assertEquals('test-description', $model->description);
    $this->assertEquals(1, $model->status);
    $this->assertModelExists($model);
});

it('can add a feature', function () {
    $party = Party::factory()->create([
        'status' => PartyStatus::INACTIVE
    ]);
    $feature = Feature::factory()->create();

    expect($party->features()->get())
        ->toHaveCount(count: 0);

    $party->addFeature($feature);

    expect($party->features()->get())
        ->toHaveCount(count: 1);
});

it('can remove a feature', function () {
    $party = Party::factory()->create([
        'status' => PartyStatus::INACTIVE
    ]);
    $feature = Feature::factory()->create();
    $party->addFeature($feature);

    expect($party->features()->get())
        ->toHaveCount(count: 1);

    $party->removeFeature($feature);

    expect($party->features()->get())
        ->toHaveCount(count: 0);
});

it('will return the parties relation', function () {
    $party = Party::factory()->create([
        'status' => PartyStatus::ACTIVE
    ]);
    $feature = Feature::factory()->create();
    $party->addFeature($feature);

    expect($party->features()->get())
        ->toHaveCount(count: 1);
});

it('can check for a given feature', function () {
    $party = Party::factory()->create([
        'status' => PartyStatus::ACTIVE
    ]);
    $feature = Feature::factory()->create();
    $party->addFeature($feature);

    expect($party->hasFeature($feature))
        ->toBeTrue();
});

it('can check for a given feature by slug', function () {
    $party = Party::factory()->create([
        'status' => PartyStatus::ACTIVE
    ]);
    $feature = Feature::factory()->create([
        'name' => 'slug test'
    ]);

    $party->addFeature($feature);

    expect($party->hasFeature('slug-test'))
        ->toBeTrue();
});
