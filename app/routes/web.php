<?php

use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/tarefas', [TarefaController::class, 'index'])->middleware(['auth', 'verified']);
Route::patch('/tarefas/{tarefa}/status', [TarefaController::class, 'updateStatus'])->middleware(['auth', 'verified']);
Route::post('/tarefas', [TarefaController::class, 'store'])->middleware(['auth', 'verified']);
Route::get('/tarefas/option_users', [TarefaController::class, 'getOptionUsers'])->middleware(['auth', 'verified']);
Route::delete('/tarefas/delete/{id}', [TarefaController::class, 'deletar'])->middleware(['auth', 'verified']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';