<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', function () {
    $plans = \App\Models\Plan::where('status', 'active')->get();
    return view('landing', compact('plans'));
})->name('landing');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [\App\Http\Controllers\AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // User management
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', UserController::class);

    // Plan management
    Route::resource('plans', PlanController::class);

    // Subscription management
    Route::get('subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});

// Employee routes
Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    // Employee dashboard
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('dashboard');

    // Subscription management (employee can only create and view their own)
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});

// Profile routes (for both admin and employee)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';
