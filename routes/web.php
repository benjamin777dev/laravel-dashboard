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
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CustomerController; // Ensure you import the CustomerController
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\UpdateFromZohoCRMController;
use App\Http\Controllers\SubmittalController;

// Zoho Bulk Read Callback
Route::post('/api/zoho-callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');
Route::post('/webhook/contact', [UpdateFromZohoCRMController::class, 'handleContactUpdate']);
Route::post('/webhook/deal', [UpdateFromZohoCRMController::class, 'handleDealUpdate']);
Route::post('/api/webhook/csvcallback', [UpdateFromZohoCRMController::class, 'handleCSVCallback']);

Route::get('/', [DashboardController::class, 'index'])->name('root')->middleware('auth');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);
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
Route::post('/save-note', [DashboardController::class, 'saveNote'])->name('save.note')->middleware('auth');
Route::delete('/delete-note/{id}', [DashboardController::class, 'deleteNote'])->name('delete.note')->middleware('auth');
Route::post('/mark-done', [DashboardController::class, 'markAsDone'])->name('mark.done')->middleware('auth');
Route::post('/update-notes/{id}', [DashboardController::class, 'updateNote'])->name('update.note')->middleware('auth');

//task actions
Route::post('/create-task', [DashboardController::class, 'createTaskaction'])->name('create.task')->middleware('auth');
// get task in json
Route::get('/task/get-Tasks', [DashboardController::class, 'getTasks'])->middleware('auth');
Route::get('/task/get-Deals', [DashboardController::class, 'getDeals'])->middleware('auth');
Route::get('/task/get-Modules', [DashboardController::class, 'retriveModulesDB'])->middleware('auth');
Route::get('/task/get-Contacts', [DashboardController::class, 'getContacts'])->middleware('auth');
Route::get('/get-stages', [DashboardController::class, 'getStagesData'])->middleware('auth');
Route::put('/update-task/{id}', [DashboardController::class, 'updateTaskaction'])->name('update.task')->middleware('auth');
Route::delete('/delete-task/{id}', [DashboardController::class, 'deleteTaskaction'])->name('delete.task')->middleware('auth');


// Contacts Route
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index')->middleware('auth');
Route::get('/get-groups', [ContactController::class, 'getGroups'])->name('group.sort')->middleware('auth');
Route::get('/contacts/fetch-contact', [ContactController::class, 'getContact'])->name('contacts.fetch')->middleware('auth');
Route::get('/group', [ContactController::class, 'databaseGroup'])->name('contacts.group')->middleware('auth');
Route::put('/update-contact/{id}', [ContactController::class, 'updateContact'])->name('update.contact')->middleware('auth');
Route::get('/contacts-view/{contactId}', [ContactController::class, 'show'])->name('contacts.show')->middleware('auth');
Route::get('/contacts-create/{contactId}', [ContactController::class, 'showCreateContactForm'])->name('contacts.create');
Route::post('/contact/create', [ContactController::class, 'createContactId'])->name('contact.create');
Route::post('/contact/spouse/create/{contactId}', [ContactController::class, 'createSpouseContact'])->name('contact.spouse.create');
Route::get('/contact/roles', [DashboardController::class, 'getContactRole'])->name('contact.roles')->middleware('auth');
//notes fetch in json for contact
Route::get('/note/{contactId}', [ContactController::class, 'retriveNotesForContact'])->name('notes.fetch')->middleware('auth');
Route::get('/deal/note/{dealId}', [PipelineController::class, 'retriveNotesForDeal'])->name('notes.fetch.deal')->middleware('auth');

// Pipeline Route
Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index')->middleware('auth');
Route::get('/pipeline/deals', [PipelineController::class, 'getDeals'])->middleware('auth');
Route::get('/pipeline-view/{dealId}', [PipelineController::class, 'showViewPipelineForm'])->name('pipeline.view');
Route::get('/pipeline-create/{dealId}', [PipelineController::class, 'showCreatePipelineForm']);
Route::post('/pipeline/create', [PipelineController::class, 'createPipeline'])->middleware('auth');
Route::get('/pipeline-update/{dealId}', [PipelineController::class, 'showCreatePipelineForm']);
Route::put('/pipeline/update/{dealId}', [PipelineController::class, 'updatePipeline'])->name('pipeline.update')->middleware('auth');
Route::post('/add/deal/contact/role/{dealId}', [PipelineController::class, 'addContactRole'])->name('contacts.role')->middleware('auth');
Route::get('/get/deal/contact/role/{dealId}', [PipelineController::class, 'getContactRole'])->name('contact.role.fetch')->middleware('auth');
Route::post('/remove/deal/contact/role', [PipelineController::class, 'removeContactRole'])->name('contacts.role.remove')->middleware('auth');

//Groups
Route::get('/group', [GroupController::class, 'index'])->name('groups.index')->middleware('auth');
Route::get('/contact/groups', [GroupController::class, 'filterGroups'])->middleware('auth');
Route::post('/contact/group/update', [GroupController::class, 'updateContactGroup'])->middleware('auth');
Route::delete('/contact/group/delete/{contactGroupId}', [GroupController::class, 'deleteContactGroup'])->middleware('auth');
Route::get('/contact/group/create/CSVfile', [GroupController::class, 'createCsv'])->middleware('auth');
Route::post('/contact/group/bulk/remove', [GroupController::class, 'bulkRemove'])->middleware('auth');
Route::post('/bulkJob/update', [GroupController::class, 'bulkUpdate']);
// From ADMIN - Assuming these routes are for authenticated users
Auth::routes(['verify' => true]);

// aci routes
Route::post('/aci_create', [PipelineController::class, 'createACI'])->middleware('auth');

// Customers Route
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.list')->middleware('auth');

// Update User Details
Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile')->middleware('auth');
Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword')->middleware('auth');

//Submittal Route
Route::get('/submittal-create/{type}', [SubmittalController::class, 'showSubmittalCreate'])->name('submittal.create')->middleware('auth');
Route::get('/submittal/{dealId}', [SubmittalController::class, 'index'])->name('submittals.index')->middleware('auth');

// Catch-all route for SPA (Single Page Application) - place this last to avoid conflicts
// Route::get('{any}', [HomeController::class, 'index'])->where('any', '.*')->name('index');

//task routes
Route::get('/task', [TaskController::class, 'index'])->name('task.index')->middleware('auth');



// Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);