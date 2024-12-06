<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('respondents_answers')) {
            Schema::create('respondents_answers', function (Blueprint $table) {
                $table->id();
                $table->uuid('event_id');
                $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
                $table->string('name');
                $table->string('answer');
                $table->timestamps();

                $table->foreign('event_id')->references('uuid')->on('events')->onDelete('cascade');

                $table->unique(['event_id', 'question_id', 'name'], 'respondents_answers_event_question_name_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondents_answers');
    }
};
