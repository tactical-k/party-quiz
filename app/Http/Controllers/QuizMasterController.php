<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Event;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Models\Question;
use App\Services\PostFirebaseRealTimeDatabaseService;
use Illuminate\Support\Facades\Log;

class QuizMasterController extends Controller
{
    protected PostFirebaseRealTimeDatabaseService $firebaseService;

    public function __construct(PostFirebaseRealTimeDatabaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function quizMaster(string $event_id): InertiaResponse
    {
        $event = Event::with('questions')->where('uuid', $event_id)->firstOrFail();
        return Inertia::render('Admin/QuizMaster/Dashboard', ['event' => $event]);
    }

    public function submitQuestion(string $question_id): JsonResponse
    {
        // firebaseに質問を同期する
        $syncResult = $this->firebaseService->syncQuestion($question_id);
        if ($syncResult['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $syncResult['message']], 500);
        }

        // 質問のis_submittedをtrueにする
        try {
            Question::where('id', $question_id)->update(['is_submitted' => true]);
        } catch (\Exception $e) {
            // エラーが発生しても、firebaseに質問が同期されているため成功として返す
            Log::error($e->getMessage());
        }

        return response()->json(['status' => 'success']);
    }

    public function clearQuestion(string $event_id): JsonResponse
    {
        $event = Event::where('uuid', $event_id)->firstOrFail();

        // N+1問題を回避するために、全ての質問を一度に更新
        $event->questions()->update(['is_submitted' => false]);

        // firebaseの質問をクリアする
        $clearResult = $this->firebaseService->clearQuestion($event->uuid);
        if ($clearResult['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $clearResult['message']], 500);
        }
        return response()->json(['status' => 'success']);
    }
}
