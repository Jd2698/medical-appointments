<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{

    public function patientId($patient)
    {
        return $this->state([
            'patient_id' => $patient->id,
        ]);
    }

    public function doctorId($doctor)
    {
        return $this->state([
            'doctor_id' => $doctor->id,
        ]);
    }

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'comment' => $this->faker->paragraph(4),
            'status' => $this->faker->randomElement([1, 2, 3, 4]),
        ];
    }
}
