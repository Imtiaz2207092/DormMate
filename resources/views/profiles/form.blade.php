@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ $profile->exists ? 'Edit Profile' : 'Create Profile' }}</div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ $profile->exists ? route('profile.update') : route('profile.store') }}">
                    @csrf
                    @if($profile->exists)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text" name="student_id" value="{{ old('student_id', $profile->student_id) }}" class="form-control @error('student_id') is-invalid @enderror">
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                            <option value="">Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ old('department', $profile->department) === $department ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                            @endforeach
                        </select>
                        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batch</label>
                        <input type="text" name="batch" value="{{ old('batch', $profile->batch) }}" class="form-control @error('batch') is-invalid @enderror">
                        @error('batch')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hall</label>
                        <select name="hall" class="form-select @error('hall') is-invalid @enderror">
                            <option value="">Select hall</option>
                            @foreach($halls as $hall)
                                <option value="{{ $hall }}" {{ old('hall', $profile->hall) === $hall ? 'selected' : '' }}>{{ ucwords($hall) }}</option>
                            @endforeach
                        </select>
                        @error('hall')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $profile->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror">
                        @if($profile->profile_photo)
                            <div class="mt-2"><img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="photo" class="img-thumbnail" style="max-width:120px"></div>
                        @endif
                        @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary">{{ $profile->exists ? 'Update Profile' : 'Save Profile' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
