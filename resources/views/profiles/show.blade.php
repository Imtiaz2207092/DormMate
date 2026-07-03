@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Profile</div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        @if($profile->profile_photo)
                            <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="photo" class="img-thumbnail me-3" style="max-width:150px">
                        @endif
                        <div>
                            <p><strong>Student ID:</strong> {{ $profile->student_id }}</p>
                            <p><strong>Department:</strong> {{ $profile->department }}</p>
                            <p><strong>Batch:</strong> {{ $profile->batch }}</p>
                            <p><strong>Hall:</strong> {{ $profile->hall }}</p>
                        </div>
                    </div>

                    <p><strong>Phone:</strong> {{ $profile->phone }}</p>
                    <p><strong>Gender:</strong> {{ ucfirst($profile->gender) }}</p>
                    <p><strong>Bio:</strong> {{ $profile->bio }}</p>

                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
@endsection
