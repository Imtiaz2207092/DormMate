<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentProfileRequest;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $profile = $request->user()->studentProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        return view('profiles.show', ['profile' => $profile]);
    }

    public function create(Request $request)
    {
        $profile = $request->user()->studentProfile;
        if ($profile) {
            return redirect()->route('profile.edit');
        }

        return view('profiles.form', ['profile' => new StudentProfile()]);
    }

    public function store(StudentProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $profile = new StudentProfile();
        $profile->user_id = $request->user()->id;
        $profile->student_id = $data['student_id'];
        $profile->department = $data['department'];
        $profile->batch = $data['batch'];
        $profile->hall = $data['hall'];
        $profile->phone = $data['phone'];
        $profile->gender = $data['gender'];
        $profile->bio = $data['bio'] ?? null;

        if ($request->hasFile('profile_photo')) {
            $profile->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->save();

        return redirect()->route('profile.show')->with('status', 'Profile saved successfully.');
    }

    public function edit(Request $request)
    {
        $profile = $request->user()->studentProfile;
        if (! $profile) {
            return redirect()->route('profile.create');
        }

        return view('profiles.form', ['profile' => $profile]);
    }

    public function update(StudentProfileRequest $request): RedirectResponse
    {
        $profile = $request->user()->studentProfile;
        if (! $profile) {
            return redirect()->route('profile.create');
        }

        $data = $request->validated();

        $profile->student_id = $data['student_id'];
        $profile->department = $data['department'];
        $profile->batch = $data['batch'];
        $profile->hall = $data['hall'];
        $profile->phone = $data['phone'];
        $profile->gender = $data['gender'];
        $profile->bio = $data['bio'] ?? null;

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $profile->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $profile->save();

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully.');
    }
}
