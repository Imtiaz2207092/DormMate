@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Compatibility Matches</h2>
                <p class="text-muted mb-0">These students have both completed their profile and preferences.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>

        @if($matches->isEmpty())
            <div class="alert alert-info">No compatible students found.</div>
        @else
            <div class="row g-4">
                @foreach($matches as $match)
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded-4 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if(optional($match->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $match->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle" style="width:72px; height:72px; object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:72px; height:72px;">
                                            <span class="fs-4">{{ strtoupper(substr($match->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0">{{ $match->name }}</h5>
                                        <small class="text-muted">{{ optional($match->studentProfile)->department ?? 'Department not set' }} • {{ optional($match->studentProfile)->hall ?? 'Hall not set' }}</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Compatibility</span>
                                        <strong>{{ $match->compatibility_score }}%</strong>
                                    </div>
                                    <div class="progress" style="height: 14px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $match->compatibility_score }}%;" aria-valuenow="{{ $match->compatibility_score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ route('compatibility.show', ['id' => $match->id]) }}" class="btn btn-primary w-100">View Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
