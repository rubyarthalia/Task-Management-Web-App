<?php
 
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', fn() => redirect()->route('tasks.index'));
 
Route::resource('tasks', TaskController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
 
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
    ->name('tasks.toggle');