<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home/Index'))->name('home');
Route::get('/about-us', fn () => Inertia::render('About/Index'))->name('about');
