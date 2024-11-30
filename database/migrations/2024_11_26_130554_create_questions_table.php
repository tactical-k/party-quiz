<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id'); //イベントID外部キー
            $table->string('text')->comment('質問文');
            $table->enum('type', ['multiple_choice', 'text_input'])->comment('質問の種類');
            $table->timestamps();

            $table->foreign('event_id')->references('uuid')->on('events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
