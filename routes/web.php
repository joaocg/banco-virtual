<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionReversalController;

use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/deposit', function () {
        return view('deposit');
    })->name('deposit.form');

    Route::get('/transfer', function () {
        return view('transfer');
    })->name('transfer.form');


    Route::get('/account-data', [AccountController::class, 'getAccountData'])
        ->name('account.data');

    Route::post('/deposit', [TransactionController::class, 'deposit'])
        ->name('deposit');
    Route::post('/transfer', [TransactionController::class, 'transfer'])
        ->name('transfer');
    Route::post('/transaction/reverse/{transactionId}', [TransactionReversalController::class, 'reverse']);
});



require __DIR__.'/auth.php';
