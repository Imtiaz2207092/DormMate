<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\StudentProfile;
use App\Models\RoommateRequest;
use App\Models\RoommateMatch;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiController extends Controller
{
    public function users()
    {
        $users = User::with('studentProfile')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'department' => optional($user->studentProfile)->department,
                'hall' => optional($user->studentProfile)->hall,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(['studentProfile', 'studentPreference']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'student_profile' => $user->studentProfile,
                'student_preference' => $user->studentPreference,
            ]
        ]);
    }

    public function matches(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $matches = collect();

        if ($user->studentProfile && $user->studentPreference) {
            $matches = $compatibility->getBestMatches($user, 10);
        } else {
            $matches = User::with('studentProfile')
                ->where('id', '!=', $user->id)
                ->whereHas('studentProfile')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($u) {
                    $u->compatibility_score = 0;
                    return $u;
                });
        }

        $requestedIds = RoommateRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->toArray();

        $incomingPendingIds = RoommateRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->pluck('sender_id')
            ->toArray();

        $matchedIds = RoommateMatch::active()
            ->forUser($user->id)
            ->get()
            ->flatMap(fn($match) => [$match->student_one_id, $match->student_two_id])
            ->unique()
            ->filter(fn($id) => $id !== $user->id)
            ->values()
            ->toArray();

        $data = $matches->map(function ($m) use ($requestedIds, $incomingPendingIds, $matchedIds) {
            $hasPending = in_array($m->id, $requestedIds) || in_array($m->id, $incomingPendingIds);
            $hasAccepted = in_array($m->id, $matchedIds);
            return [
                'id' => $m->id,
                'name' => $m->name,
                'compatibility_score' => $m->compatibility_score ?? 0,
                'department' => optional($m->studentProfile)->department,
                'hall' => optional($m->studentProfile)->hall,
                'has_pending' => $hasPending,
                'has_accepted' => $hasAccepted,
                'can_send' => !$hasPending && !$hasAccepted,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function messages(Request $request)
    {
        $user = $request->user();

        $messages = Message::with(['sender', 'conversation.userOne', 'conversation.userTwo'])
            ->whereHas('conversation', function ($q) use ($user) {
                $q->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
            })
            ->latest()
            ->get()
            ->map(function ($msg) use ($user) {
                $conversation = $msg->conversation;
                $receiver = $conversation->user_one_id === $msg->sender_id ? $conversation->userTwo : $conversation->userOne;
                return [
                    'sender' => [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                        'email' => $msg->sender->email,
                    ],
                    'receiver' => [
                        'id' => $receiver->id,
                        'name' => $receiver->name,
                        'email' => $receiver->email,
                    ],
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function search(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $q = $request->input('name') ?? $request->input('q');

        $query = User::with(['studentProfile', 'studentPreference'])
            ->where('id', '!=', $user->id)
            ->whereHas('studentProfile');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhereHas('studentProfile', function ($sub2) use ($q) {
                        $sub2->where('student_id', 'like', "%{$q}%")
                            ->orWhere('department', 'like', "%{$q}%")
                            ->orWhere('hall', 'like', "%{$q}%")
                            ->orWhere('batch', 'like', "%{$q}%");
                    });
            });
        }

        $requestedIds = RoommateRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->toArray();

        $incomingPendingIds = RoommateRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->pluck('sender_id')
            ->toArray();

        $matchedIds = RoommateMatch::active()
            ->forUser($user->id)
            ->get()
            ->flatMap(fn($match) => [$match->student_one_id, $match->student_two_id])
            ->unique()
            ->filter(fn($id) => $id !== $user->id)
            ->values()
            ->toArray();

        $users = $query->get()->map(function ($candidate) use ($user, $compatibility, $requestedIds, $incomingPendingIds, $matchedIds) {
            $candidate->compatibility_score = 0;
            if ($user->studentProfile && $user->studentPreference && $candidate->studentPreference) {
                $candidate->compatibility_score = $compatibility->calculateScore($user, $candidate);
            }
            $hasPending = in_array($candidate->id, $requestedIds) || in_array($candidate->id, $incomingPendingIds);
            $hasAccepted = in_array($candidate->id, $matchedIds);

            return [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'email' => $candidate->email,
                'compatibility_score' => $candidate->compatibility_score,
                'department' => optional($candidate->studentProfile)->department,
                'hall' => optional($candidate->studentProfile)->hall,
                'bio' => optional($candidate->studentProfile)->bio,
                'profile_photo' => optional($candidate->studentProfile)->profile_photo,
                'student_id' => optional($candidate->studentProfile)->student_id,
                'batch' => optional($candidate->studentProfile)->batch,
                'has_pending' => $hasPending,
                'has_accepted' => $hasAccepted,
                'can_send' => !$hasPending && !$hasAccepted,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->studentProfile;
        $profileId = $profile ? $profile->id : null;

        $validator = Validator::make($request->all(), [
            'student_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('student_profiles', 'student_id')->ignore($profileId),
            ],
            'department' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'max:100'],
            'hall' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female,other'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (!$profile) {
            $profile = new StudentProfile();
            $profile->user_id = $user->id;
        }

        $profile->student_id = $data['student_id'];
        $profile->department = $data['department'];
        $profile->batch = $data['batch'];
        $profile->hall = $data['hall'];
        $profile->phone = $data['phone'];
        $profile->gender = $data['gender'];
        $profile->bio = $data['bio'] ?? null;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.'
        ]);
    }
}
