<?php

use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\RoomBookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\AgentRequestController;
use App\Http\Controllers\Agent\RoomController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // REMOVE THIS DUPLICATE:
    // Route::get('/agent/dashboard', function () {
    //     return view('agent.dashboard');
    // })->middleware('role:agent');

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->middleware('role:user');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Agent routes
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    // KEEP ONLY THIS ONE - the controller route:
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // REMOVE THIS DUPLICATE:
    // Route::get('/dashboard', fn() => view('agent.dashboard'))->name('dashboard');

    // Room routes
    Route::resource('rooms', RoomController::class);

    // Room Bookings routes
    Route::get('/room-bookings', [RoomBookingController::class, 'index'])->name('room-bookings.index');
    Route::get('/room-bookings/create', [RoomBookingController::class, 'create'])->name('room-bookings.create');
    Route::post('/room-bookings', [RoomBookingController::class, 'store'])->name('room-bookings.store');
    Route::get('/available-rooms', [RoomBookingController::class, 'availableRooms'])->name('available-rooms');
    Route::get('/room-bookings/{booking}', [RoomBookingController::class, 'show'])->name('room-bookings.show');
    Route::post('/room-bookings/{booking}/status', [RoomBookingController::class, 'updateStatus'])->name('room-bookings.update-status');

    // Image management routes
    Route::post('/rooms/{room}/images/{image}/set-primary', [RoomController::class, 'setPrimaryImage'])
        ->name('rooms.images.set-primary');

    Route::delete('/rooms/{room}/images/{image}', [RoomController::class, 'destroyImage'])
        ->name('rooms.images.destroy');
});

Route::get('/become-agent', function () {
    return view('frontend.agent-request');
})->name('agent.request.form');

Route::post('/become-agent', [AgentRequestController::class, 'store'])
    ->name('agent.request.store');

require __DIR__ . '/auth.php';
