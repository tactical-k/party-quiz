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
        if (Schema::hasTable('users') && !Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('主催者ユーザーID');
                $table->string('name')->comment('イベント名');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
