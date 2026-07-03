<?php

namespace Database\Seeders;

use App\Models\StudentPreference;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user for login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create 30 dummy students with profiles and preferences
        User::factory()
            ->count(30)
            ->has(StudentProfile::factory(), 'studentProfile')
            ->has(StudentPreference::factory(), 'studentPreference')
            ->create();
    }
}
