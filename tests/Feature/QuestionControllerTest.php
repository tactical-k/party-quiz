<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $event;

    protected function setUp(): void
    {
        parent::setUp();
        // テスト用のイベントを作成
        $this->event = Event::factory()->create();
        $user = User::factory()->create(); // ユーザーを作成
        $this->actingAs($user); // ユーザーとしてログイン
    }

    /** @test */
    public function it_can_store_a_new_question()
    {
        $data = [
            'event_id' => $this->event->uuid,
            'text' => 'Sample Question',
            'choices' => [
                ['text' => 'Choice 1', 'is_correct' => true],
                ['text' => 'Choice 2', 'is_correct' => false],
            ],
        ];

        $response = $this->post(route('questions.store'), $data);

        $response->assertRedirect(route('events.show', ['event' => $this->event->uuid]));
        $this->assertDatabaseHas('questions', ['text' => 'Sample Question']);
        $this->assertDatabaseHas('choices', ['text' => 'Choice 1']);
    }

    /** @test */
    public function it_can_update_an_existing_question()
    {
        $question = Question::factory()->create(['event_id' => $this->event->uuid]);

        $data = [
            'event_id' => $this->event->uuid,
            'text' => 'Updated Question',
            'choices' => [
                ['text' => 'Updated Choice 1', 'is_correct' => true],
                ['text' => 'Updated Choice 2', 'is_correct' => false],
            ],
        ];

        $response = $this->put(route('questions.update', $question->id), $data);

        $response->assertRedirect(route('events.show', ['event' => $this->event->uuid]));
        $this->assertDatabaseHas('questions', ['text' => 'Updated Question']);
        $this->assertDatabaseHas('choices', ['text' => 'Updated Choice 1']);
    }

    /** @test */
    public function it_can_destroy_a_question()
    {
        $question = Question::factory()->create(['event_id' => $this->event->uuid]);

        $response = $this->delete(route('questions.destroy', $question->id));

        $response->assertRedirect(route('events.show', ['event' => $this->event->uuid]));
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function it_validates_question_store_request()
    {
        $data = [
            'event_id' => $this->event->uuid,
            'text' => '',
            'choices' => [
                ['text' => 'Choice 1', 'is_correct' => true],
            ],
        ];

        $response = $this->post(route('questions.store'), $data);

        $response->assertSessionHasErrors(['text']);
    }

    /** @test */
    public function it_validates_question_update_request()
    {
        $question = Question::factory()->create(['event_id' => $this->event->uuid]);

        $data = [
            'event_id' => $this->event->uuid,
            'text' => '',
            'choices' => [
                ['text' => 'Choice 1', 'is_correct' => true],
            ],
        ];

        $response = $this->put(route('questions.update', $question->id), $data);

        $response->assertSessionHasErrors(['text']);
    }
}