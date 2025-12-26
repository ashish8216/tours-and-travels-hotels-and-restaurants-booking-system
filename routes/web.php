<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\AgentRequestController;
use App\Http\Controllers\Agent\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/agent/dashboard', function () {
        return view('agent.dashboard');
    })->middleware('role:agent');

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->middleware('role:user');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {

    Route::get('/dashboard', fn () => view('agent.dashboard'))->name('dashboard');

    // Room routes
    Route::resource('rooms', RoomController::class);

    // Image management routes (add these AFTER the resource route)
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

require __DIR__.'/auth.php';
