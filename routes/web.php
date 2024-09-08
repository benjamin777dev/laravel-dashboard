<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController; // Make sure to import the ResetPasswordController if you're using it
use App\Http\Controllers\ClosingInformationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController; // Ensure you import the CustomerController
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NonTmController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrategyGroupController;
use App\Http\Controllers\SubmittalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamIndividualController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UpdateFromZohoCRMController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// Zoho Bulk Read Callback
Route::post('/api/zoho-callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');
/*
Route::post('/webhook/contact', [UpdateFromZohoCRMController::class, 'handleContactUpdate']);
Route::post('/webhook/deal', [UpdateFromZohoCRMController::class, 'handleDealUpdate']);
Route::post('/webhook/task', [UpdateFromZohoCRMController::class, 'handleTaskUpdate']);
Route::post('/webhook/cxg', [UpdateFromZohoCRMController::class, 'handleContactXGroupUpdate']);
Route::post('/webhook/aci', [UpdateFromZohoCRMController::class, 'handleAciUpdate']);
-- depreciated in favor of a single method:
*/
Route::post('/webhook/{module}', [UpdateFromZohoCRMController::class, 'handleUpdateDeleteFromZoho']);

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
Route::get('/dashboard-tasks', [DashboardController::class, 'retriveTaskforDatatable'])->name('dashboard.tasks')->middleware('auth');
Route::get('/upcomming-task', [TaskController::class, 'upcommingTaskForDashboard'])->name('dashboard.upcomming')->middleware('auth');

Route::get('/needsNewdate', [DashboardController::class, 'needNewDateMethod'])->middleware('auth');
Route::get('/getStage', [DashboardController::class, 'getStageForDashboard'])->middleware('auth');

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
Route::post('/contact/create', [ContactController::class, 'createContactId'])->name('contact.create');
Route::get('/contact_view', [ContactController::class, 'getContactJson'])->name('contact.get_contact')->middleware('auth');
Route::post('/contact/update/{id}', [ContactController::class, 'updateContactField'])->name('contact.update')->middleware('auth');

Route::get('/contacts-create/{contactId}', [ContactController::class, 'showCreateContactForm']);
Route::get('/contact/create/form/{contactId}', [ContactController::class, 'contactCreateForm'])->name('contacts.create.form')->middleware('auth');
Route::get('/contacts-view/{contactId}', [ContactController::class, 'show'])->name('contacts.show')->middleware('auth');
Route::get('/contacts-trasactions/{contactId}', [ContactController::class, 'retriveDealForContacts'])->name('contact.deal')->middleware('auth');
Route::get('/contact/detail/form/{contactId}', [ContactController::class, 'showDetailForm'])->name('contacts.detail.form')->middleware('auth');
Route::post('/contact/spouse/create/{contactId}', [ContactController::class, 'createSpouseContact'])->name('contact.spouse.create');
Route::get('/get-groups', [ContactController::class, 'getGroups'])->name('group.sort')->middleware('auth');
Route::get('/contacts/fetch-contact', [ContactController::class, 'getContact'])->name('contacts.fetch')->middleware('auth');
Route::get('/group', [ContactController::class, 'databaseGroup'])->name('contacts.group')->middleware('auth');
Route::put('/update-contact/{id}', [ContactController::class, 'updateContact'])->name('update.contact')->middleware('auth');
Route::get('/contact/roles', [DashboardController::class, 'getContactRole'])->name('contact.roles')->middleware('auth');
Route::get('/contact/email/list/{contactId}', [ContactController::class, 'contactEmailList'])->name('contact.email.list')->middleware('auth');

//notes fetch in json for contact
Route::get('/contact/list', [ContactController::class, 'contactList'])->name('contacts.list')->middleware('auth');
Route::get('/note/{contactId}', [ContactController::class, 'retriveNotesForContact'])->name('notes.fetch')->middleware('auth');
Route::get('/task/{contactId}', [ContactController::class, 'retriveTasksForContact'])->name('notes.fetch')->middleware('auth');
Route::get('/note-create/{contactId}', [ContactController::class, 'createNotesForContact'])->name('notes.create')->middleware('auth');
Route::get('/task-create/{contactId}', [ContactController::class, 'createTasksForContact'])->name('notes.task.create')->middleware('auth');
Route::get('/deal/note/{dealId}', [PipelineController::class, 'retriveNotesForDeal'])->name('notes.fetch.deal')->middleware('auth');
Route::get('/deal/task/{dealId}', [PipelineController::class, 'retriveNotesForDeal'])->name('notes.fetch.deal')->middleware('auth');

// Pipeline Route
Route::get('/pipeline', [PipelineController::class, 'index'])->name('pipeline.index')->middleware('auth');
Route::get('/pipeline_view', [PipelineController::class, 'getDealsJson'])->name('pipeline.get_pipeline')->middleware('auth');
Route::get('/note-create-pipe/{dealId}', [PipelineController::class, 'createNotesForDeal'])->name('notes.create.deal')->middleware('auth');
Route::get('/task-create-pipe/{dealId}', [PipelineController::class, 'createTasksForDeal'])->name('tasks.create.deal')->middleware('auth');
Route::get('/pipeline/deals', [PipelineController::class, 'getDeals'])->middleware('auth');
Route::get('/pipeline-view/{dealId}', [PipelineController::class, 'showViewPipeline'])->name('pipeline.view');
Route::get('/pipeline/detail/form/{dealId}', [PipelineController::class, 'showViewPipelineForm'])->name('pipeline.detail.view');
Route::get('/pipeline-create/{dealId}', [PipelineController::class, 'showCreatePipeline']);
Route::get('/pipeline/create/form/{dealId}', [PipelineController::class, 'showCreatePipelineForm']);
Route::post('/pipeline/create', [PipelineController::class, 'createPipeline'])->middleware('auth');
Route::get('/pipeline-update/{dealId}', [PipelineController::class, 'showCreatePipelineForm']);
Route::get('/pipline-cards', [PipelineController::class, 'piplineCardUpdate']);


Route::post('/deals/update/{id}', [PipelineController::class, 'updateDeals'])->name('deal.update')->middleware('auth');

Route::put('/pipeline/update/{dealId}', [PipelineController::class, 'updatePipeline'])->name('pipeline.update')->middleware('auth');
Route::post('/add/deal/contact/role/{dealId}', [PipelineController::class, 'addContactRole'])->name('contacts.role')->middleware('auth');
Route::get('/get/deal/contact/role/{dealId}', [PipelineController::class, 'getContactRole'])->name('contact.role.fetch')->middleware('auth');
Route::post('/remove/deal/contact/role', [PipelineController::class, 'removeContactRole'])->name('contacts.role.remove')->middleware('auth');

//Groups
Route::get('/group', [GroupController::class, 'index'])->name('groups.index')->middleware('auth');
Route::post('/group/create', [GroupController::class, 'createGroup'])->name('group.create')->middleware('auth');
Route::put('/group/edit/{groupId}', [GroupController::class, 'updateGroup'])->name('group.edit')->middleware('auth');
Route::delete('/group/delete/{groupId}', [GroupController::class, 'deleteGroup'])->name('group.delete')->middleware('auth');
Route::get('/contact/groups', [GroupController::class, 'filterGroups'])->middleware('auth');
Route::post('/contact/group/update', [GroupController::class, 'updateContactGroup'])->middleware('auth');
Route::delete('/contact/group/delete/{contactGroupId}', [GroupController::class, 'deleteContactGroup'])->middleware('auth');
Route::post('/contact/group/create/CSVfile', [GroupController::class, 'createCsv'])->middleware('auth');
Route::post('/contact/group/bulk/remove', [GroupController::class, 'bulkRemove'])->middleware('auth');
Route::post('/bulkJob/update', [GroupController::class, 'bulkUpdate']);
Route::get('/get/group/contacts', [GroupController::class, 'getGroupContacts']);
// From ADMIN - Assuming these routes are for authenticated users
Auth::routes(['verify' => true]);

// aci routes
Route::post('/aci_create', [PipelineController::class, 'createACI'])->middleware('auth');

//nontm page route
Route::get('/nontms/{dealId}', [NonTmController::class, 'index'])->middleware('auth');
Route::post('/create-nontm', [NonTmController::class, 'createNontm'])->middleware('auth');
Route::get('/nontm-create/{id}', [NonTmController::class, 'createNontmView'])->middleware('auth');
Route::put('/nontm-update/{id}', [NonTmController::class, 'updateNonTm'])->middleware('auth');
Route::get('/nontm-view/{id}', [NonTmController::class, 'getNonTm'])->middleware('auth');

// Customers Route
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.list')->middleware('auth');

// User Route
Route::get('/profile', [HomeController::class, 'index'])->name('user.profile')->middleware('auth');
Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile')->middleware('auth');
Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword')->middleware('auth');

//Submittal Route
Route::get('/submittal/{dealId}', [SubmittalController::class, 'index'])->name('submittals.index')->middleware('auth');
Route::post('/listing/submittal/create/{dealId}', [SubmittalController::class,
'createListingSubmittal'])->name('listing.submittal.create')->middleware('auth');
Route::get('/submittal-create/{type}/{submittalId}', [SubmittalController::class, 'showSubmittalCreate'])->name('submittal.create')->middleware('auth');
Route::get('/listing/form/{submittalId}', [SubmittalController::class, 'showListingSubmittalForm'])->name('listing.submittal.form')->middleware('auth');
Route::get('/buyer/form/{submittalId}', [SubmittalController::class, 'showBuyerSubmittalForm'])->name('buyer.submittal.form')->middleware('auth');
Route::get('/submittal-view/{type}/{submittalId}', [SubmittalController::class, 'showSubmittalView'])->name('submittal.create')->middleware('auth');
Route::put('/listing/submittal/update/{submittalId}', [SubmittalController::class, 'updateListingSubmittal'])->name('listing.submittal.update')->middleware('auth');
Route::post('/buyer/submittal/create/{dealId}', [SubmittalController::class,
'createBuyerSubmittal'])->name('buyer.submittal.create')->middleware('auth');
Route::put('/buyer/submittal/update/{submittalId}', [SubmittalController::class, 'updateBuyerSubmittal'])->name('buyer.submittal.update')->middleware('auth');

//task routes
Route::get('/task', [TaskController::class, 'index'])->name('task.index')->middleware('auth');
Route::post('/update-task-contact/{id}', [TaskController::class, 'updateTask'])->name('update.tasks')->middleware('auth');
Route::get('/task/for/contact/{contactId}', [TaskController::class, 'taskForContactJson'])->name('task.contact')->middleware('auth');
Route::get('/task/for/pipe/{dealId}', [TaskController::class, 'taskForPipeJson'])->name('task.pipe')->middleware('auth');
Route::get('/task/for/pipeline/{dealId}', [TaskController::class, 'taskForPipeline'])->name('task.pipeline')->middleware('auth');

//Notes Route
Route::get('/notes', [DashboardController::class, 'showNotes'])->name('show.notes')->middleware('auth');

//Email Route
Route::get('/emails',[EmailController::class,'index'])->name('email.index')->middleware('auth');
Route::get('/emails/list',[EmailController::class,'emailList'])->name('email.list')->middleware('auth');
Route::post('/send/email',[EmailController::class,'sendEmail'])->name('send.email')->middleware('auth');
Route::post('/send/multiple/email',[EmailController::class,'sendMultipleEmail'])->name('send.multiple.email')->middleware('auth');
Route::get('/email/detail/{emailId}',[EmailController::class,'emailDetail'])->name('email.detail')->middleware('auth');
Route::get('/email/detail/draft/{emailId}',[EmailController::class,'emailDetailDraft'])->name('email.detail.draft')->middleware('auth');
Route::get('/email/template',[EmailController::class,'emailTemplate'])->name('email.template')->middleware('auth');
Route::patch('/email/moveToTrash',[EmailController::class,'emailMoveToTrash'])->name('email.moveToTrash')->middleware('auth');
Route::patch('/email/delete',[EmailController::class,'emailDelete'])->name('email.delete')->middleware('auth');
Route::get('/get/email/modal/{emailId}',[EmailController::class,'getEmailModal'])->name('get.email.modal')->middleware('auth');
// Route::post('/get/email-create',[EmailController::class,'getEmailCreateModal'])->name('get.email.create.modal')->middleware('auth');


//Template Route
Route::post('/create/template',[TemplateController::class,'createTemplate'])->name('create.template')->middleware('auth');
Route::get('/get/templates/from/zoho',[TemplateController::class,'getTemplatesFromZoho'])->name('get.template.zoho')->middleware('auth');
Route::get('/get/templates',[TemplateController::class,'getTemplates'])->name('get.template')->middleware('auth');
Route::get('/get/templates/json',[TemplateController::class,'getTemplatesJSON'])->name('get.template.json')->middleware('auth');
Route::get('/get/template/detail/{templateId}',[TemplateController::class,'getTemplateDetail'])->name('get.template.detail')->middleware('auth');
Route::get('/read/template/detail/{templateId}',[TemplateController::class,'readTemplateDetail'])->name('read.template.detail')->middleware('auth');
Route::post('/delete/templates',[TemplateController::class,'deleteTemplates'])->name('delete.template')->middleware('auth');
Route::patch('/update/template/{templateId}',[TemplateController::class,'updateTemplate'])->name('delete.template')->middleware('auth');

// Closing Information Route
Route::get('/closing-information', [ClosingInformationController::class, 'index'])->name('closing.information')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/update-agent-info/{id}', [ProfileController::class, 'updateAgentInfo'])->name('profile.updateAgentInfo');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});

// Streategy Groups
Route::get('/strategy-group', [StrategyGroupController::class, 'index'])->name('strategy.group.index');

// Reports
Route::get('/reports/productionProjections', [ReportController::class, 'productionProjections'])->name('reports.productionProjections');
Route::post('/reports/productionProjections/render-deal-cards', [ReportController::class, 'renderDealCards'])->name('agent.deal.cards');

// Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);

// Call Record Route
Route::middleware('auth')->group(function () {
    Route::get('get-call-records/{contactId}', [CallController::class, 'listCallRecord'])->name('call.records.list');
    Route::post('add-call-record', [CallController::class, 'saveCallRecord'])->name('call.records.create');
    Route::post('upload-video-s3', [VideoController::class, 'upload'])->name('video.upload');
});


