@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card rounded-4 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mb-3 text-center">
                        <div>
                            <h5 class="mb-1">Current Roommate</h5>
                            <p class="text-muted mb-0">Manage your active roommate match from your profile page.</p>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('roommate-match.index') }}" class="btn btn-outline-secondary btn-sm">My Roommate</a>
                            <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">Find Roommates</a>
                        </div>
                    </div>

                    @if($currentRoommate)
                        <div class="d-flex flex-column align-items-center gap-3">
                            @if(optional($currentRoommate->studentProfile)->profile_photo)
                                <img src="{{ asset('storage/' . $currentRoommate->studentProfile->profile_photo) }}" alt="{{ $currentRoommate->name }}" class="rounded-circle" style="width:96px;height:96px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:96px;height:96px;">
                                    <span class="fs-3">{{ strtoupper(substr($currentRoommate->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $currentRoommate->name }}</h5>
                                <p class="text-secondary mb-2">{{ optional($currentRoommate->studentProfile)->department ?? 'Department not set' }}</p>
                                <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
                                    <span class="badge bg-secondary">ID: {{ optional($currentRoommate->studentProfile)->student_id ?? 'N/A' }}</span>
                                    <span class="badge bg-secondary">Hall: {{ optional($currentRoommate->studentProfile)->hall ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                <a href="{{ route('students.show', $currentRoommate->id) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                                <form method="POST" action="{{ route('roommate-match.end') }}" class="d-inline-block">
                                    @csrf
                                    <input type="hidden" name="match_id" value="{{ $currentMatch->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm">End Match</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No active roommate match found. Browse students to find a roommate or review your pending requests.</div>
                    @endif
                </div>
            </div>
        </div>

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
