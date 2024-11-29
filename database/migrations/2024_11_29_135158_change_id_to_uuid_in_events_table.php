<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeIdToUuidInEventsTable extends Migration
{
    public function up()
    {
        // 新しいテーブルを作成
        Schema::create('new_events', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUIDカラムを作成
            // 他のカラムもここに追加
            $table->string('name'); // 例: 他のカラムを追加
            $table->timestamps();
        });

        // 既存のデータを新しいテーブルに移行
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            DB::table('new_events')->insert([
                'id' => (string) Str::uuid(), // UUIDを生成
                'name' => $event->name, // 他のカラムも移行
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ]);
        }

        // 古いテーブルを削除
        Schema::dropIfExists('events');

        // 新しいテーブルの名前を変更
        Schema::rename('new_events', 'events');
    }

    public function down()
    {
        // 元のテーブルを復元するための処理
        Schema::create('old_events', function (Blueprint $table) {
            $table->bigIncrements('id'); // 元のIDカラムを復元
            // 他のカラムもここに追加
            $table->string('name'); // 例: 他のカラムを追加
            $table->timestamps();
        });

        // 既存のデータを元のテーブルに移行
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            DB::table('old_events')->insert([
                'id' => $event->id, // 元のIDを使用
                'name' => $event->name, // 他のカラムも移行
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ]);
        }

        // 新しいテーブルを削除
        Schema::dropIfExists('events');

        // 古いテーブルの名前を変更
        Schema::rename('old_events', 'events');
    }
}
