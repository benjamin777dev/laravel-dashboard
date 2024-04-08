<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController; // Make sure to import the ResetPasswordController if you're using it
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController; // Ensure you import the CustomerController

// Assuming you want to redirect authenticated users to the dashboard,
// and non-authenticated users to a home or login page:
// Route::get('/', [HomeController::class, 'index'])->middleware('guest')->name('root');
// Dashboard Route
Route::get('/', [DashboardController::class, 'index'])->name('root')->middleware('auth');
// Route::get('/home', [HomeController::class, 'index'])->name('home.index')->middleware('auth');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('reset', [RegisterController::class, 'showResetForm'])->name('reset');

// Zoho OAuth Routes
Route::get('/auth/redirect', [RegisterController::class, 'redirectToZoho'])->name('auth.redirect');
Route::get('/auth/callback', [RegisterController::class, 'handleZohoCallback'])->name('auth.callback');

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
//create note
Route::post('/save-note', [DashboardController::class, 'saveNote'])->name('save.note')->middleware('auth');
Route::post('/update-notes/{id}', [DashboardController::class, 'updateNote'])->name('update.note')->middleware('auth');
Route::post('/delete-note/{id}', [DashboardController::class, 'deleteNote'])->name('delete.note')->middleware('auth');

//task actions
Route::post('/create-task', [DashboardController::class, 'createTaskaction'])->name('create.task')->middleware('auth');
// get task in json
Route::get('/task/get-Tasks', [DashboardController::class, 'getTasks'])->middleware('auth');
Route::put('/update-task/{id}', [DashboardController::class, 'updateTaskaction'])->name('update.task')->middleware('auth');
Route::delete('/delete-task/{id}', [DashboardController::class, 'deleteTaskaction'])->name('delete.task')->middleware('auth');


// Contacts Route
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index')->middleware('auth');
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show')->middleware('auth');

// Pipeline Route
Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index')->middleware('auth');
Route::get('/pipeline/deals', [PipelineController::class, 'getDeals'])->middleware('auth');
Route::get('/pipeline-view/{dealId}', [PipelineController::class, 'showViewPipelineForm'])->name('pipeline.view');
Route::get('/pipeline-create', [PipelineController::class, 'showCreatePipelineForm'])->name('pipeline.create');
Route::post('/pipeline/create', [PipelineController::class, 'createPipeline'])->middleware('auth');;

// From ADMIN - Assuming these routes are for authenticated users
Auth::routes(['verify' => true]);

// Customers Route
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.list')->middleware('auth');

// Update User Details
Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile')->middleware('auth');
Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword')->middleware('auth');

// Catch-all route for SPA (Single Page Application) - place this last to avoid conflicts
Route::get('{any}', [HomeController::class, 'index'])->where('any', '.*')->name('index');

// Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);
