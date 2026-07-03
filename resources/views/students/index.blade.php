@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Keyword</label>
                    <input type="text" name="q" value="{{ old('q', $filters['q'] ?? '') }}" class="form-control" placeholder="Name or Student ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" value="{{ old('department', $filters['department'] ?? '') }}" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Batch</label>
                    <input type="text" name="batch" value="{{ old('batch', $filters['batch'] ?? '') }}" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Hall</label>
                    <input type="text" name="hall" value="{{ old('hall', $filters['hall'] ?? '') }}" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Any</option>
                        <option value="male" {{ (isset($filters['gender']) && $filters['gender']=='male') ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (isset($filters['gender']) && $filters['gender']=='female') ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ (isset($filters['gender']) && $filters['gender']=='other') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lifestyle Filters</label>
                    <div class="d-flex gap-2">
                        <input type="text" name="sleep_schedule" value="{{ old('sleep_schedule', $filters['sleep_schedule'] ?? '') }}" class="form-control" placeholder="Sleep">
                        <input type="text" name="study_habit" value="{{ old('study_habit', $filters['study_habit'] ?? '') }}" class="form-control" placeholder="Study">
                        <input type="text" name="smoking" value="{{ old('smoking', $filters['smoking'] ?? '') }}" class="form-control" placeholder="Smoking">
                        <input type="text" name="cleanliness" value="{{ old('cleanliness', $filters['cleanliness'] ?? '') }}" class="form-control" placeholder="Cleanliness">
                        <input type="text" name="noise_tolerance" value="{{ old('noise_tolerance', $filters['noise_tolerance'] ?? '') }}" class="form-control" placeholder="Noise">
                    </div>
                </div>

                <div class="col-12 text-end mt-2">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($profiles as $profile)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            @if($profile->profile_photo_path)
                                <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="photo" class="rounded-circle me-3" style="width:64px; height:64px; object-fit:cover">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:64px; height:64px">{{ strtoupper(substr($profile->user->name,0,1)) }}</div>
                            @endif
                            <div>
                                <h5 class="mb-0">{{ $profile->user->name }}</h5>
                                <small class="text-muted">{{ $profile->department }} • {{ $profile->hall }}</small>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="badge bg-info">ID: {{ $profile->student_id ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    @if(isset($scores[$profile->id]))
                                        <span class="badge bg-success">Compatibility: {{ $scores[$profile->id] }}%</span>
                                    @else
                                        <span class="badge bg-secondary">Compatibility: N/A</span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('profile.show') }}?user={{ $profile->user->id }}" class="btn btn-outline-primary w-100">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No students found for the selected filters.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $profiles->links() }}
    </div>
@endsection
