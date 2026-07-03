@extends('layouts.app')

@section('content')
    @include('preferences.form', [
        'title' => 'Create Lifestyle Preferences',
        'action' => route('preferences.store'),
        'method' => 'POST',
        'submitLabel' => 'Save Preferences',
    ])
@endsection
