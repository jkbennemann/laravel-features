<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests;

use Illuminate\Support\Facades\Hash;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;
use Jkbennemann\Features\Tests\Stubs\User;

it('makes available gates for each feature', function() {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE
    ]);

    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password'),
    ]);
    $user->giveFeature($feature);

    expect($user->can($feature->slug))->toBeTrue();
});

it('makes gate unavailable for each feature if not active', function() {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE
    ]);

    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password'),
    ]);
    $user->giveFeature($feature);

    expect($user->can($feature->slug))->toBeFalse();
});

it('can enable gate for each feature after getting active', function() {
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE
    ]);

    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password'),
    ]);
    $user->giveFeature($feature);

    expect($user->can($feature->slug))->toBeFalse();

    $feature->activate();

    expect($user->can($feature->slug))->toBeTrue();
});
