@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">My Lifestyle Preferences</h3>
            <p class="text-muted mb-0">Review and update your profile for better roommate matching.</p>
        </div>
        <a href="{{ route('preferences.edit') }}" class="btn btn-primary">Edit Preferences</a>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Sleep Schedule:</strong> {{ $preference->sleep_schedule }}</div>
                        <div class="col-md-6"><strong>Wake Up Time:</strong> {{ $preference->wake_up_time }}</div>
                        <div class="col-md-6"><strong>Study Habit:</strong> {{ $preference->study_habit }}</div>
                        <div class="col-md-6"><strong>Cleanliness:</strong> {{ $preference->cleanliness }}</div>
                        <div class="col-md-6"><strong>Smoking:</strong> {{ $preference->smoking ? 'Yes' : 'No' }}</div>
                        <div class="col-md-6"><strong>Noise Tolerance:</strong> {{ $preference->noise_tolerance }}</div>
                        <div class="col-md-6"><strong>Guests Frequency:</strong> {{ $preference->guests_frequency }}</div>
                        <div class="col-md-6"><strong>Room Temperature:</strong> {{ $preference->room_temperature }}</div>
                        <div class="col-md-6"><strong>Music Preference:</strong> {{ $preference->music_preference }}</div>
                        <div class="col-md-6"><strong>Lights Preference:</strong> {{ $preference->lights_preference }}</div>
                        <div class="col-md-6"><strong>Personality:</strong> {{ $preference->introvert_extrovert }}</div>
                        <div class="col-md-6"><strong>Sleep with Light:</strong> {{ $preference->sleep_with_light ? 'Yes' : 'No' }}</div>
                        <div class="col-md-6"><strong>Pets:</strong> {{ $preference->pets ? 'Yes' : 'No' }}</div>
                        <div class="col-md-12"><strong>Hobbies:</strong> {{ $preference->hobbies }}</div>
                        <div class="col-md-12"><strong>Languages:</strong> {{ $preference->languages }}</div>
                        <div class="col-md-12"><strong>Additional Notes:</strong> {{ $preference->additional_notes ?? 'None' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
