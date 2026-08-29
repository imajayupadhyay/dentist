<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\TreatmentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->name('admin.')->group(function (): void {
    Route::get('/drpushpa-secure-login', [LoginController::class, 'create'])->name('login');
    Route::post('/drpushpa-secure-login', [LoginController::class, 'store'])->name('login.store');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', fn () => to_route('admin.dashboard'))->name('index');
        Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard/Index'))->name('dashboard');
        Route::resource('/treatments', TreatmentController::class)->except('show');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});
