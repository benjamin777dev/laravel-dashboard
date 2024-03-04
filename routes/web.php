<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController; // Make sure to import the LoginController
use App\Http\Controllers\Auth\ForgotPasswordController; // Make sure to import the LoginController
use App\Http\Controllers\Auth\RegisterController; // Make sure to import the LoginController
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PipelineController;

// Home Route (welcome page or dashboard)
Route::get('/', function () {
    return redirect()->route('dashboard.index'); 
})->middleware('auth');

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout'); // Add if you have a logout method

// Zoho OAuth Routes
Route::get('/auth/redirect', [RegisterController::class, 'redirectToZoho'])->name('auth.redirect');
Route::get('/auth/callback', [RegisterController::class, 'handleZohoCallback'])->name('auth.callback');

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Contacts Route
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');

// Pipeline Route
Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index');
Route::get('/pipeline/{deal}', [PipelineController::class, 'show'])->name('pipeline.show');


// FROM ADMIN
Auth::routes(['verify' => true]);
// customers route
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.list');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// back to routes
Auth::routes();

