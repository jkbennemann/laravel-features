<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests\Unit\Commands;

use Illuminate\Support\Facades\Artisan;
use Jkbennemann\Features\Models\Enums\PartyStatus;
use Jkbennemann\Features\Models\Party;

it('it can create a party with the command', function () {
    expect(Artisan::call('party:add', [
        'name' => 'New Party',
        'description' => 'Test Party',
    ]))->toBe(0);

    $party = Party::first();

    expect($party->status)
        ->toBe(PartyStatus::INACTIVE);
});
