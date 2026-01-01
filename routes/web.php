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

// Agent routes
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', fn () => view('agent.dashboard'))->name('dashboard');

    // Room routes
    Route::resource('rooms', RoomController::class);
    //Room Bookings routes
    Route::get('/room-bookings', [RoomBookingController::class,'index'])->name('room-bookings.index');
    Route::get('/room-bookings/create', [RoomBookingController::class,'create'])->name('room-bookings.create');
    Route::post('/room-bookings', [RoomBookingController::class,'store'])->name('room-bookings.store');
    Route::get('/available-rooms',[RoomBookingController::class, 'availableRooms'])->name('available-rooms');

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

    Route::get('/debug-rooms', function() {
    if (!Auth::check()) {
        return "Not logged in";
    }

    $userId = Auth::id();
    $user = Auth::user();

    $data = [
        'user_id' => $userId,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'agent_relation' => $user->agent ? 'EXISTS' : 'NULL',
        'agent_id' => $user->agent ? $user->agent->id : 'No agent',
        'all_rooms_count' => \App\Models\Room::count(),
        'all_rooms' => \App\Models\Room::select('id', 'room_name', 'agent_id')->get()->toArray(),
        'my_rooms_count' => \App\Models\Room::where('agent_id', $userId)->count(),
        'my_rooms' => \App\Models\Room::where('agent_id', $userId)->get()->toArray()
    ];

    return response()->json($data, 200, [], JSON_PRETTY_PRINT);
});

require __DIR__.'/auth.php';
