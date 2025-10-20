<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\BusCompanyController;
use App\Http\Controllers\Owner\BusController;
use App\Http\Controllers\Owner\ScheduleController;

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

// Homepage - accessible to everyone
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
Route::middleware(['auth', 'role:passenger'])->group(function () {
    Route::get('/profile', function () {
        return view('passenger.profile');
    })->name('passenger.profile');
    
    // More passenger routes will be added here later
});

