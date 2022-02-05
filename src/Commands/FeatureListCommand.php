<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

class FeatureListCommand extends Command
{
    public $signature = 'feature:list';

    public $description = 'List all features';

    public function handle(): int
    {
        $features = Feature::all();

        $this->table(['ID', 'Name', 'Slug', 'Status', 'Description', 'Created'], $features->map(function (Feature $feature) {
            return [
               $feature->id,
               $feature->name,
               $feature->slug,
               FeatureStatus::getLabel($feature->status),
               $feature->description,
               $feature->created_at->format('d.m.Y H:i:s (T)'),
           ];
        }));

        return self::SUCCESS;
    }

    private function parseStatus(string $status = null): int
    {
        if (! is_numeric($status) && ! empty($status)) {
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
