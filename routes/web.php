<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
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

    Route::get('/gantt', function () {
    $tasks = [
        [
            'id' => 'Task1',
            'name' => 'Perencanaan',
            'start' => '2025-06-01',
            'end' => '2025-06-05',
            'progress' => 20,
        ],
        [
            'id' => 'Task2',
            'name' => 'Pengembangan',
            'start' => '2025-06-06',
            'end' => '2025-06-15',
            'progress' => 10,
            'dependencies' => 'Task1'
        ],
    ];

    return view('projects.gantt', compact('tasks'));
});

Route::middleware('auth')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index2'])->name('projects.index2');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects_list/{project}', [ProjectController::class, 'list'])->name('projects.list');


Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/task/delete/{project}/{task}', [TaskController::class, 'delete'])->name('task.delete');
Route::post('/tasks/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
// web.php
Route::post('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

Route::middleware('auth')->post('/tasks/{task}/comments', [CommentController::class, 'store']);
});

require __DIR__.'/auth.php';
