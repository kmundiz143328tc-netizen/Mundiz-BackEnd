<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@school.edu.ph'],
            [
                'name'     => 'Admin User',
                'email'    => 'admin@school.edu.ph',
                'password' => Hash::make('password123'),
            ]
        );

        // Run seeders in order
        $this->call([
            CourseSeeder::class,
            StudentSeeder::class,
            SchoolDaySeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed! Default login: admin@school.edu.ph / password123');
    }
}