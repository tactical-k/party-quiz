<?php

namespace Database\Factories;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoiceFactory extends Factory
{
    protected $model = Choice::class;

    public function definition()
    {
        return [
            'question_id' => Question::factory(), // 新しい質問を作成し、そのIDを設定
            'text' => $this->faker->sentence(3), // ランダムなテキストを生成
            'is_correct' => $this->faker->boolean(), // ランダムに真偽値を生成
        ];
    }
}
