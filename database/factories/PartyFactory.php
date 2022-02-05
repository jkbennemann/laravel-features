<?php

namespace Jkbennemann\Features\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jkbennemann\Features\Models\Party;

class PartyFactory extends Factory
{
    protected $model = Party::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(),
        ];
    }
}
