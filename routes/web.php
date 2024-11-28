<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
Route::inertia('/', 'Welcome');
Route::inertia('/quiz', 'Quiz');
Route::get('/set-question', [QuizController::class, 'setQuestion']);
