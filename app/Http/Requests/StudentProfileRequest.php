<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $profileId = auth()->user()->studentProfile?->id;

        return [
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('student_profiles', 'student_id')->ignore($profileId),
            ],
            'department' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'max:100'],
            'hall' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female,other'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
