<?php

namespace Tests\Feature;

use App\Models\CompatibilityExplanation;
use App\Models\StudentPreference;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AICompatibilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        StudentProfile::factory()->create(['user_id' => $this->user->id]);
        StudentPreference::factory()->create(['user_id' => $this->user->id]);

        $this->otherUser = User::factory()->create();
        StudentProfile::factory()->create(['user_id' => $this->otherUser->id]);
        StudentPreference::factory()->create(['user_id' => $this->otherUser->id]);
    }

    public function test_route_requires_authentication(): void
    {
        $response = $this->postJson(route('students.explain-compatibility', $this->otherUser->id));
        $response->assertStatus(401);
    }

    public function test_it_returns_error_for_self_matching(): void
    {
        $this->actingAs($this->user);
        $response = $this->postJson(route('students.explain-compatibility', $this->user->id));

        $response->assertStatus(422);
        $response->assertJsonPath('error', 'You cannot match with yourself.');
    }

    public function test_it_returns_explanation_via_gemini_api_and_caches_it(): void
    {
        $this->actingAs($this->user);

        // Fake Gemini API response
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'summary' => 'This is a friendly AI summary.',
                                        'strengths' => ['Strong compatibility', 'Quiet study habits'],
                                        'differences' => ['Varying sleep schedules'],
                                        'suggestions' => ['Set quiet hours'],
                                        'recommendation' => 'Recommended'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson(route('students.explain-compatibility', $this->otherUser->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'summary',
            'strengths',
            'differences',
            'suggestions',
            'recommendation'
        ]);

        // Check if database contains caching record
        $this->assertDatabaseHas('compatibility_explanations', [
            'user_id' => $this->user->id,
            'matched_user_id' => $this->otherUser->id,
        ]);
    }

    public function test_it_reuses_cached_explanation_if_score_is_unchanged(): void
    {
        $this->actingAs($this->user);

        // Seed an existing explanation
        $mockExplanation = [
            'summary' => 'Seeded summary cache.',
            'strengths' => ['Seeded strength'],
            'differences' => ['Seeded difference'],
            'suggestions' => ['Seeded suggestion'],
            'recommendation' => 'Highly Recommended'
        ];

        $score = app(\App\Services\CompatibilityService::class)->calculateScore($this->user, $this->otherUser);

        CompatibilityExplanation::create([
            'user_id' => $this->user->id,
            'matched_user_id' => $this->otherUser->id,
            'compatibility_score' => $score,
            'ai_explanation' => $mockExplanation
        ]);

        // Ensure no external HTTP requests are made
        Http::preventStrayRequests();

        $response = $this->postJson(route('students.explain-compatibility', $this->otherUser->id));

        $response->assertStatus(200);
        $response->assertExactJson($mockExplanation);
    }

    public function test_dashboard_contains_best_match_and_ai_advisor_card(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('bestMatch');
        $response->assertSee('AI Roommate Advisor');
        $response->assertSee('✨ Analyze My Best Match');
    }
}
