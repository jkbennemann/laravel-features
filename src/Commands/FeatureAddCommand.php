<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Jkbennemann\Features\Models\Enums\FeatureStatus;
use Jkbennemann\Features\Models\Feature;

class FeatureAddCommand extends Command
{
    public $signature = 'feature:add {name} {description?} {--status}';

    public $description = 'Creates a new feature';

    public function handle(): int
    {
        $name = $this->argument('name');
        $description = $this->argument('description');
        $status = $this->option('status');

        try {
            /** @var Feature $feature */
            $feature = Feature::create([
                'name' => $name,
                'description' => $description ?: null,
                'status' => $this->parseStatus($status),
            ]);
        } catch (QueryException $e) {
            $this->error('The feature already exists!');

            return self::FAILURE;
        }

        $this->info(sprintf("ID: %s | Slug: %s | Status: %s | Description: %s", $feature->id, $feature->slug, $feature->status, $feature->description));
        $this->comment('Feature created!');

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
