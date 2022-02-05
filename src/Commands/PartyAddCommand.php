<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Jkbennemann\Features\Models\Enums\PartyStatus;
use Jkbennemann\Features\Models\Party;

class PartyAddCommand extends Command
{
    public $signature = 'party:add {name} {description?} {--status}';

    public $description = 'Creates a new party';

    public function handle(): int
    {
        $name = $this->argument('name');
        $description = $this->argument('description');
        $status = $this->option('status');

        try {
            /** @var Party $party */
            $party = Party::create([
                'name' => $name,
                'description' => $description ?: null,
                'status' => $this->parseStatus($status),
            ]);
        } catch (QueryException $e) {
            $this->error('The party already exists!');

            return self::FAILURE;
        }

        $this->info(sprintf("ID: %s | Slug: %s | Status: %s | Description: %s", $party->id, $party->slug, $party->status, $party->description));
        $this->comment('Party created!');

        return self::SUCCESS;
    }

    private function parseStatus(string $status): int
    {
        if (! is_numeric($status) && ! empty($status)) {
            $this->error('Status has to be one of [0,1]');
        }

        return match ((int)$status) {
            1 => PartyStatus::ACTIVE,
            default => PartyStatus::INACTIVE,
        };
    }
}
