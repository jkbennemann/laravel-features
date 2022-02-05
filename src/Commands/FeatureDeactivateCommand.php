<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Jkbennemann\Features\Models\Feature;

class FeatureDeactivateCommand extends Command
{
    public $signature = 'feature:deactivate {identifier}';

    public $description = 'Dectivates a feature';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $feature = Feature::find($identifier);

        if (! $feature) {
            $feature = Feature::where('slug', $identifier)->first();
        }

        if (! $feature) {
            $this->error('Not feature found');

            return self::FAILURE;
        }

        /** @var Feature $feature */
        $feature->deactivate();

        $this->comment('All done');

        return self::SUCCESS;
    }
}
