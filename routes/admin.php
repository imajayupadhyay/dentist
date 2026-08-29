<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\HomePageController;
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
        Route::get('/home', [HomePageController::class, 'edit'])->name('home.edit');
        Route::put('/home', [HomePageController::class, 'update'])->name('home.update');
        Route::get('/about', [AboutPageController::class, 'edit'])->name('about.edit');
        Route::put('/about', [AboutPageController::class, 'update'])->name('about.update');
        Route::get('/contacts', [ContactSubmissionController::class, 'index'])->name('contacts.index');
        Route::patch('/contacts/{contactSubmission}', [ContactSubmissionController::class, 'update'])->name('contacts.update');
        Route::resource('/treatments', TreatmentController::class)->except('show');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});
