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
        $departments = ['eee', 'cse', 'ece', 'bme', 'mse', 'me', 'iem', 'le', 'te', 'ese', 'ce', 'urp', 'becm', 'arch', 'math', 'chem', 'phy', 'hum'];
        $halls = ['amar ekushey hall', 'lalon shah hall', 'fajlul haq hall', 'khan jahan ali hall', 'rashid hall', 'rokeya hall'];
        $genders = ['male', 'female', 'other'];

        $photos = [
            'cLC8yFGU7cRu5OE4S83N3tddBKsukAoGmFbRfCUQ.png',
            'DS6jCjuOLVYZCyK93Uno7abMlcH9Ie57tJxy91wq.png',
            'fCohGLe6hKTyZ09xK3KHJti0C90XwN2tJ8MfDJhx.png',
            'Ic6ovBTVc3JoTgzKltDErIwaGajWGUoaAdQdhjCG.png',
            'IqacGiiRpEXOjRGpgz2tMB2pre3Uo7qqba2fUQ2H.png',
            'jDyUAfPpFPmNcPbGKDN2EzaLsfD2KiQOjK3IZfID.png',
            'lfxPPjuxnnHhBCXsj4NZYByDSOqHvMak5HpPf48k.png',
            'NlZvQG7VUwoXhy1RKrG16YnUl0eyFg0toVJJ3utp.png',
            'nnBDhFzHP2TWA9UgkuNQG7CoOqR49Gz7f3iln7sK.png',
            'oqQri8OkwlVmd5k9hqU2gz5xfiw1QnNcNAJilyXW.png',
            'Q5RFAuk7SJLQaOqFyITTMUxL54AEWQgI77b0KQDH.png',
            'qeUYCATeTV4cG9MJL2eAF75IsSotvISVXzsLQoUT.png',
            'rdnMJ5j27ZRPMOMdaNqGYvNDZlEI7JWrcJXf8t2e.png',
            'Zl2g1DLzi1qUahSTuizdpGq5gM6rHe3kqNQfjnGN.png',
            'zmSApvIbal0UTCv3K7qnZDctmpoDfCjM3fZESzyu.png'
        ];

        return [
            'student_id' => '2026' . fake()->unique()->numerify('###'),
            'department' => fake()->randomElement($departments),
            'batch' => fake()->randomElement(['2k20', '2k21', '2k22', '2k23', '2k24']),
            'hall' => fake()->randomElement($halls),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement($genders),
            'bio' => fake()->sentence(10),
            'profile_photo' => 'profile_photos/' . fake()->randomElement($photos),
        ];
    }
}
