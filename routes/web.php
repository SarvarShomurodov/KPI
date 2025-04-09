<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\ClientTaskController;
use App\Http\Controllers\TaskAssignmentController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth', 'role:Super Admin|Admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
        'subtasks' => SubTaskController::class,
        'task_assignments'=> TaskAssignmentController::class
    ]);
});
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/tasks', [ClientTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{taskId}', [ClientTaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{taskId}/assign/{staffId}', [ClientTaskController::class, 'assignTask'])->name('tasks.assign');
    Route::post('/tasks/{taskId}/assign/{staffId}', [ClientTaskController::class, 'storeRating'])->name('tasks.storeRating');
});