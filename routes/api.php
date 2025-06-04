<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/comments/{task}', [CommentController::class, 'index'])->name('get.comments');
Route::post('/comments/{task}', [CommentController::class, 'store'])->name('store.comments');
Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::post('/task/update/{task}', [TaskController::class, 'update'])->name('tasks.update.api');
Route::post('/task/estimate/{task}', [TaskController::class, 'estimate'])->name('tasks.estimate.api');