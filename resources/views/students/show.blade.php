@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Student Profile</h2>
                <p class="text-muted mb-0">Public profile view for roommate discovery.</p>
            </div>
            <div class="text-md-end">
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back to search</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card rounded-card shadow-sm p-4">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="card rounded-card shadow-soft h-100 p-4 text-center">
                                @if(optional($student->studentProfile)->profile_photo)
                                    <img src="{{ asset('storage/' . $student->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle mb-4" style="width:170px;height:170px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-4" style="width:170px;height:170px;font-size:2.75rem;">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif

                                <h2 class="h4 mb-1">{{ $student->name }}</h2>
                                <p class="text-muted mb-1">{{ optional($student->studentProfile)->department ?? 'Department not set' }}</p>
                                <p class="text-muted mb-3">{{ optional($student->studentProfile)->hall ?? 'Hall not set' }}</p>
                                <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                    <span class="badge bg-info text-dark">Batch {{ optional($student->studentProfile)->batch ?? 'N/A' }}</span>
                                    <span class="badge bg-secondary">ID {{ optional($student->studentProfile)->student_id ?? 'N/A' }}</span>
                                </div>

                                @if($score !== null)
                                    <div class="mb-4 text-start">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="fw-bold">{{ $score }}% Compatibility</strong>
                                            <span class="badge bg-success">Live</span>
                                        </div>
                                        <div class="progress rounded-pill overflow-hidden mb-2" style="height: 16px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $score }}%;" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <button type="button" class="btn btn-soft-primary btn-sm w-100 fw-semibold mt-2" id="btnExplainAI" data-matched-id="{{ $student->id }}">
                                            ✨ Explain Compatibility with AI
                                        </button>
                                    </div>
                                @endif

                                <div class="d-grid gap-3">
                                    <form method="POST" action="{{ route('messages.open') }}">
                                        @csrf
                                        <input type="hidden" name="other_user_id" value="{{ $student->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg">Chat</button>
                                    </form>

                                    @if($requestStatus === 'accepted')
                                        <button type="button" class="btn btn-success btn-lg">Roommate Match Accepted</button>
                                    @elseif($requestStatus === 'pending')
                                        <button type="button" class="btn btn-warning text-dark btn-lg">Request Pending</button>
                                    @elseif($requestStatus === 'rejected')
                                        <button type="button" class="btn btn-gradient-danger btn-lg">Request Rejected</button>
                                    @elseif($canSendRequest)
                                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#profileRequestModal">Send Roommate Request</button>
                                    @else
                                        <button type="button" class="btn btn-soft-secondary btn-lg" disabled>Roommate Match Unavailable</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <h3 class="h5 mb-2">About</h3>
                                    <p class="text-muted mb-0">{{ optional($student->studentProfile)->bio ?? 'No bio available.' }}</p>
                                </div>
                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back to search</a>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Department</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->department ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Hall</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->hall ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Batch</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->batch ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Student ID</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->student_id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-5 mb-3">Lifestyle Preferences</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Sleep Schedule</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->sleep_schedule ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Wake Up Time</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->wake_up_time ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Study Habit</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->study_habit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Cleanliness</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->cleanliness ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Smoking</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->smoking ? 'Yes' : 'No' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Noise Tolerance</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->noise_tolerance ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Room Temperature</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->room_temperature ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Music Preference</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->music_preference ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Lights Preference</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->lights_preference ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Personality</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->introvert_extrovert ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="profileRequestModal" tabindex="-1" aria-labelledby="profileRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-soft border-0">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="profileRequestModalLabel">Send Roommate Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('roommate-requests.send') }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $student->id }}">
                        <div class="modal-body">
                            <p class="text-muted">Send your request directly to {{ $student->name }}.</p>
                            <div class="mb-3">
                                <label class="form-label">Optional Message</label>
                                <textarea name="message" class="form-control" rows="4" maxlength="250" placeholder="Write a short note (optional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- AI Compatibility Explanation Modal -->
        <div class="modal fade" id="aiCompatibilityModal" tabindex="-1" aria-labelledby="aiCompatibilityModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow border-0">
                    <div class="modal-header border-bottom border-secondary border-opacity-10 py-3">
                        <h5 class="modal-title fw-bold text-dark" id="aiCompatibilityModalLabel">
                            <i class="bi bi-cpu text-primary me-2"></i> AI Compatibility Insights
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Loading State -->
                        <div id="aiLoadingState" class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h6 class="fw-semibold text-secondary">Analyzing roommate synergy...</h6>
                            <p class="text-muted small mb-0">Google Gemini is compiling profile and preference comparisons.</p>
                        </div>

                        <!-- Error State -->
                        <div id="aiErrorState" class="alert alert-danger d-none" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="aiErrorMessage">Unable to generate AI explanation right now. Please try again later.</span>
                        </div>

                        <!-- Content State -->
                        <div id="aiContentState" class="d-none">
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary text-uppercase small tracking-wider mb-2">Overall Summary</h6>
                                <p class="text-dark fs-6" id="aiSummary" style="line-height: 1.6;"></p>
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <h6 class="fw-bold text-secondary text-uppercase small tracking-wider mb-3">
                                <i class="bi bi-arrow-left-right text-primary me-2"></i> Side-by-Side Comparison
                            </h6>

                            <div class="card border border-secondary border-opacity-10 shadow-sm overflow-hidden mb-4">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="min-width: 600px; font-size: 0.9rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" style="width: 25%;" class="ps-3">Preference / Habit</th>
                                                <th scope="col" style="width: 20%;">You</th>
                                                <th scope="col" style="width: 20%;">{{ $student->name }}</th>
                                                <th scope="col" style="width: 35%;" class="pe-3">AI Verdict</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Sleep Schedule -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-moon-stars text-primary me-2"></i>Sleep Schedule
                                                </td>
                                                <td>{{ $myPref->sleep_schedule ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->sleep_schedule ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-sleep_schedule">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Wake Up Time -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-alarm text-primary me-2"></i>Wake Up Time
                                                </td>
                                                <td>{{ $myPref ? \Carbon\Carbon::parse($myPref->wake_up_time)->format('h:i A') : 'N/A' }}</td>
                                                <td>{{ $targetPref ? \Carbon\Carbon::parse($targetPref->wake_up_time)->format('h:i A') : 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-wake_up_time">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Study Habit -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-book text-primary me-2"></i>Study Habit
                                                </td>
                                                <td>{{ $myPref->study_habit ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->study_habit ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-study_habit">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Cleanliness -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-brush text-primary me-2"></i>Cleanliness
                                                </td>
                                                <td>{{ $myPref->cleanliness ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->cleanliness ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-cleanliness">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Smoking -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-capsule text-primary me-2"></i>Smoking
                                                </td>
                                                <td>{{ isset($myPref->smoking) ? ($myPref->smoking ? 'Smoker' : 'Non-smoker') : 'N/A' }}</td>
                                                <td>{{ isset($targetPref->smoking) ? ($targetPref->smoking ? 'Smoker' : 'Non-smoker') : 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-smoking">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Noise Tolerance -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-volume-up text-primary me-2"></i>Noise Tolerance
                                                </td>
                                                <td>{{ $myPref->noise_tolerance ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->noise_tolerance ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-noise_tolerance">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Room Temperature -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-thermometer-half text-primary me-2"></i>Temperature
                                                </td>
                                                <td>{{ $myPref->room_temperature ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->room_temperature ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-room_temperature">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Music Preference -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-music-note-beamed text-primary me-2"></i>Music Preference
                                                </td>
                                                <td>{{ $myPref->music_preference ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->music_preference ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-music_preference">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Lights Preference -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-lightbulb text-primary me-2"></i>Lights Preference
                                                </td>
                                                <td>{{ $myPref->lights_preference ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->lights_preference ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-lights_preference">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Introvert Extrovert -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-people text-primary me-2"></i>Personality Type
                                                </td>
                                                <td>{{ $myPref->introvert_extrovert ?? 'N/A' }}</td>
                                                <td>{{ $targetPref->introvert_extrovert ?? 'N/A' }}</td>
                                                <td class="pe-3 text-muted small" id="ai-insight-introvert_extrovert">
                                                    Loading...
                                                </td>
                                            </tr>

                                            <!-- Hobbies -->
                                            <tr>
                                                <td class="ps-3 fw-semibold text-muted">
                                                    <i class="bi bi-controller text-primary me-2"></i>Hobbies / Interests
                                                </td>
                                                <td><div class="text-truncate" style="max-width: 120px;" title="{{ $myPref->hobbies ?? 'N/A' }}">{{ $myPref->hobbies ?? 'N/A' }}</div></td>
                                                <td><div class="text-truncate" style="max-width: 120px;" title="{{ $targetPref->hobbies ?? 'N/A' }}">{{ $targetPref->hobbies ?? 'N/A' }}</div></td>
                                                <td class="pe-3 text-muted small" id="ai-insight-hobbies">
                                                    Loading...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-success text-uppercase small tracking-wider mb-3">
                                        <i class="bi bi-plus-circle-fill me-2"></i> Key Strengths
                                    </h6>
                                    <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiStrengths">
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-warning text-uppercase small tracking-wider mb-3">
                                        <i class="bi bi-dash-circle-fill me-2"></i> Possible Differences
                                    </h6>
                                    <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiDifferences">
                                    </ul>
                                </div>
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <div class="mb-4">
                                <h6 class="fw-bold text-primary text-uppercase small tracking-wider mb-3">
                                    <i class="bi bi-lightbulb-fill me-2"></i> Recommendations & Suggestions
                                </h6>
                                <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiSuggestions">
                                </ul>
                            </div>

                            <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                                <span class="small fw-semibold text-muted">Final Recommendation</span>
                                <span class="badge fs-6 fw-bold px-3 py-2" id="aiRecommendationBadge"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnExplainAI = document.getElementById('btnExplainAI');
        if (!btnExplainAI) return;

        const matchedUserId = btnExplainAI.getAttribute('data-matched-id');
        const modalElement = document.getElementById('aiCompatibilityModal');
        const modalInstance = new bootstrap.Modal(modalElement);

        const loadingState = document.getElementById('aiLoadingState');
        const errorState = document.getElementById('aiErrorState');
        const errorMessage = document.getElementById('aiErrorMessage');
        const contentState = document.getElementById('aiContentState');

        const summaryEl = document.getElementById('aiSummary');
        const strengthsEl = document.getElementById('aiStrengths');
        const differencesEl = document.getElementById('aiDifferences');
        const suggestionsEl = document.getElementById('aiSuggestions');
        const badgeEl = document.getElementById('aiRecommendationBadge');

        btnExplainAI.addEventListener('click', function() {
            // Reset states and open modal
            loadingState.classList.remove('d-none');
            errorState.classList.add('d-none');
            contentState.classList.add('d-none');
            modalInstance.show();

            // Reset table cells to Loading...
            const keys = ['sleep_schedule', 'wake_up_time', 'study_habit', 'cleanliness', 'smoking', 'noise_tolerance', 'room_temperature', 'music_preference', 'lights_preference', 'introvert_extrovert', 'hobbies'];
            keys.forEach(key => {
                const cell = document.getElementById(`ai-insight-${key}`);
                if (cell) {
                    cell.innerHTML = `<span class="text-secondary opacity-75">Loading...</span>`;
                }
            });

            // Disable launch button and show loading text
            btnExplainAI.disabled = true;
            btnExplainAI.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Explaining...';

            // POST AJAX Request
            fetch(`/students/${matchedUserId}/explain-compatibility`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Server error'); });
                }
                return response.json();
            })
            .then(data => {
                // Render content
                summaryEl.textContent = data.summary || 'No summary generated.';

                // Render Strengths
                strengthsEl.innerHTML = '';
                if (Array.isArray(data.strengths)) {
                    data.strengths.forEach(str => {
                        strengthsEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-check-lg text-success"></i> <span>${str}</span></li>`;
                    });
                }

                // Render Differences
                differencesEl.innerHTML = '';
                if (Array.isArray(data.differences)) {
                    data.differences.forEach(diff => {
                        differencesEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-x-lg text-warning"></i> <span>${diff}</span></li>`;
                    });
                }

                // Render Suggestions
                suggestionsEl.innerHTML = '';
                if (Array.isArray(data.suggestions)) {
                    data.suggestions.forEach(sug => {
                        suggestionsEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-arrow-right-short text-primary fs-5 leading-none"></i> <span>${sug}</span></li>`;
                    });
                }

                // Render recommendation badge
                const rec = (data.recommendation || 'Neutral').trim();
                badgeEl.textContent = rec;
                badgeEl.className = 'badge fs-6 fw-bold px-3 py-2'; // Reset

                if (rec === 'Highly Recommended') {
                    badgeEl.classList.add('bg-success');
                } else if (rec === 'Recommended') {
                    badgeEl.classList.add('bg-primary');
                } else if (rec === 'Not Recommended') {
                    badgeEl.classList.add('bg-danger');
                } else {
                    badgeEl.classList.add('bg-secondary');
                }

                // Populate side-by-side table cells with AI comments
                if (data.comparison) {
                    Object.keys(data.comparison).forEach(key => {
                        const cell = document.getElementById(`ai-insight-${key}`);
                        if (cell) {
                            cell.innerHTML = `<span class="text-dark fw-semibold"><i class="bi bi-robot text-primary me-1"></i> ${data.comparison[key]}</span>`;
                        }
                    });
                }

                // Switch display states
                loadingState.classList.add('d-none');
                contentState.classList.remove('d-none');
            })
            .catch(error => {
                errorMessage.textContent = error.message || 'Unable to generate AI explanation right now. Please try again later.';
                loadingState.classList.add('d-none');
                errorState.classList.remove('d-none');
            })
            .finally(() => {
                // Re-enable launch button
                btnExplainAI.disabled = false;
                btnExplainAI.innerHTML = '✨ Explain Compatibility with AI';
            });
        });
    });
    </script>
@endsection
