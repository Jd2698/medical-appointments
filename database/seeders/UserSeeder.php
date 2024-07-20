<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        User::create([
            'name' => 'admin',
            'documento_identidad' => '1004233282',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'phone' => '3186369798',
            'birthdate' => $faker->date(),
            'gender' =>
            $faker->randomElement([1, 2]),
            'address' => "calle 2 #81-01",
            'password' => bcrypt('admin123'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ])->assignRole('admin');
    }
}
