<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    public function setQuestion(): JsonResponse
{
    $firebase = (new Factory)->withServiceAccount(__DIR__.'/../../../firebase_credentials.json');
    $database = $firebase->withDatabaseUri(env('VITE_FIREBASE_DATABASE_URL'))->createDatabase();
    $database->getReference('currentQuestion')->set([
        'text' => '3 + 2 は何ですか？',         
        'choices' => ['3', '4', '5', '6']
    ]);

    return response()->json(['status' => 'success']);
}
}
