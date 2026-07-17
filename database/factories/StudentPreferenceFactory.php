<?php

namespace Database\Factories;

use App\Models\StudentPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StudentPreference>
 */
class StudentPreferenceFactory extends Factory
{
    protected $model = StudentPreference::class;

    public function definition(): array
    {
        $sleepSchedules = ['Early Sleeper', 'Late Sleeper', 'Flexible'];
        $studyHabits = ['Silent', 'Group Study', 'Flexible'];
        $cleanliness = ['Low', 'Medium', 'High'];
        $noise = ['Low', 'Medium', 'High'];
        $temperatures = ['Cold', 'Moderate', 'Warm'];
        $music = ['Quiet', 'Soft Music', 'Loud Music'];
        $personality = ['Introvert', 'Ambivert', 'Extrovert'];

        return [
            'sleep_schedule' => fake()->randomElement($sleepSchedules),
            'wake_up_time' => fake()->time('H:i'),
            'study_habit' => fake()->randomElement($studyHabits),
            'cleanliness' => fake()->randomElement($cleanliness),
            'smoking' => fake()->boolean(30),
            'noise_tolerance' => fake()->randomElement($noise),
            'guests_frequency' => fake()->randomElement(['Never', 'Sometimes', 'Frequently']),
            'room_temperature' => fake()->randomElement($temperatures),
            'music_preference' => fake()->randomElement($music),
            'lights_preference' => fake()->randomElement(['Dark', 'Dim', 'Bright']),
            'introvert_extrovert' => fake()->randomElement($personality),
            'sleep_with_light' => fake()->boolean(50),
            'pets' => fake()->boolean(20),
            'hobbies' => fake()->words(3, true),
            'languages' => fake()->languageCode() . ', ' . fake()->languageCode(),
            'additional_notes' => fake()->sentence(),
        ];
    }
}
