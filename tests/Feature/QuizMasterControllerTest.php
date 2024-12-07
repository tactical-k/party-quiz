<?php

namespace Tests\Feature;

use App\Models\RespondentsAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Question;
use App\Models\Choice;

class QuizMasterControllerTest extends TestCase
{
    use RefreshDatabase;

    private Event $event;
    private Question $question;
    private Question $question_2;

    protected function setUp(): void
    {
        parent::setUp();
        // イベントを作成
        $this->event = Event::factory()->create();
        // 質問を作成
        $this->question = Question::factory()->create([
            'event_id' => $this->event->uuid,
        ]);
        $this->question_2 = Question::factory()->create([
            'event_id' => $this->event->uuid,
        ]);
        // 正解の選択肢を作成
        Choice::factory()->create([
            'question_id' => $this->question->id,
            'text' => '選択肢1',
            'is_correct' => true,
        ]);
        Choice::factory()->create([
            'question_id' => $this->question->id,
            'text' => '選択肢2',
            'is_correct' => false,
        ]);
        Choice::factory()->create([
            'question_id' => $this->question_2->id,
            'text' => '選択肢1',
            'is_correct' => true,
        ]);
        Choice::factory()->create([
            'question_id' => $this->question_2->id,
            'text' => '選択肢2',
            'is_correct' => false,
        ]);
    }

    public function test_summary_returns_correct_data()
    {
        // テスト用のデータを作成
        RespondentsAnswer::factory()->create([
            'name' => 'ユーザー1',
            'event_id' => $this->event->uuid,
            'question_id' => $this->question->id,
            'answer' => '選択肢1', // 正解
        ]);

        RespondentsAnswer::factory()->create([
            'name' => 'ユーザー2',
            'event_id' => $this->event->uuid,
            'question_id' => $this->question->id,
            'answer' => '選択肢1', // 正解
        ]);

        RespondentsAnswer::factory()->create([
            'name' => 'ユーザー1',
            'event_id' => $this->event->uuid,
            'question_id' => $this->question_2->id,
            'answer' => '選択肢2', // 不正解
        ]);

        // コントローラのメソッドを呼び出す
        $response = $this->get(route('summary', ['event_id' => $this->event->uuid]));

        // レスポンスの検証
        $response->assertStatus(200);
        $this->assertSame('Admin/QuizMaster/Summary', $response->getOriginalContent()->getData()['page']['component']);

        $this->assertSame('ユーザー1', $response->getOriginalContent()->getData()['page']['props']['summary'][0]['name']);
        $this->assertSame(1, $response->getOriginalContent()->getData()['page']['props']['summary'][0]['correct_count']);
        $this->assertSame('ユーザー2', $response->getOriginalContent()->getData()['page']['props']['summary'][1]['name']);
        $this->assertSame(1, $response->getOriginalContent()->getData()['page']['props']['summary'][1]['correct_count']);
    }
}