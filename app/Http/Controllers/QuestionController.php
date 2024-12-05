<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Question;

class QuestionController extends Controller
{
    // 新しい問題を作成
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'event_id' => 'required|string|max:255',
            'text' => 'required|string|max:255',
            'choices' => 'required|array|min:2|max:5', // 選択肢の配列をバリデート（2つ以上、最大5つ）
            'choices.*.text' => 'required|string|max:255', // 各選択肢のテキストをバリデート
            'choices.*.is_correct' => 'required|boolean', // 各選択肢の正誤をバリデート
        ]);

        // 正解の選択肢が1つ以上存在することを確認
        $correctChoices = collect($request->choices)->where('is_correct', true);
        if ($correctChoices->isEmpty()) {
            return redirect()->back()->withErrors(['choices' => '少なくとも1つの選択肢は正解である必要があります。'])->withInput();
        }

        // 新しい問題を作成
        $question = Question::create([
            'event_id' => $request->event_id,
            'text' => $request->text,
            'type' => 'multiple_choice', // 一旦固定値
        ]);

        // 選択肢を保存
        foreach ($request->choices as $choice) {
            $question->choices()->create($choice);
        }

        return redirect()->route('events.show', ['event' => $request->event_id])->with('success', '問題を登録しました。');
    }

    // 問題を更新（削除して新規作成）
    public function update(Request $request, string $id): RedirectResponse
    {
        // バリデーション
        $request->validate([
            'event_id' => 'required|string|max:255',
            'text' => 'required|string|max:255',
            'choices' => 'required|array|min:2|max:5',
            'choices.*.text' => 'required|string|max:255',
            'choices.*.is_correct' => 'required|boolean',
        ]);

        // 問題は更新。選択肢は削除して新規作成。 → 問題の表示する順番を維持するため
        // 既存の問題を取得
        $question = Question::with('choices')->find($id);
        if ($question) {
            // 既存の選択肢を削除
            $question->choices()->delete();
        }

        // 問題を更新
        $question->update($request->only('event_id', 'text'));

        // 新しい選択肢を作成
        foreach ($request->choices as $choice) {
            $question->choices()->create($choice);
        }

        return redirect()->route('events.show', $question->event_id)->with('success', '問題を更新しました。');
    }

    // 問題を削除
    public function destroy(string $id): RedirectResponse
    {
        $question = Question::with('choices')->find($id);
        $question->choices()->delete();
        $question->delete();

        return redirect()->route('events.show', ['event' => $question->event_id])->with('success', '問題を削除しました。');
    }
}
