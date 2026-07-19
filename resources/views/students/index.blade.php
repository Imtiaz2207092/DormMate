@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Find Roommates</h2>
                <p class="text-muted mb-0">Search and filter students, then review compatibility scores instantly.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>

        <div class="card rounded-4 border-0 shadow-sm mb-4" id="student-search-card">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('students.index') }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="search" name="q" class="form-control border-start-0 ps-0" placeholder="Search by name, student ID, department, hall or batch..." value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <select name="sort_by" class="form-select">
                                <option value="compatibility_desc" {{ ($sortBy ?? '') === 'compatibility_desc' ? 'selected' : '' }}>Highest Compatibility</option>
                                <option value="compatibility_asc" {{ ($sortBy ?? '') === 'compatibility_asc' ? 'selected' : '' }}>Lowest Compatibility</option>
                                <option value="newest" {{ ($sortBy ?? '') === 'newest' ? 'selected' : '' }}>Newest Students</option>
                                <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>Oldest Students</option>
                                <option value="name_asc" {{ ($sortBy ?? '') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="name_desc" {{ ($sortBy ?? '') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            </select>
                        </div>
                        <div class="col-lg-3 d-flex gap-2">
                            <button class="btn btn-soft-primary flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                                <i class="bi bi-funnel"></i> Filters
                            </button>
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </div>

                    @php
                        $hasActiveFilters = !empty($filters['department']) || !empty($filters['batch']) || !empty($filters['hall']) || !empty($filters['gender']) || !empty($filters['sleep_schedule']) || !empty($filters['study_habit']) || !empty($filters['cleanliness']) || !empty($filters['smoking']) || !empty($filters['noise_tolerance']) || !empty($filters['room_temperature']) || !empty($filters['music_preference']) || !empty($filters['introvert_extrovert']);
                    @endphp
                    <div class="collapse {{ $hasActiveFilters ? 'show' : '' }} mt-4 pt-3 border-top border-secondary border-opacity-10" id="advancedFilters">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Department</label>
                                <select name="department" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}" {{ (isset($filters['department']) && $filters['department'] === $department) ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Batch</label>
                                <select name="batch" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($batches as $batch)
                                        <option value="{{ $batch }}" {{ (isset($filters['batch']) && $filters['batch'] === $batch) ? 'selected' : '' }}>{{ $batch }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Hall</label>
                                <select name="hall" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall }}" {{ (isset($filters['hall']) && $filters['hall'] === $hall) ? 'selected' : '' }}>{{ ucwords($hall) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender }}" {{ (isset($filters['gender']) && $filters['gender'] === $gender) ? 'selected' : '' }}>{{ ucfirst($gender) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Sleep</label>
                                <select name="sleep_schedule" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($sleepSchedules as $sleepSchedule)
                                        <option value="{{ $sleepSchedule }}" {{ (isset($filters['sleep_schedule']) && $filters['sleep_schedule'] === $sleepSchedule) ? 'selected' : '' }}>{{ ucwords($sleepSchedule) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Study Habit</label>
                                <select name="study_habit" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($studyHabits as $studyHabit)
                                        <option value="{{ $studyHabit }}" {{ (isset($filters['study_habit']) && $filters['study_habit'] === $studyHabit) ? 'selected' : '' }}>{{ ucwords($studyHabit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Cleanliness</label>
                                <select name="cleanliness" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($cleanlinessLevels as $cleanliness)
                                        <option value="{{ $cleanliness }}" {{ (isset($filters['cleanliness']) && $filters['cleanliness'] === $cleanliness) ? 'selected' : '' }}>{{ ucwords($cleanliness) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Smoking</label>
                                <select name="smoking" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    <option value="yes" {{ (isset($filters['smoking']) && $filters['smoking'] === 'yes') ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ (isset($filters['smoking']) && $filters['smoking'] === 'no') ? 'selected' : '' }}>No</option>
                                    <option value="occasional" {{ (isset($filters['smoking']) && $filters['smoking'] === 'occasional') ? 'selected' : '' }}>Occasional</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Noise</label>
                                <select name="noise_tolerance" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($noiseLevels as $noiseLevel)
                                        <option value="{{ $noiseLevel }}" {{ (isset($filters['noise_tolerance']) && $filters['noise_tolerance'] === $noiseLevel) ? 'selected' : '' }}>{{ ucwords($noiseLevel) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Temperature</label>
                                <select name="room_temperature" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($roomTemperatures as $roomTemperature)
                                        <option value="{{ $roomTemperature }}" {{ (isset($filters['room_temperature']) && $filters['room_temperature'] === $roomTemperature) ? 'selected' : '' }}>{{ ucwords($roomTemperature) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Music</label>
                                <select name="music_preference" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($musicPreferences as $musicPreference)
                                        <option value="{{ $musicPreference }}" {{ (isset($filters['music_preference']) && $filters['music_preference'] === $musicPreference) ? 'selected' : '' }}>{{ ucwords($musicPreference) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2">
                                <label class="form-label">Personality</label>
                                <select name="introvert_extrovert" class="form-select form-select-sm">
                                    <option value="">Any</option>
                                    @foreach($personalities as $personality)
                                        <option value="{{ $personality }}" {{ (isset($filters['introvert_extrovert']) && $filters['introvert_extrovert'] === $personality) ? 'selected' : '' }}>{{ ucfirst($personality) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-end mt-3 border-top border-secondary border-opacity-10 pt-3">
                                <a href="{{ route('students.index') }}" class="btn btn-soft-secondary btn-sm me-2">Clear Filters</a>
                                <button type="submit" class="btn btn-primary btn-sm px-4">Apply Filters</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-1">Search Results</h5>
                <div id="search-loading-spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></div>
            </div>
            <span class="badge bg-secondary" id="search-results-count">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="search-results-grid">
            @forelse($users as $student)
                <div class="col">
                    @include('partials.profile_card', ['student' => $student, 'hideConnect' => true])
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No students found matching your criteria.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center" id="search-pagination-container">
            {{ $users->links() }}
        </div>
    </div>

    <div class="modal fade" id="sharedSendRequestModal" tabindex="-1" aria-labelledby="sharedSendRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-soft border-0">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="sharedSendRequestModalLabel">Send Roommate Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('roommate-requests.send') }}">
                    @csrf
                    <input type="hidden" name="receiver_id" id="shared-receiver-id" value="">
                    <div class="modal-body">
                        <p class="text-muted">Send a roommate request to <span id="shared-receiver-name" class="fw-semibold"></span>.</p>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="4" maxlength="250" placeholder="Write a note (optional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="q"]');
            const searchForm = document.querySelector('form');
            const resultsGrid = document.getElementById('search-results-grid');
            const resultsCount = document.getElementById('search-results-count');
            const paginationContainer = document.getElementById('search-pagination-container');
            const loadingSpinner = document.getElementById('search-loading-spinner');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const params = new URLSearchParams(window.location.search);
            const openSearch = params.has('open_search') || params.has('q');
            if (openSearch && searchInput) {
                const card = document.getElementById('student-search-card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    searchInput.focus();
                }
            }

            function renderStudentCard(student) {
                const assetUrl = "{{ asset('storage') }}";
                const openMessageRoute = "{{ route('messages.open') }}";
                const showRouteBase = "{{ route('students.show', ':id') }}";
                const showRoute = showRouteBase.replace(':id', student.id);

                let avatarHtml = '';
                if (student.profile_photo) {
                    avatarHtml = `<img src="${assetUrl}/${student.profile_photo}" alt="Profile photo" class="rounded-circle profile-photo-lg mb-3" style="width:72px;height:72px;object-fit:cover;">`;
                } else {
                    const initial = student.name.charAt(0).toUpperCase();
                    avatarHtml = `<div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center profile-photo-lg mb-3" style="width:72px;height:72px;background-color:var(--primary);color:#ffffff;">
                        <span class="fs-3">${initial}</span>
                    </div>`;
                }



                return `
                    <div class="col">
                        <div class="card rounded-card shadow-sm hover-shadow profile-card-fixed h-100">
                            <div class="card-body profile-card-body">
                                <div class="profile-avatar">
                                    ${avatarHtml}
                                </div>

                                <div class="profile-meta mt-3">
                                    <h5 class="mb-1 text-truncate" style="max-width:220px;">${student.name}</h5>
                                    <p class="text-muted small mb-2 text-truncate" style="max-width:220px;">${(student.department || 'Department not set').toUpperCase()}</p>

                                    <div class="d-flex gap-2 justify-content-center flex-wrap mb-3 w-100">
                                        <span class="badge bg-info text-dark">ID: ${student.student_id || 'N/A'}</span>
                                        <span class="badge bg-secondary">Batch: ${student.batch || 'N/A'}</span>
                                    </div>
                                </div>

                                <div class="profile-compat mb-3 w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted">Compatibility</span>
                                        <strong>${student.compatibility_score !== null ? student.compatibility_score + '%' : 'N/A'}</strong>
                                    </div>
                                    <div class="progress" style="height: 14px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: ${student.compatibility_score || 0}%;" aria-valuenow="${student.compatibility_score || 0}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="profile-bio text-truncate-2 text-muted mb-3 w-100">${student.bio || 'No bio provided yet.'}</div>

                                <div class="d-flex gap-2 w-100 mt-auto profile-card-actions">
                                    <a href="${showRoute}" class="btn btn-primary flex-grow-1">View Profile</a>
                                    <form method="POST" action="${openMessageRoute}" class="m-0">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="other_user_id" value="${student.id}">
                                        <button type="submit" class="btn btn-outline-primary" style="padding: 0.65rem 0.95rem;" title="Chat"><i class="bi bi-chat-dots"></i></button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                `;
            }

            async function performSearch() {
                loadingSpinner.classList.remove('d-none');
                const queryVal = searchInput ? searchInput.value : '';
                const filterForm = document.getElementById('search-filter-form');
                const formData = new URLSearchParams(new FormData(filterForm)).toString();
                
                try {
                    const response = await fetch(`/api/search?name=${encodeURIComponent(queryVal)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (!response.ok) throw new Error('Search request failed');
                    
                    const result = await response.json();
                    
                    if (result.success && Array.isArray(result.data)) {
                        const students = result.data;
                        resultsCount.textContent = `Showing ${students.length} students matching your criteria.`;
                        paginationContainer.innerHTML = '';
                        
                        if (students.length === 0) {
                            resultsGrid.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-info">No students found matching your criteria.</div>
                                </div>
                            `;
                        } else {
                            resultsGrid.innerHTML = students.map(student => renderStudentCard(student)).join('');
                        }
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    loadingSpinner.classList.add('d-none');
                }
            }

            let debounceTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(performSearch, 300);
                });
            }

            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    performSearch();
                });
            }

            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('.btn-connect-trigger');
                if (trigger) {
                    document.getElementById('shared-receiver-id').value = trigger.dataset.id;
                    document.getElementById('shared-receiver-name').textContent = trigger.dataset.name;
                    const modal = new bootstrap.Modal(document.getElementById('sharedSendRequestModal'));
                    modal.show();
                }
            });
        });
    </script>
@endsection
