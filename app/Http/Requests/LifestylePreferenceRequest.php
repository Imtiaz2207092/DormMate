<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LifestylePreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'sleep_schedule' => ['nullable', 'string', 'max:100'],
            'study_habit' => ['nullable', 'string', 'max:100'],
            'smoking' => ['nullable', 'string', 'max:50'],
            'cleanliness' => ['nullable', 'string', 'max:100'],
            'noise_tolerance' => ['nullable', 'string', 'max:100'],
            'hobbies' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
