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
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:50'],
            'hall' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'sleep_schedule' => ['nullable', 'string', 'max:100'],
            'study_habit' => ['nullable', 'string', 'max:100'],
            'smoking' => ['nullable', 'string', 'max:50'],
            'cleanliness' => ['nullable', 'string', 'max:100'],
            'noise_tolerance' => ['nullable', 'string', 'max:100'],
            'q' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
