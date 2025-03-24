<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Projects;
use App\Livewire\About;
use App\Livewire\Technologies;
use App\Livewire\Contact;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



                // web pages
                Route::get('/projects', Projects::class)->name('projects');
                Route::get('/technologies', Technologies::class)->name('technologies');
                Route::get('/contact', Contact::class)->name('contact');
                Route::get('/about', About::class)->name('about');







Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
