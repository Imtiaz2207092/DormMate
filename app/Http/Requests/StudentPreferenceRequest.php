<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'sleep_schedule' => ['required', 'string', 'in:Early Sleeper,Late Sleeper,Flexible'],
            'wake_up_time' => ['required', 'date_format:H:i'],
            'study_habit' => ['required', 'string', 'in:Silent,Group Study,Flexible'],
            'cleanliness' => ['required', 'string', 'in:Low,Medium,High'],
            'smoking' => ['required', 'boolean'],
            'noise_tolerance' => ['required', 'string', 'in:Low,Medium,High'],
            'guests_frequency' => ['required', 'string', 'in:Never,Sometimes,Frequently'],
            'room_temperature' => ['required', 'string', 'in:Cold,Moderate,Warm'],
            'music_preference' => ['required', 'string', 'in:Quiet,Soft Music,Loud Music'],
            'lights_preference' => ['required', 'string', 'in:Dark,Dim,Bright'],
            'introvert_extrovert' => ['required', 'string', 'in:Introvert,Ambivert,Extrovert'],
            'sleep_with_light' => ['required', 'boolean'],
            'pets' => ['required', 'boolean'],
            'hobbies' => ['required', 'string', 'max:2000'],
            'languages' => ['required', 'string', 'max:2000'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'smoking' => $this->boolean('smoking'),
            'sleep_with_light' => $this->boolean('sleep_with_light'),
            'pets' => $this->boolean('pets'),
        ]);
    }
}
