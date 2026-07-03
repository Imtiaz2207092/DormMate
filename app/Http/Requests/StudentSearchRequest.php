<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:50'],
            'hall' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'sleep_schedule' => ['nullable', 'string', 'max:100'],
            'study_habit' => ['nullable', 'string', 'max:100'],
            'cleanliness' => ['nullable', 'string', 'max:100'],
            'smoking' => ['nullable', 'in:yes,no,occasional'],
            'noise_tolerance' => ['nullable', 'string', 'max:100'],
            'room_temperature' => ['nullable', 'in:hot,cold'],
            'music_preference' => ['nullable', 'string', 'max:100'],
            'introvert_extrovert' => ['nullable', 'in:introvert,ambivert,extrovert'],
            'sort_by' => ['nullable', 'in:compatibility_desc,compatibility_asc,newest,oldest,name_asc,name_desc'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
