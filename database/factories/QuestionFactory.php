<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'event_id' => $this->faker->uuid,
            'text' => $this->faker->sentence,
            'type' => 'multiple_choice',
            'created_at' => $this->faker->dateTime,
        ];
    }
}

