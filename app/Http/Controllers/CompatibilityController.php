<?php

namespace App\Http\Controllers;

use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class CompatibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $matches = $compatibility->getBestMatches($user, 10);

        return view('compatibility.index', [
            'matches' => $matches,
        ]);
    }

    public function show(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $otherId = $request->route('id');

        if (! $otherId) {
            return redirect()->route('compatibility.index');
        }

        $otherUser = \App\Models\User::with(['studentProfile', 'studentPreference'])->find($otherId);

        if (! $otherUser || $otherUser->id === $user->id || ! $otherUser->studentProfile || ! $otherUser->studentPreference) {
            return redirect()->route('compatibility.index');
        }

        $score = $compatibility->calculateScore($user, $otherUser);

        return view('compatibility.show', [
            'match' => $otherUser,
            'score' => $score,
        ]);
    }
}
