<?php

use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\RestaurantController;
use App\Http\Controllers\Agent\RestaurantReservationController;
use App\Http\Controllers\Agent\RestaurantTableController;
use App\Http\Controllers\Agent\RoomBookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\AgentRequestController;
use App\Http\Controllers\Agent\RoomController;
use App\Http\Controllers\Agent\TourBookingController;
use App\Http\Controllers\Agent\TourController;
use App\Http\Controllers\Agent\TourDateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('frontend.home');
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

    // Restaurant Management
    Route::prefix('restaurants')->name('restaurants.')->group(function () {
        Route::get('/', [RestaurantController::class, 'index'])->name('index');
        Route::get('/{restaurant}', [RestaurantController::class, 'show'])->name('show');
        Route::get('/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('edit');
        Route::put('/{restaurant}', [RestaurantController::class, 'update'])->name('update');

        // Tables
        Route::prefix('{restaurant}/tables')->name('tables.')->group(function () {
            Route::get('/', [RestaurantTableController::class, 'index'])->name('index');
            Route::get('/create', [RestaurantTableController::class, 'create'])->name('create');
            Route::post('/', [RestaurantTableController::class, 'store'])->name('store');
            Route::get('/{table}/edit', [RestaurantTableController::class, 'edit'])->name('edit');
            Route::put('/{table}', [RestaurantTableController::class, 'update'])->name('update');
            Route::delete('/{table}', [RestaurantTableController::class, 'destroy'])->name('destroy');
        });

        // Reservations
        Route::prefix('{restaurant}/reservations')->name('reservations.')->group(function () {
            Route::get('/', [RestaurantReservationController::class, 'index'])->name('index');
            Route::get('/create', [RestaurantReservationController::class, 'create'])->name('create');
            Route::post('/', [RestaurantReservationController::class, 'store'])->name('store');
            Route::get('/{reservation}', [RestaurantReservationController::class, 'show'])->name('show');
            Route::post('/{reservation}/confirm', [RestaurantReservationController::class, 'confirm'])->name('confirm');
            Route::post('/{reservation}/cancel', [RestaurantReservationController::class, 'cancel'])->name('cancel');
            Route::patch('/{reservation}/notes', [RestaurantReservationController::class, 'updateNotes'])->name('update-notes');
        });
    });

    // Tour Management
    Route::resource('tours', TourController::class);

    // Tour Dates Management
    Route::prefix('tours/{tour}/dates')->name('tours.dates.')->group(function () {
        Route::get('/', [TourDateController::class, 'index'])->name('index');
        Route::get('/create', [TourDateController::class, 'create'])->name('create');
        Route::post('/', [TourDateController::class, 'store'])->name('store');
        Route::get('/{date}/edit', [TourDateController::class, 'edit'])->name('edit');
        Route::put('/{date}', [TourDateController::class, 'update'])->name('update');
        Route::delete('/{date}', [TourDateController::class, 'destroy'])->name('destroy');

        // Bulk add dates
        Route::get('/bulk-create', [TourDateController::class, 'bulkCreate'])->name('bulk-create');
        Route::post('/bulk-store', [TourDateController::class, 'bulkStore'])->name('bulk-store');
    });

    // Tour Bookings Management
    Route::prefix('tour-bookings')->name('tour-bookings.')->group(function () {
        Route::get('/', [TourBookingController::class, 'index'])->name('index');
        Route::get('/create', [TourBookingController::class, 'create'])->name('create');
        Route::post('/', [TourBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [TourBookingController::class, 'show'])->name('show');
        Route::post('/{booking}/confirm', [TourBookingController::class, 'confirm'])->name('confirm');
        Route::post('/{booking}/cancel', [TourBookingController::class, 'cancel'])->name('cancel');
        Route::patch('/{booking}/notes', [TourBookingController::class, 'updateNotes'])->name('update-notes');
    });
});

Route::get('/become-agent', function () {
    return view('frontend.agent-request');
})->name('agent.request.form');

Route::post('/become-agent', [AgentRequestController::class, 'store'])
    ->name('agent.request.store');

//frontend routes
// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/search', [SearchController::class, 'index'])->name('search');
// Route::get('/tours', [TourController::class, 'index'])->name('tours');
// Route::get('/hotels', [HotelController::class, 'index'])->name('hotels');
// Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants');
// Route::get('/about', [PageController::class, 'about'])->name('about');
// Route::get('/blog', [BlogController::class, 'index'])->name('blog');
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Route::get('/packages', [PackageController::class, 'index'])->name('packages');
// Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

require __DIR__ . '/auth.php';
