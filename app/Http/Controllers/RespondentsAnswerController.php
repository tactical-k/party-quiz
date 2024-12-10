<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RespondentsAnswer;
class RespondentsAnswerController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'answer' => 'required|string',
            'event_id' => 'required|uuid',
            'question_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        // 既存のレコードを検索
        $respondentAnswer = RespondentsAnswer::where('event_id', $validatedData['event_id'])
            ->where('question_id', $validatedData['question_id'])
            ->where('name', $validatedData['name'])
            ->first();

        if ($respondentAnswer) {
            // レコードが存在する場合は回答済みとして終了
            return response()->json(['status' => 'already_answered']);
        } else {
            // レコードが存在しない場合は新規作成
            RespondentsAnswer::create($validatedData);
        }
        

        return response()->json(['status' => 'success']);
    }
}
