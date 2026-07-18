<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentPreference;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'user_type' => 'student',
        ]);
        StudentProfile::factory()->create(['user_id' => $this->user->id]);
        StudentPreference::factory()->create(['user_id' => $this->user->id]);

        $this->otherUser = User::factory()->create([
            'email' => 'other@example.com',
            'user_type' => 'student',
        ]);
        StudentProfile::factory()->create(['user_id' => $this->otherUser->id]);
        StudentPreference::factory()->create(['user_id' => $this->otherUser->id]);
    }

    public function test_guests_receive_json_unauthorized_response()
    {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized',
        ]);
    }

    public function test_authenticated_user_can_retrieve_profile()
    {
        $response = $this->actingAs($this->user)->getJson('/api/profile');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.email', 'user@example.com');
    }

    public function test_can_retrieve_users_list()
    {
        $response = $this->actingAs($this->user)->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_retrieve_roommate_matches()
    {
        $response = $this->actingAs($this->user)->getJson('/api/matches');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_can_retrieve_messages()
    {
        $conversation = Conversation::create([
            'user_one_id' => min($this->user->id, $this->otherUser->id),
            'user_two_id' => max($this->user->id, $this->otherUser->id),
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $this->user->id,
            'message' => 'Hello test roommate',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/messages');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.message', 'Hello test roommate');
    }

    public function test_can_search_users_by_name()
    {
        $response = $this->actingAs($this->user)->getJson('/api/search?name=' . $this->otherUser->name);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', $this->otherUser->name);
    }

    public function test_can_update_profile_via_api()
    {
        $payload = [
            'student_id' => '99999999',
            'department' => 'CSE',
            'batch' => '2024',
            'hall' => 'Lalon Shah Hall',
            'phone' => '01800000000',
            'gender' => 'male',
            'bio' => 'New API Bio content',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/profile/update', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);

        $this->assertDatabaseHas('student_profiles', [
            'user_id' => $this->user->id,
            'student_id' => '99999999',
            'bio' => 'New API Bio content',
        ]);
    }

    public function test_profile_update_validation_errors()
    {
        $payload = [
            'student_id' => '',
            'department' => '',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/profile/update', $payload);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
        $response->assertJsonStructure(['success', 'message', 'errors']);
    }
}
