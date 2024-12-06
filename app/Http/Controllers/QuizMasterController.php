<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use App\Models\Event;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Models\Question;
use App\Services\PostFirebaseRealTimeDatabaseService;
use Illuminate\Support\Facades\Log;

class QuizMasterController extends Controller
{
    protected $firebaseService;

    public function __construct(PostFirebaseRealTimeDatabaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    // サンプル問題をセットする
    // public function setSampleQuestion(): JsonResponse
    // {
    //     $this->firebaseService->database->getReference('currentQuestion')->set([
    //         'text' => '3 + 2 は何ですか？',
    //         'choices' => ['3', '4', '5', '6']
    //     ]);

    //     return response()->json(['status' => 'success']);
    // }
    
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
}
