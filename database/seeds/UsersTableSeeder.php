<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if user exists to avoid duplicates
        if (!User::where('email', 'aldmic@example.com')->exists()) {
            User::create([
                'name' => 'aldmic',
                'email' => 'aldmic@example.com',
                'password' => Hash::make('123abc123'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('User aldmic created successfully.');
        } else {
            $this->command->info('User aldmic already exists.');
        }
    }
}
