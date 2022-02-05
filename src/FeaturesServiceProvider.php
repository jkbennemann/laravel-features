<?php

namespace Jkbennemann\Features;

use Illuminate\Support\Facades\Blade;
use Jkbennemann\Features\Commands\FeaturesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FeaturesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-features')
            ->hasConfigFile()
            ->hasMigrations(
                'create_feature_table',
                'create_party_table',
                'create_feature_party_table',
                'create_feature_user_table',
                'create_party_user_table',
            )
            ->hasCommand(FeaturesCommand::class);
    }

    public function bootingPackage()
    {
        Blade::directive('feature', static function ($feature) {
            return "<?php if (auth()->check() && auth()->user()->hasFeature($feature)): ?>";
        });
        Blade::directive('endfeature', static function ($feature) {
            return "<?php if (auth()->check() && auth()->user()->hasFeature($feature)): ?>";
        });

        Blade::directive('party', function ($featureGroup) {
            return "<?php if (auth()->check() && auth()->user()->inParty($featureGroup)): ?>";
        });
        Blade::directive('endparty', function () {
            return "<?php endif; ?>";
        });
    }
}
