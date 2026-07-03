<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $title }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if($method === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sleep_schedule" class="form-label">Sleep Schedule</label>
                            <select id="sleep_schedule" name="sleep_schedule" class="form-select @error('sleep_schedule') is-invalid @enderror">
                                <option value="">Choose sleep schedule</option>
                                @foreach(['Early Sleeper', 'Late Sleeper', 'Flexible'] as $option)
                                    <option value="{{ $option }}" {{ old('sleep_schedule', $preference->sleep_schedule) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('sleep_schedule')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="wake_up_time" class="form-label">Wake Up Time</label>
                            <input id="wake_up_time" type="time" name="wake_up_time" value="{{ old('wake_up_time', $preference->wake_up_time) }}" class="form-control @error('wake_up_time') is-invalid @enderror">
                            @error('wake_up_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="study_habit" class="form-label">Study Habit</label>
                            <select id="study_habit" name="study_habit" class="form-select @error('study_habit') is-invalid @enderror">
                                <option value="">Choose study habit</option>
                                @foreach(['Silent', 'Group Study', 'Flexible'] as $option)
                                    <option value="{{ $option }}" {{ old('study_habit', $preference->study_habit) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('study_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cleanliness" class="form-label">Cleanliness</label>
                            <select id="cleanliness" name="cleanliness" class="form-select @error('cleanliness') is-invalid @enderror">
                                <option value="">Choose cleanliness</option>
                                @foreach(['Low', 'Medium', 'High'] as $option)
                                    <option value="{{ $option }}" {{ old('cleanliness', $preference->cleanliness) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('cleanliness')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Smoking</label>
                            @foreach(['1' => 'Yes', '0' => 'No'] as $value => $label)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('smoking') is-invalid @enderror" type="radio" name="smoking" id="smoking_{{ $value }}" value="{{ $value }}" {{ old('smoking', (string) $preference->smoking) === $value ? 'checked' : '' }}>
                                    <label class="form-check-label" for="smoking_{{ $value }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @error('smoking')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="noise_tolerance" class="form-label">Noise Tolerance</label>
                            <select id="noise_tolerance" name="noise_tolerance" class="form-select @error('noise_tolerance') is-invalid @enderror">
                                <option value="">Choose tolerance</option>
                                @foreach(['Low', 'Medium', 'High'] as $option)
                                    <option value="{{ $option }}" {{ old('noise_tolerance', $preference->noise_tolerance) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('noise_tolerance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guests_frequency" class="form-label">Guests Frequency</label>
                            <select id="guests_frequency" name="guests_frequency" class="form-select @error('guests_frequency') is-invalid @enderror">
                                <option value="">Choose guest frequency</option>
                                @foreach(['Never', 'Sometimes', 'Frequently'] as $option)
                                    <option value="{{ $option }}" {{ old('guests_frequency', $preference->guests_frequency) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('guests_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="room_temperature" class="form-label">Room Temperature</label>
                            <select id="room_temperature" name="room_temperature" class="form-select @error('room_temperature') is-invalid @enderror">
                                <option value="">Choose room temperature</option>
                                @foreach(['Cold', 'Moderate', 'Warm'] as $option)
                                    <option value="{{ $option }}" {{ old('room_temperature', $preference->room_temperature) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('room_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="music_preference" class="form-label">Music Preference</label>
                            <select id="music_preference" name="music_preference" class="form-select @error('music_preference') is-invalid @enderror">
                                <option value="">Choose music preference</option>
                                @foreach(['Quiet', 'Soft Music', 'Loud Music'] as $option)
                                    <option value="{{ $option }}" {{ old('music_preference', $preference->music_preference) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('music_preference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lights_preference" class="form-label">Lights Preference</label>
                            <select id="lights_preference" name="lights_preference" class="form-select @error('lights_preference') is-invalid @enderror">
                                <option value="">Choose lights preference</option>
                                @foreach(['Dark', 'Dim', 'Bright'] as $option)
                                    <option value="{{ $option }}" {{ old('lights_preference', $preference->lights_preference) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('lights_preference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="introvert_extrovert" class="form-label">Introvert / Extrovert</label>
                            <select id="introvert_extrovert" name="introvert_extrovert" class="form-select @error('introvert_extrovert') is-invalid @enderror">
                                <option value="">Choose personality style</option>
                                @foreach(['Introvert', 'Ambivert', 'Extrovert'] as $option)
                                    <option value="{{ $option }}" {{ old('introvert_extrovert', $preference->introvert_extrovert) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('introvert_extrovert')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label d-block">Sleep with Light</label>
                            @foreach(['1' => 'Yes', '0' => 'No'] as $value => $label)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('sleep_with_light') is-invalid @enderror" type="radio" name="sleep_with_light" id="sleep_with_light_{{ $value }}" value="{{ $value }}" {{ old('sleep_with_light', (string) $preference->sleep_with_light) === $value ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sleep_with_light_{{ $value }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @error('sleep_with_light')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label d-block">Pets</label>
                            @foreach(['1' => 'Yes', '0' => 'No'] as $value => $label)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('pets') is-invalid @enderror" type="radio" name="pets" id="pets_{{ $value }}" value="{{ $value }}" {{ old('pets', (string) $preference->pets) === $value ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pets_{{ $value }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @error('pets')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="hobbies" class="form-label">Hobbies</label>
                        <textarea id="hobbies" name="hobbies" rows="3" class="form-control @error('hobbies') is-invalid @enderror">{{ old('hobbies', $preference->hobbies) }}</textarea>
                        @error('hobbies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="languages" class="form-label">Languages</label>
                        <textarea id="languages" name="languages" rows="3" class="form-control @error('languages') is-invalid @enderror">{{ old('languages', $preference->languages) }}</textarea>
                        @error('languages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">Additional Notes</label>
                        <textarea id="additional_notes" name="additional_notes" rows="3" class="form-control @error('additional_notes') is-invalid @enderror">{{ old('additional_notes', $preference->additional_notes) }}</textarea>
                        @error('additional_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                    <a href="{{ route('preferences.index') }}" class="btn btn-link">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
