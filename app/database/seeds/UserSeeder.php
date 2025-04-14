<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{       
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
                User::create([
                        'username' => "Safat",
                        'email' => "safat@gmail.com",
                        'password' => Hash::make('1234'), // Don't use fake passwords
                        'user_type' => 1,
                        'status' => 1,
                        'registration_number' => 'REG-0001',
                        'phone_number' => '01721000000',
                        'created_at' => Carbon::now('Asia/Dhaka'),
                        'updated_at' => Carbon::now('Asia/Dhaka')
                ]);
        }
        
}
