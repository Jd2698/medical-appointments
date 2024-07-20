<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    public function userId($user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    public function definition(): array
    {
        return [
            'medical_history' => $this->faker->sentence(3, true),
            'eps' => $this->faker->text(10),
        ];
    }
}
