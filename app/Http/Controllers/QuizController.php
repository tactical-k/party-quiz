<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Kreait\Firebase\Factory;

class QuizController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount([
            'type' => env('FIREBASE_TYPE'),
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
            'private_key' => str_replace('\\n', "\n", env('FIREBASE_PRIVATE_KEY')),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'auth_uri' => env('FIREBASE_AUTH_URI'),
            'token_uri' => env('FIREBASE_TOKEN_URI'),
            'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_CERT_URL'),
            'client_x509_cert_url' => env('FIREBASE_CLIENT_CERT_URL'),
            'universe_domain' => env('FIREBASE_UNIVERSE_DOMAIN'),
        ]);
        
        $this->database = $firebase->withDatabaseUri(env('VITE_FIREBASE_DATABASE_URL'))->createDatabase();
    }

    // サンプル問題をセットする
    public function setSampleQuestion(): JsonResponse
    {
        $this->database->getReference('currentQuestion')->set([
            'text' => '3 + 2 は何ですか？',
            'choices' => ['3', '4', '5', '6']
        ]);

        return response()->json(['status' => 'success']);
    }
}
