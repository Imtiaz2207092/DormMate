<?php

namespace App\Http\Controllers;

use App\Models\RoommateMatch;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class RoommateMatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $currentMatch = RoommateMatch::with(['studentOne.studentProfile', 'studentTwo.studentProfile'])
            ->active()
            ->where(function ($query) use ($user) {
                $query->where('student_one_id', $user->id)
                    ->orWhere('student_two_id', $user->id);
            })
            ->first();

        $otherStudent = null;
        if ($currentMatch) {
            $otherStudent = $currentMatch->student_one_id === $user->id
                ? $currentMatch->studentTwo
                : $currentMatch->studentOne;
        }

        return view('roommate_match.index', [
            'currentMatch' => $currentMatch,
            'otherStudent' => $otherStudent,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $match = RoommateMatch::with(['studentOne.studentProfile', 'studentTwo.studentProfile'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('student_one_id', $user->id)
                    ->orWhere('student_two_id', $user->id);
            })
            ->firstOrFail();

        $otherStudent = $match->student_one_id === $user->id ? $match->studentTwo : $match->studentOne;

        return view('roommate_match.show', [
            'match' => $match,
            'otherStudent' => $otherStudent,
        ]);
    }

    public function endMatch(Request $request)
    {
        $data = $request->validate([
            'match_id' => ['required', 'integer', 'exists:roommate_matches,id'],
        ]);

        $user = $request->user();

        $match = RoommateMatch::active()
            ->where('id', $data['match_id'])
            ->where(function ($query) use ($user) {
                $query->where('student_one_id', $user->id)
                    ->orWhere('student_two_id', $user->id);
            })
            ->firstOrFail();

        $match->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        $otherUser = $match->student_one_id === $user->id ? $match->studentTwo : $match->studentOne;
        $otherUser->notify(new \App\Notifications\RoommateRemovedNotification($match, $user->name));

        return back()->with('status', 'Roommate match ended successfully.');
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $matches = RoommateMatch::with(['studentOne.studentProfile', 'studentTwo.studentProfile'])
            ->forUser($user->id)
            ->orderByDesc('matched_at')
            ->paginate(10);

        return view('roommate_match.history', [
            'matches' => $matches,
        ]);
    }
}
