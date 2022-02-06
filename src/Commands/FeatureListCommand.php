<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

class FeatureListCommand extends Command
{
    public $signature = 'feature:list';

    public $description = 'List all features';

    public function handle(): int
    {
        /** @var Collection $features */
        $features = Feature::with('parties')->get();

        $this->table(
            ['ID', 'Name', 'Slug', 'Status', 'Users', 'Description', 'Created'],
            $features->map(function (Feature $feature) {
                return [
                   $feature->id,
                   $feature->name,
                   $feature->slug,
                   FeatureStatus::getLabel($feature->status),
                    $feature->users()->count(),
                   $feature->description,
                   $feature->created_at->format('d.m.Y H:i:s (T)'),
               ];
            })
        );

        return self::SUCCESS;
    }
}
