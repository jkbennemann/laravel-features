<?php

namespace Jkbennemann\Features\Commands;

use Illuminate\Console\Command;
use Jkbennemann\Features\Models\Feature;

class FeatureDeactivateCommand extends Command
{
    public $signature = 'features:deactivate {identifier}';

    public $description = 'Dectivates a feature';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        if (is_numeric($identifier)) {
            $feature = Feature::find($identifier);
        } else {
            $feature = Feature::where('slug', $identifier)->first();
        }

        if (!$feature) {
            $this->error('Not feature found');

            return self::FAILURE;
        }

        $feature->deactivate();

        $this->comment('All done');

        return self::SUCCESS;
    }
}
