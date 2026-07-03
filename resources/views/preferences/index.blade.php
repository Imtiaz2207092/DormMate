@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Lifestyle Preferences</h3>
            <p class="text-muted mb-0">Manage your roommate preference profile for better matches.</p>
        </div>
        <a href="{{ $preference ? route('preferences.edit') : route('preferences.create') }}" class="btn btn-primary">
            {{ $preference ? 'Edit Preferences' : 'Create Preferences' }}
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($preference)
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <strong>Sleep Schedule:</strong> {{ $preference->sleep_schedule }}
                </div>
                <div class="mb-3">
                    <strong>Wake Up Time:</strong> {{ $preference->wake_up_time }}
                </div>
                <div class="mb-3">
                    <strong>Study Habit:</strong> {{ $preference->study_habit }}
                </div>
                <div class="mb-3">
                    <strong>Cleanliness:</strong> {{ $preference->cleanliness }}
                </div>
                <div class="mb-3">
                    <strong>Smoking:</strong> {{ $preference->smoking ? 'Yes' : 'No' }}
                </div>
                <div class="mb-3">
                    <strong>Noise Tolerance:</strong> {{ $preference->noise_tolerance }}
                </div>
                <div class="mb-3">
                    <strong>Guests Frequency:</strong> {{ $preference->guests_frequency }}
                </div>
                <div class="mb-3">
                    <strong>Room Temperature:</strong> {{ $preference->room_temperature }}
                </div>
                <div class="mb-3">
                    <strong>Music Preference:</strong> {{ $preference->music_preference }}
                </div>
                <div class="mb-3">
                    <strong>Lights Preference:</strong> {{ $preference->lights_preference }}
                </div>
                <div class="mb-3">
                    <strong>Personality:</strong> {{ $preference->introvert_extrovert }}
                </div>
                <div class="mb-3">
                    <strong>Sleep with Light:</strong> {{ $preference->sleep_with_light ? 'Yes' : 'No' }}
                </div>
                <div class="mb-3">
                    <strong>Pets:</strong> {{ $preference->pets ? 'Yes' : 'No' }}
                </div>
                <a href="{{ route('preferences.show') }}" class="btn btn-outline-secondary">View Full Details</a>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            You have not created your lifestyle preferences yet. Click the button above to begin.
        </div>
    @endif
@endsection
