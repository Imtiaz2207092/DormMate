@extends('layouts.app')

@section('content')
    @include('preferences.form', [
        'title' => 'Edit Lifestyle Preferences',
        'action' => route('preferences.update'),
        'method' => 'PUT',
        'submitLabel' => 'Update Preferences',
    ])
@endsection
