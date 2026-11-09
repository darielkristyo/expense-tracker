<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'application' => config('app.name'),
    ], 200);
});

Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

// Simple JSON API endpoints required by the assignment.
Route::get('/api/expenses', [ExpenseController::class, 'apiIndex']);
Route::post('/api/expenses', [ExpenseController::class, 'apiStore']);
