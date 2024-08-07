<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() . $this->faker->name(),
            'documento_identidad' => $this->faker->randomNumber(9, true),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => '3' . $this->faker->randomNumber(9, true),
            'birthdate' => $this->faker->date(),
            'gender' =>
            $this->faker->randomElement([1, 2]),
            'address' => $this->faker->streetAddress(),
            'password' => bcrypt('admin123'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $random = rand(0, 1);
            if ($random == 1) {
                $user->assignRole(['doctor', 'patient']);
                $doctor = Doctor::factory(1)->userId($user)->create();
            } else {
                $user->assignRole('patient');
                $patient = Patient::factory(1)->userId($user)->create();
            }


            // echo $patient;
            // Appointment::factory(1)->patientId($patient[0])->doctorId($doctor[0])->create();
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (!Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
