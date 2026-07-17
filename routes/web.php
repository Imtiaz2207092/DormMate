<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompatibilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoommateMatchController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentPreferenceController;
use App\Http\Controllers\StudentSearchController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('users', [AdminController::class, 'users'])->name('users.index');
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

    Route::get('roommate-requests', [\App\Http\Controllers\RoommateRequestController::class, 'index'])->name('roommate-requests.index');
    Route::get('roommate-request/history', [\App\Http\Controllers\RoommateRequestController::class, 'history'])->name('roommate-requests.history');
    Route::post('roommate-request/send', [\App\Http\Controllers\RoommateRequestController::class, 'send'])->name('roommate-requests.send');
    Route::post('roommate-request/{id}/accept', [\App\Http\Controllers\RoommateRequestController::class, 'accept'])->name('roommate-requests.accept');
    Route::post('roommate-request/{id}/reject', [\App\Http\Controllers\RoommateRequestController::class, 'reject'])->name('roommate-requests.reject');
    Route::post('roommate-request/{id}/cancel', [\App\Http\Controllers\RoommateRequestController::class, 'cancel'])->name('roommate-requests.cancel');

    Route::get('roommate-match', [RoommateMatchController::class, 'index'])->name('roommate-match.index');
    Route::get('roommate-match/history', [RoommateMatchController::class, 'history'])->name('roommate-match.history');
    Route::get('roommate-match/{id}', [RoommateMatchController::class, 'show'])->whereNumber('id')->name('roommate-match.show');
    Route::post('roommate-match/end', [RoommateMatchController::class, 'endMatch'])->name('roommate-match.end');

    Route::get('compatibility', [CompatibilityController::class, 'index'])->name('compatibility.index');
    Route::get('compatibility/{id}', [CompatibilityController::class, 'show'])->name('compatibility.show');

    // Messaging
    Route::get('messages', [\App\Http\Controllers\ConversationController::class, 'index'])->name('messages.index');
    Route::get('messages/{id}', [\App\Http\Controllers\ConversationController::class, 'show'])->name('messages.show');
    Route::post('messages/open', [\App\Http\Controllers\ConversationController::class, 'createOrOpen'])->name('messages.open');

    Route::post('messages/send', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.send');
    Route::post('messages/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');

    Route::get('notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{id}/redirect', [\App\Http\Controllers\NotificationController::class, 'redirect'])->name('notifications.redirect');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('students/{id}/explain-compatibility', [\App\Http\Controllers\AICompatibilityController::class, 'explain'])->name('students.explain-compatibility');
});

