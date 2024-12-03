<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
class EventController extends Controller
{
    // イベントの一覧を取得
    public function index(): InertiaResponse
    {
        $events = Event::with('user')->get();
        return Inertia::render('Admin/Events/Index', ['events' => $events]);
    }

    // 新しいイベントを作成するためのフォームを表示
    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Events/Create'); // イベント作成用のビューを表示
    }

    // 新しいイベントを作成
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // ログイン中のユーザーのIDを取得
        $userId = $request->user()->id;

        try {
            // 新しいイベントを作成
            Event::create([
                'name' => $request->name,
                'user_id' => $userId,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }

        // 成功時のレスポンス
        return redirect()->route('events.index')->with('success', 'イベントを登録しました。');
    }

    // 特定のイベントを取得
    public function show(string $uuid): InertiaResponse
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        return Inertia::render('Admin/Events/Show', ['event' => $event]);
    }

    public function edit(string $uuid): InertiaResponse
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        return Inertia::render('Admin/Events/Edit', ['event' => $event]);
    }

    // 特定のイベントを更新
    public function update(Request $request, string $uuid): RedirectResponse
    {
        $userId = $request->user()->id;
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        Event::where('uuid', $uuid)->update(
            [
                'name' => $request->name,
                'user_id' => $userId,
            ]
        );
        return redirect()->route('events.index')->with('success', 'イベントを更新しました。');
    }

    // 特定のイベントを削除
    public function destroy(string $uuid): RedirectResponse
    {
        Event::query()->where('uuid', $uuid)->delete();

        return redirect()->route('events.index')->with('success', 'イベントを削除しました。');
    }
}
