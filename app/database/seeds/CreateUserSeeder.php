<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CreateUserSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove namespace imports (not needed in Laravel 4.2)
        $faker = Faker\Factory::create();
        
        // Use more realistic test data
        $statuses = [0, 1]; // Inactive/Active
        $userTypes = [2, 3]; // Admin, Instructor, Student
        
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Don't use fake passwords
                'user_type' => $faker->randomElement($userTypes),
                'status' => $faker->randomElement($statuses),
                'registration_number' => 'REG-'.$faker->unique()->numberBetween(10000, 99999),
                'phone_number' => '01'.$faker->unique()->numberBetween(100000000, 999999999),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}