<?php

namespace App\Http\Controllers;

use App\Http\Requests\LifestylePreferenceRequest;
use App\Models\LifestylePreference;
use Illuminate\Http\Request;

class LifestylePreferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $prefs = LifestylePreference::with('user')->paginate(15);
        return view('preferences.index', ['prefs' => $prefs]);
    }

    public function create(Request $request)
    {
        // If user already has preferences, redirect to edit
        if ($request->user()->lifestylePreference) {
            return redirect()->route('preferences.edit', $request->user()->lifestylePreference);
        }

        return view('preferences.form', ['pref' => new LifestylePreference()]);
    }

    public function store(LifestylePreferenceRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $pref = new LifestylePreference();
        $pref->user_id = $user->id;
        $pref->sleep_schedule = $data['sleep_schedule'] ?? null;
        $pref->study_habit = $data['study_habit'] ?? null;
        $pref->smoking = $data['smoking'] ?? null;
        $pref->cleanliness = $data['cleanliness'] ?? null;
        $pref->noise_tolerance = $data['noise_tolerance'] ?? null;
        $pref->hobbies = isset($data['hobbies']) ? array_values(array_filter(array_map('trim', explode(',', $data['hobbies'])))) : [];

        $pref->save();

        return redirect()->route('preferences.show', $pref)->with('status', 'Preferences saved.');
    }

    public function show(LifestylePreference $preference)
    {
        return view('preferences.show', ['pref' => $preference]);
    }

    public function edit(Request $request, LifestylePreference $preference)
    {
        // ensure ownership or admin -- for now only owner
        if ($request->user()->id !== $preference->user_id) {
            abort(403);
        }

        return view('preferences.form', ['pref' => $preference]);
    }

    public function update(LifestylePreferenceRequest $request, LifestylePreference $preference)
    {
        if ($request->user()->id !== $preference->user_id) {
            abort(403);
        }

        $data = $request->validated();
        $preference->sleep_schedule = $data['sleep_schedule'] ?? null;
        $preference->study_habit = $data['study_habit'] ?? null;
        $preference->smoking = $data['smoking'] ?? null;
        $preference->cleanliness = $data['cleanliness'] ?? null;
        $preference->noise_tolerance = $data['noise_tolerance'] ?? null;
        $preference->hobbies = isset($data['hobbies']) ? array_values(array_filter(array_map('trim', explode(',', $data['hobbies'])))) : [];

        $preference->save();

        return redirect()->route('preferences.show', $preference)->with('status', 'Preferences updated.');
    }

    public function destroy(Request $request, LifestylePreference $preference)
    {
        if ($request->user()->id !== $preference->user_id) {
            abort(403);
        }

        $preference->delete();

        return redirect()->route('preferences.index')->with('status', 'Preferences deleted.');
    }
}
