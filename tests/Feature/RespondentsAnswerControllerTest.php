<?php

namespace Tests\Feature;

use App\Models\RespondentsAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Question;

class RespondentsAnswerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $question;
    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::factory()->create([
            'uuid' => '2c94f36c-0b43-4b8e-8c1e-1f1e1e1e1e1e',
        ]);
        $this->question = Question::factory()->create([
            'event_id' => $this->event->uuid,
        ]);
    }

    public function test_store_creates_new_respondents_answer()
    {
        $data = [
            'event_id' => $this->event->uuid,
            'question_id' => $this->question->id,
            'name' => 'John Doe',
            'answer' => 'This is an answer.',
        ];

        $response = $this->postJson('/events/' . $this->event->uuid . '/quiz', $data);

        $response->assertStatus(JsonResponse::HTTP_OK);
        $this->assertDatabaseHas('respondents_answers', $data);
    }

    public function test_store_updates_existing_respondents_answer()
    {
        $existingAnswer = RespondentsAnswer::create([
            'event_id' => $this->event->uuid,
            'question_id' => $this->question->id,
            'name' => 'John Doe',
            'answer' => 'Old answer.',
        ]);

        $data = [
            'event_id' => $existingAnswer->event_id,
            'question_id' => $existingAnswer->question_id,
            'name' => $existingAnswer->name,
            'answer' => 'Updated answer.',
        ];

        $response = $this->postJson('/events/' . $this->event->uuid . '/quiz', $data);

        $response->assertStatus(JsonResponse::HTTP_OK);
        $this->assertDatabaseHas('respondents_answers', [
            'id' => $existingAnswer->id,
            'answer' => 'Updated answer.',
        ]);
    }
} 