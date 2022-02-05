<?php

declare(strict_types=1);

namespace Jkbennemann\Features\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jkbennemann\Features\FeaturesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Jkbennemann\\Features\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FeaturesServiceProvider::class,
        ];
    }

    protected function setUpDatabase(): void
    {
        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_feature_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_party_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_feature_party_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_feature_user_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_party_user_table.php.stub';
        $migration->up();
    }
}
