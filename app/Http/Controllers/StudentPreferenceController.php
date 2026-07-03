<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentPreferenceRequest;
use App\Models\StudentPreference;
use Illuminate\Http\Request;

class StudentPreferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $preference = $request->user()->studentPreference;

        return view('preferences.index', compact('preference'));
    }

    public function create(Request $request)
    {
        if ($request->user()->studentPreference) {
            return redirect()->route('preferences.edit');
        }

        return view('preferences.create', [
            'preference' => new StudentPreference(),
        ]);
    }

    public function store(StudentPreferenceRequest $request)
    {
        $user = $request->user();

        if ($user->studentPreference) {
            return redirect()->route('preferences.edit');
        }

        StudentPreference::create(array_merge(
            $request->validated(),
            ['user_id' => $user->id]
        ));

        return redirect()->route('preferences.show')->with('status', 'Preferences saved successfully.');
    }

    public function show(Request $request)
    {
        $preference = $request->user()->studentPreference;

        if (! $preference) {
            return redirect()->route('preferences.create');
        }

        return view('preferences.show', compact('preference'));
    }

    public function edit(Request $request)
    {
        $preference = $request->user()->studentPreference;

        if (! $preference) {
            return redirect()->route('preferences.create');
        }

        return view('preferences.edit', compact('preference'));
    }

    public function update(StudentPreferenceRequest $request)
    {
        $preference = $request->user()->studentPreference;

        if (! $preference) {
            return redirect()->route('preferences.create');
        }

        $preference->update($request->validated());

        return redirect()->route('preferences.show')->with('status', 'Preferences updated successfully.');
    }
}