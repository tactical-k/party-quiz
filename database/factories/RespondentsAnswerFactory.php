<?php

namespace Database\Factories;

use App\Models\RespondentsAnswer;
use App\Models\Event;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespondentsAnswerFactory extends Factory
{
    protected $model = RespondentsAnswer::class;

    public function definition()
    {
        return [
            'event_id' => Event::factory(), // 新しいイベントを作成し、そのIDを設定
            'question_id' => Question::factory(), // 新しい質問を作成し、そのIDを設定
            'name' => $this->faker->name(), // ランダムな名前を生成
            'answer' => $this->faker->sentence(3), // ランダムな回答を生成
        ];
    }
}
