<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');
Route::get('/about-us', fn () => Inertia::render('About/Index'))->name('about');
Route::get('/treatments/{treatment}', [TreatmentController::class, 'show'])->name('treatments.show');
