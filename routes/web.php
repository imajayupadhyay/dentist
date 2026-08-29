<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/contact-submissions', [ContactSubmissionController::class, 'store'])->name('contact-submissions.store');
Route::get('/about-us', AboutController::class)->name('about');
Route::get('/treatments/{treatment}', [TreatmentController::class, 'show'])->name('treatments.show');
