<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    if(Sentinel::check()) {
        return redirect('dashboard');
    } else {
        return view('welcome');
    }
  
});

// Authorization
Route::get('login', 'Auth\SessionController@getLogin')->name('auth.login.form');
Route::post('login', 'Auth\SessionController@postLogin')->name('auth.login.attempt');
Route::any('logout', 'Auth\SessionController@getLogout')->name('auth.logout');

// Registration
Route::get('register', 'Auth\RegistrationController@getRegister')->name('auth.register.form');
Route::post('register', 'Auth\RegistrationController@postRegister')->name('auth.register.attempt');

// Activation
Route::get('activate/{code}', 'Auth\RegistrationController@getActivate')->name('auth.activation.attempt');
Route::get('resend', 'Auth\RegistrationController@getResend')->name('auth.activation.request');
Route::post('resend', 'Auth\RegistrationController@postResend')->name('auth.activation.resend');

// Password Reset
Route::get('password/reset/{code}', 'Auth\PasswordController@getReset')->name('auth.password.reset.form');
Route::post('password/reset/{code}', 'Auth\PasswordController@postReset')->name('auth.password.reset.attempt');
Route::get('password/reset', 'Auth\PasswordController@getRequest')->name('auth.password.request.form');
Route::post('password/reset', 'Auth\PasswordController@postRequest')->name('auth.password.request.attempt');

// Users
Route::resource('users', 'UserController');

// Roles
Route::resource('roles', 'RoleController');

// Company
Route::resource('companies', 'CompanyController');

// Department
Route::resource('departments', 'DepartmentController');

// Department_role
Route::resource('department_roles', 'DepartmentRoleController');

// Work
Route::resource('works', 'WorkController');

//WorkRecords
Route::resource('work_records', 'WorkRecordController');
Route::get('work_records_table', ['as' => 'work_records_table', 'uses' => 'WorkRecordController@workRecordsTable']);


// Employee
Route::resource('employees', 'EmployeeController');

// Education
Route::resource('education', 'EducationController');

// Education theme
Route::resource('education_themes', 'EducationThemeController');

// Education article
Route::resource('education_articles', 'EducationArticleController');

// Document
Route::resource('documents', 'DocumentController');

// AdCategory
Route::resource('ad_categories', 'AdCategoryController');

// Ad
Route::resource('ads', 'AdController');

// Questionnaire
Route::resource('questionnaires', 'QuestionnaireController');

// QuestionnaireResult
Route::resource('questionnaire_results', 'QuestionnaireResultController');

// EvaluationCategory
Route::resource('evaluation_categories', 'EvaluationCategoryController');

// EvaluationQuestion
Route::resource('evaluation_questions', 'EvaluationQuestionController');

// EvaluationRating
Route::resource('evaluation_ratings', 'EvaluationRatingController');

// EvaluationEmployees
Route::resource('evaluation_employees', 'EvaluationEmployeeController');

// Evaluation
Route::resource('evaluations', 'EvaluationController');

// Post
Route::resource('posts', 'PostController');
Route::post('/comment/store', ['as' => 'comment.store', 'uses' => 'PostController@storeComment', function () {
    event(new App\Events\MessageSend($message, $comment, $id));
    return "Event has been sent!";
}]);

// post update
Route::get('posts/{id}/{year?}/{month?}/{day?}/{hour?}/{minute?}/{second?}', ['as' => 'posts', 'uses' => 'PostController@updated']);
Route::get('setCommentAsRead/{id}', ['as' => 'setCommentAsRead', 'uses' => 'PostController@setCommentAsRead']);

// Event
Route::resource('events', 'EventController');
Route::get('store_event/{id}', ['as' => 'store_event', 'uses' => 'EventController@store_event']);

// Table
Route::resource('tables', 'TableController');

// Emailing
Route::resource('emailings', 'EmailingController');

// AbsenceType
Route::resource('absence_types', 'AbsenceTypeController');

// Absences
Route::resource('absences', 'AbsenceController');

// Notices
Route::resource('notices', 'NoticeController');

// NoticeStatistic
Route::resource('notice_statistics', 'NoticeStatisticController');

// UserInteres
Route::resource('user_interes', 'UserInteresController');

// Campaign
Route::resource('campaigns', 'CampaignController');

// CampaignSequence
Route::resource('campaign_sequences', 'CampaignSequenceController');

// CampaignRecipient
Route::resource('campaign_recipients', 'CampaignRecipientController');

// Benefit
Route::resource('benefits', 'BenefitController');

// Car
Route::resource('cars', 'CarController');

// Fuel
Route::resource('fuels', 'FuelController');

// Locco
Route::resource('loccos', 'LoccoController');

// Task
Route::resource('tasks', 'TaskController');

// Setting
Route::resource('settings', 'SettingController');

// VehicalService
Route::resource('vehical_services', 'VehicalServiceController');

// Template
Route::resource('templates', 'TemplateController');

// Dashboard 
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
/*
Route::get('dashboard', function () {
    return view('Centaur::dashboard');
})->name('dashboard');*/

// Layout 
Route::get('layout', ['as' => 'layout', 'uses' => 'LayoutController@index']);

// TravelOrder
Route::resource('travel_orders', 'TravelOrderController');
Route::get('close_travel', ['as' => 'close_travel', 'uses' => 'TravelOrderController@close_travel']);
Route::get('travelShow/{id}', ['as' => 'travelShow', 'uses' => 'TravelOrderController@travelShow']);
Route::post('travelFilter', ['as' => 'travelFilter', 'uses' => 'TravelOrderController@travelFilter']);
Route::get('pdfTravel/{id}',array('as'=>'pdfTravel','uses'=>'TravelOrderController@pdfTravel'));

//TravelExpenses
Route::resource('travel_expenses', 'TravelExpensesController');

//Shortcut
Route::resource('shortcuts', 'ShortcutController');

// Terminations
Route::resource('terminations', 'TerminationController');

// EmployeeTermination
Route::resource('employee_terminations', 'EmployeeTerminationController');

// Project
Route::resource('projects', 'ProjectController');

// Customer
Route::resource('customers', 'CustomerController');

// Afterhour
Route::resource('afterhours', 'AfterhourController');
     
// JobInterview
Route::resource('job_interviews', 'JobInterviewController');



// Oglasnik
Route::get('oglasnik', ['as' => 'oglasnik', 'uses' => 'AdController@oglasnik']);
Route::get('sort', ['as' => 'sort', 'uses' => 'AdController@sort']);

// Noticeboard
Route::get('noticeboard', ['as' => 'noticeboard', 'uses' => 'NoticeController@noticeboard']);
Route::get('notices.schedule', ['as' => 'notices.schedule', 'uses' => 'NoticeController@schedule']);
Route::get('notices.test_mail', ['as' => 'notices.test_mail', 'uses' => 'NoticeController@test_mail_open']);
Route::post('sendTestEmail', ['as' => 'sendTestEmail', 'uses' => 'NoticeController@sendTestEmail']);

// Send mail questionnaire
Route::get('sendEmail', ['as' => 'sendEmail', 'uses' => 'QuestionnaireController@sendEmail']);

// Send absence confirmation mail
Route::get('confirmation', ['as' => 'confirmation', 'uses' => 'AbsenceController@storeConf']);
Route::get('confirmation_update', ['as' => 'confirmation_update', 'uses' => 'AbsenceController@storeConf_update']);
// Open absence confirmation page
Route::get('absence/confirmation_show', ['as' => 'confirmation_show', 'uses' => 'AbsenceController@confirmation_show']);

// User edit 
Route::get('user/edit_user/{id}', ['as' => 'user.edit', 'uses' => 'UserController@edit_user']);

// Upload image 
Route::get('upload_image', ['as' => 'upload', 'uses' => 'DocumentController@uploadImage']);


// Open slide show
Route::get('users.slide_show/{id}', ['as' => 'slide_show', 'uses' => 'UserController@slide_show']);

use App\Models\Event;
use App\Http\Resources\EventCollection;

Route::get('/event', function () {
    return new EventCollection(Event::all());
});
Route::get('side_calendar', ['as' => 'side_calendar', 'uses' => 'EventController@side_calendar']);

// Admin panel
Route::get('admin_panel', ['as' => 'admin_panel', 'uses' => 'DashboardController@openAdmin']);
Route::get('admin', ['as' => 'admin', 'uses' => 'DashboardController@openAdminNew']);

Route::get('all_event', ['as' => 'all_event', 'uses' => 'EventController@modal_event']);

// Start Campaign
Route::get('startCampaign', ['as' => 'sendEmail', 'uses' => 'CampaignController@startCampaign']);
Route::get('imageDelete', ['as' => 'imageDelete', 'uses' => 'DocumentController@imageDelete']);

// CampaignSequence mail
Route::get('campaign_mail', ['as' => 'campaign_mail', 'uses' => 'CampaignSequenceController@campaign_mail']);
Route::get('campaign_sequences.test_mail', ['as' => 'campaign_sequences.test_mail', 'uses' => 'CampaignSequenceController@test_mail_open']);
Route::post('sendTestSequence', ['as' => 'sendTestSequence', 'uses' => 'CampaignSequenceController@sendTestEmail']);
Route::post('setOrder', ['as' => 'setOrder', 'uses' => 'CampaignSequenceController@setOrder']);

// Get last km for car
Route::post('last_km', 'CarController@last_km');
Route::post('getMailSettings', 'SettingController@getMailSettings');

Route::get('/t', function () {
    event(new \App\Events\MessageSendEvent());
    dd('Event Run Successfully.');
});

Route::get('errorMessage', ['as' => 'errorMessage', 'uses' => 'ErrorController@errorMessage']);

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/view_clear', function() {
    Artisan::call('view:clear');
    return "View is cleared";
});
Route::get('/down', function(){
    $exitCode = Artisan::call('down');
});