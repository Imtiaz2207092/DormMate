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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
            'user_type' => 'admin',
        ]);

        // Create 30 dummy students with profiles and preferences
        User::factory()
            ->count(30)
            ->has(StudentProfile::factory(), 'studentProfile')
            ->has(StudentPreference::factory(), 'studentPreference')
            ->create();
    }
}
