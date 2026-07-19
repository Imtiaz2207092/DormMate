<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RoommateMatch;
use App\Models\RoommateRequest;
use App\Models\StudentProfile;
use App\Models\StudentPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoommateRequestLimitTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $recipient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['user_type' => 'student']);
        StudentProfile::factory()->create(['user_id' => $this->user->id]);
        StudentPreference::factory()->create(['user_id' => $this->user->id]);

        $this->recipient = User::factory()->create(['user_type' => 'student']);
        StudentProfile::factory()->create(['user_id' => $this->recipient->id]);
        StudentPreference::factory()->create(['user_id' => $this->recipient->id]);
    }

    public function test_can_send_request_when_below_limit()
    {
        $response = $this->actingAs($this->user)->post('/roommate-request/send', [
            'receiver_id' => $this->recipient->id,
            'message' => 'Hey, let us match!'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Roommate request sent successfully.');
    }

    public function test_cannot_send_request_when_sender_reaches_limit()
    {
        $other = User::factory()->create(['user_type' => 'student']);
        RoommateMatch::create([
            'student_one_id' => $this->user->id,
            'student_two_id' => $other->id,
            'compatibility_score' => 85,
            'matched_at' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->user)->post('/roommate-request/send', [
            'receiver_id' => $this->recipient->id,
            'message' => 'Want to match?'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'You have already found a roommate.');
    }

    public function test_cannot_send_request_when_recipient_reaches_limit()
    {
        $other = User::factory()->create(['user_type' => 'student']);
        RoommateMatch::create([
            'student_one_id' => $this->recipient->id,
            'student_two_id' => $other->id,
            'compatibility_score' => 85,
            'matched_at' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->user)->post('/roommate-request/send', [
            'receiver_id' => $this->recipient->id,
            'message' => 'Want to match?'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'This student has already found a roommate.');
    }

    public function test_cannot_accept_request_when_limit_is_reached()
    {
        $request = RoommateRequest::create([
            'sender_id' => $this->recipient->id,
            'receiver_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $other = User::factory()->create(['user_type' => 'student']);
        RoommateMatch::create([
            'student_one_id' => $this->user->id,
            'student_two_id' => $other->id,
            'compatibility_score' => 85,
            'matched_at' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->user)->post("/roommate-request/{$request->id}/accept");

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'You already have an active roommate match.');
    }
}
