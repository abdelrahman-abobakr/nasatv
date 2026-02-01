<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@nasatv.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create employee user
        User::create([
            'name' => 'Employee User',
            'email' => 'employee@nasatv.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        // Create sample plans
        Plan::create([
            'name' => 'Basic Plan',
            'price' => 29.99,
            'duration' => '1 Month',
            'description' => 'Basic subscription plan with essential features',
            'status' => 'active',
        ]);

        Plan::create([
            'name' => 'Premium Plan',
            'price' => 79.99,
            'duration' => '3 Months',
            'description' => 'Premium subscription plan with advanced features',
            'status' => 'active',
        ]);

        Plan::create([
            'name' => 'Annual Plan',
            'price' => 299.99,
            'duration' => '1 Year',
            'description' => 'Annual subscription plan with all features',
            'status' => 'active',
        ]);
    }
}
