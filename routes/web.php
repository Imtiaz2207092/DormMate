<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompatibilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentPreferenceController;
use App\Http\Controllers\StudentSearchController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Student profile management
Route::middleware('auth')->group(function () {
    Route::get('profile', [StudentProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/create', [StudentProfileController::class, 'create'])->name('profile.create');
    Route::post('profile', [StudentProfileController::class, 'store'])->name('profile.store');
    Route::get('profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [StudentProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('preferences', [StudentPreferenceController::class, 'index'])->name('preferences.index');
    Route::get('preferences/create', [StudentPreferenceController::class, 'create'])->name('preferences.create');
    Route::post('preferences', [StudentPreferenceController::class, 'store'])->name('preferences.store');
    Route::get('preferences/show', [StudentPreferenceController::class, 'show'])->name('preferences.show');
    Route::get('preferences/edit', [StudentPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::put('preferences', [StudentPreferenceController::class, 'update'])->name('preferences.update');

    Route::get('students', [StudentSearchController::class, 'index'])->name('students.index');
    Route::get('students/{id}', [StudentSearchController::class, 'show'])->name('students.show');

    Route::get('compatibility', [CompatibilityController::class, 'index'])->name('compatibility.index');
    Route::get('compatibility/{id}', [CompatibilityController::class, 'show'])->name('compatibility.show');
});

