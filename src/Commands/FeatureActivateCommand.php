<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Jkbennemann\Features\Models\Feature;

class FeatureActivateCommand extends Command
{
    public $signature = 'feature:activate {identifier}';

    public $description = 'Activates a feature';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $feature = Feature::find($identifier);

        if (!$feature) {
            $feature = Feature::where('slug', $identifier)->first();
        }

        if (! $feature) {
            $this->error('Not feature found');

            return self::FAILURE;
        }

        /** @var Feature $feature */
        $feature->activate();

        $this->comment('All done');

        return self::SUCCESS;
    }
}
