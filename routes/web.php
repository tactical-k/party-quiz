<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QuizMasterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QuestionController;
require __DIR__.'/auth.php';

// ログイン
Route::get('/admin', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('admin');

Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  //アカウント設定まわり
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  //管理者機能まわり
  // イベントCRUD
  Route::resource('events', EventController::class);
  // 問題CRUD
  Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
  Route::put('/questions/{question_id}', [QuestionController::class, 'update'])->name('questions.update');
  Route::delete('/questions/{question_id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
  // クイズ主催機能
  Route::get('/setSampleQuestion', [QuizMasterController::class, 'setSampleQuestion'])->name('set-sample-question');
  Route::get('/quizMaster/{event_id}', [QuizMasterController::class, 'quizMaster'])->name('quiz-master');
  Route::post('/submitQuestion/{question_id}', [QuizMasterController::class, 'submitQuestion'])->name('submit-question');
});

// 参加者機能まわり
Route::get('/', function () {
    return Inertia::render('Start');
})->name('start');

Route::get('/quiz', function () {
    return Inertia::render('Quiz');
})->name('quiz');


