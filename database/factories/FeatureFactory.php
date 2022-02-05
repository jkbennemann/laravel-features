<?php

namespace Jkbennemann\Features\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jkbennemann\Features\Models\Feature;

class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(),
        ];
    }
}
