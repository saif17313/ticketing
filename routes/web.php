<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\BusCompanyController;
use App\Http\Controllers\Owner\BusController;
use App\Http\Controllers\Owner\ScheduleController;
use App\Http\Controllers\Passenger\DashboardController as PassengerDashboardController;
use App\Http\Controllers\Passenger\SearchController;
use App\Http\Controllers\Passenger\BusController as PassengerBusController;

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration routes (only for passengers)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route (authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Homepage - Passenger Dashboard (accessible to everyone)
Route::get('/', [PassengerDashboardController::class, 'index'])->name('home');
Route::get('/passenger/dashboard', [PassengerDashboardController::class, 'index'])->name('passenger.dashboard');

// Public Bus Search Routes (no authentication required)
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::post('/search', [SearchController::class, 'search'])->name('search.buses');

// Bus Details (public)
Route::get('/bus/{bus}', [PassengerBusController::class, 'show'])->name('bus.details');

// Owner routes (only accessible by users with role='owner')
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Bus Company Management
    Route::resource('companies', BusCompanyController::class);
    
    // Bus Management
    Route::resource('buses', BusController::class);
    
    // Schedule Management
    Route::resource('schedules', ScheduleController::class);
});

// Passenger routes (only accessible by users with role='passenger')
Route::middleware(['auth', 'role:passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    // Profile
    Route::get('/profile', function () {
        return view('passenger.profile');
    })->name('profile');
    
    // Booking routes will be added here in next commit
});

