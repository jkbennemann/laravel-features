<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Models\Traits;

use Illuminate\Support\Facades\Hash;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;
use Jkbennemann\Features\Models\Party;
use Jkbennemann\Features\Tests\Stubs\User;

it('can assign a feature to a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create();

    $user->giveFeature($feature->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();
});

it('can assign multiple feature to a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create();
    $feature2 = Feature::factory()->create();
    $featureNotSet = Feature::factory()->create();

    $user->giveFeature($feature->slug, $feature2->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();

    expect($user)
        ->hasFeature($feature2->slug, false)
        ->toBeTrue();

    expect($user)
        ->hasFeature($featureNotSet->slug, false)
        ->toBeFalse();
});

it('can remove a feature from a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create();
    $user->giveFeature($feature->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();

    $user->removeFeature($feature->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeFalse();
});

it('can remove multiple features from a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create();
    $feature2 = Feature::factory()->create();

    $user->giveFeature($feature->slug);
    $user->giveFeature($feature2->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();
    expect($user)
        ->hasFeature($feature2->slug, false)
        ->toBeTrue();

    $user->removeFeature($feature->slug);
    $user->removeFeature($feature2->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeFalse();

    expect($user)
        ->hasFeature($feature2->slug, false)
        ->toBeFalse();
});

it('can remove specific features from a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create();
    $feature2 = Feature::factory()->create();

    $user->giveFeature($feature->slug);
    $user->giveFeature($feature2->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();
    expect($user)
        ->hasFeature($feature2->slug, false)
        ->toBeTrue();

    $user->removeFeature($feature->slug);

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeFalse();

    expect($user)
        ->hasFeature($feature2->slug, false)
        ->toBeTrue();
});

it('can check for a direct and active feature', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create([
        'status' => FeatureStatus::ACTIVE
    ]);

    $user->giveFeature($feature->slug);

    expect($user)
        ->hasFeatureDirect($feature->slug)
        ->toBeTrue();
});

it('can check for a direct and inactive feature', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE
    ]);

    $user->giveFeature($feature->slug);

    expect($user)
        ->hasFeatureDirect($feature->slug)
        ->toBeFalse();

    $feature->activate();

    expect($user)
        ->hasFeatureDirect($feature->slug)
        ->toBeTrue();
});

//join party
it('can join a user to a party', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();

    expect($user->parties)
        ->toHaveCount(0);

    $user->addToParty($party->slug);

    expect($user->parties)
        ->toHaveCount(1);
});

it('can join a user to multiple parties', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();
    $party2 = Party::factory()->create();

    expect($user->parties)
        ->toHaveCount(0);

    $user->addToParty($party->slug);
    $user->addToParty($party2->slug);

    expect($user->parties)
        ->toHaveCount(2);
});

//leave party
it('can remove a user from a party', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();
    $user->addToParty($party->slug);

    expect($user->parties)
        ->toHaveCount(1);

    $user->leaveParty($party->slug);

    expect($user->parties)
        ->toHaveCount(0);
});

it('can remove a user from multiple parties', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();
    $party2 = Party::factory()->create();
    $user->addToParty($party->slug);
    $user->addToParty($party2->slug);

    expect($user->parties)
        ->toHaveCount(2);

    $user->leaveParty($party->slug);
    $user->leaveParty($party2->slug);

    expect($user->parties)
        ->toHaveCount(0);
});

it('can remove a user from specific parties', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();
    $party2 = Party::factory()->create();
    $user->addToParty($party->slug);
    $user->addToParty($party2->slug);

    expect($user->parties)
        ->toHaveCount(2);

    $user->leaveParty($party->slug);

    expect($user->parties)
        ->toHaveCount(1);

    expect($user->parties->first()->slug)
        ->toBe($party2->slug);
});

//belongs to party
it('can check if a user belongs to a party', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();

    expect($user)
        ->belongsToParty($party->slug)
        ->toBeFalse();

    $user->joinParty($party->slug);

    expect($user)
        ->belongsToParty($party->slug)
        ->toBeTrue();
});

it('can check if a user belongs to at least one party of', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $party = Party::factory()->create();
    $party2 = Party::factory()->create();

    $user->joinParty($party2->slug);

    expect($user)
        ->belongsToParty($party->slug, $party2->slug)
        ->toBeTrue();
});

//user has feature through party
it('can check if a user has features through a party', function () {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $feature = Feature::factory()->create();
    $party = Party::factory()->create();

    $party->addFeature($feature);
    $user->joinParty($party->slug);

    expect($user)
        ->partyHasFeature($feature->slug, false)
        ->toBeTrue();

    expect($user)
        ->hasFeature($feature->slug, false)
        ->toBeTrue();
});

it('can check if a user has only active features through a party', function () {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
    $feature = Feature::factory()->create([
        'status' => FeatureStatus::INACTIVE
    ]);
    $party = Party::factory()->create();

    $party->addFeature($feature);
    $user->joinParty($party->slug);

    expect($user)
        ->partyHasFeature($feature->slug)
        ->toBeFalse();

    $feature->activate();

    expect($user)
        ->partyHasFeature($feature->slug)
        ->toBeTrue();
});

//all features for a user
