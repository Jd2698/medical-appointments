<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\specialty>
 */
class SpecialtyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
