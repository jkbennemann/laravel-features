<?php

namespace Jkbennemann\Features;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Jkbennemann\Features\Commands\FeatureActivateCommand;
use Jkbennemann\Features\Commands\FeatureAddCommand;
use Jkbennemann\Features\Commands\FeatureDeactivateCommand;
use Jkbennemann\Features\Commands\FeatureListCommand;
use Jkbennemann\Features\Commands\PartyAddCommand;
use Jkbennemann\Features\Commands\PartyListCommand;
use Jkbennemann\Features\Models\Feature;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FeaturesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-features')
            ->hasConfigFile()
            ->hasCommands(
                FeatureActivateCommand::class,
                FeatureDeactivateCommand::class,
                FeatureAddCommand::class,
                PartyAddCommand::class,
                FeatureListCommand::class,
                PartyListCommand::class,
            )
            ->hasMigrations(
                'create_feature_table',
                'create_party_table',
                'create_feature_party_table',
                'create_feature_user_table',
                'create_party_user_table',
            )
            ->hasCommand(FeatureActivateCommand::class);
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

        Feature::all()->each(function(Feature $feature) {
            Gate::define($feature->slug, function ($user) use ($feature) {
                if (method_exists($user, 'hasFeature')) {
                    return $user->hasFeature($feature, true);
                }

                return false;
            });
        });
    }
}
