<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });



Route::get('/dashboard', [ProjectController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
   Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');


Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

// web.php
Route::post('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

Route::middleware('auth')->post('/tasks/{task}/comments', [CommentController::class, 'store']);




});

require __DIR__.'/auth.php';
