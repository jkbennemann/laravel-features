<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Enums\PartyStatus;
use Jkbennemann\Features\Models\Party;

class PartyListCommand extends Command
{
    public $signature = 'party:list';

    public $description = 'List all parties';

    public function handle(): int
    {
       $parties = Party::all();

       $this->table(['ID', 'Name', 'Slug', 'Status', 'Description', 'Created'], $parties->map(function(Party $party
       ) {
           return [
               $party->id,
               $party->name,
               $party->slug,
               PartyStatus::getLabel($party->status),
               $party->description,
               $party->created_at->format('d.m.Y H:i:s (T)')
           ];
       }));

        return self::SUCCESS;
    }

    private function parseStatus(string $status = null): int
    {
        if (!is_numeric($status) && !empty($status)) {
            $this->error('Status has to be one of [0,1,2,3]');
        }

        return match ((int)$status) {
            1 => FeatureStatus::ACTIVE,
            2 => FeatureStatus::PLANNED,
            3 => FeatureStatus::ABANDONED,
            default => FeatureStatus::INACTIVE,
        };
    }
}
