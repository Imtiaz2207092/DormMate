<?php

namespace Database\Factories;

use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    protected $model = StudentProfile::class;

    public function definition(): array
    {
        $departments = ['cse', 'eee', 'me', 'civil', 'bme', 'mte', 'mse', 'becm', 'arch'];
        $halls = ['amar ekushey hall', 'lalon shah hall', 'fajlul haq hall', 'khan jahan ali hall', 'rashid hall', 'rokeya hall'];
        $genders = ['male', 'female', 'other'];

        return [
            'student_id' => '2026' . fake()->unique()->numerify('###'),
            'department' => fake()->randomElement($departments),
            'batch' => fake()->randomElement(['2k20', '2k21', '2k22', '2k23', '2k24']),
            'hall' => fake()->randomElement($halls),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement($genders),
            'bio' => fake()->sentence(10),
            'profile_photo' => null,
        ];
    }
}
