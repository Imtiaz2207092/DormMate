<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MessageImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message_with_image()
    {
        Storage::fake('public');

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::create([
            'user_one_id' => $user1->id,
            'user_two_id' => $user2->id,
        ]);

        // Use UploadedFile::fake()->create instead of image() to avoid GD extension dependency
        $file = UploadedFile::fake()->create('chat_image.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user1)->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'message' => 'Hello check out this pic',
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'image', 'created_at', 'sender_initial']);

        $message = Message::first();
        $this->assertNotNull($message->image);
        Storage::disk('public')->assertExists($message->image);
    }

    public function test_user_can_send_image_only()
    {
        Storage::fake('public');

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::create([
            'user_one_id' => $user1->id,
            'user_two_id' => $user2->id,
        ]);

        // Use UploadedFile::fake()->create instead of image() to avoid GD extension dependency
        $file = UploadedFile::fake()->create('only_image.png', 100, 'image/png');

        $response = $this->actingAs($user1)->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'image' => $file,
        ]);

        $response->assertStatus(200);

        $message = Message::first();
        $this->assertNull($message->message);
        $this->assertNotNull($message->image);
        Storage::disk('public')->assertExists($message->image);
    }

    public function test_cannot_send_empty_message_without_image()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conversation = Conversation::create([
            'user_one_id' => $user1->id,
            'user_two_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->postJson(route('messages.send'), [
            'conversation_id' => $conversation->id,
            'message' => '',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Message::count());
    }
}
