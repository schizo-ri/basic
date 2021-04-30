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

// Layout 
Route::get('layout', ['as' => 'layout', 'uses' => 'LayoutController@index']);

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

// Api
Route::resource('api_erp', 'ApiController');

// Users
Route::resource('users', 'UserController');

// Roles
Route::resource('roles', 'RoleController');

// Company
Route::resource('companies', 'CompanyController');
Route::get('company/structure', ['as' => 'structure', 'uses' => 'CompanyController@structure']);

// Department
Route::resource('departments', 'DepartmentController');

// EmployeeDepartment
Route::resource('employee_departments', 'EmployeeDepartmentController');

// Department_role
Route::resource('department_roles', 'DepartmentRoleController');

// Work
Route::resource('works', 'WorkController');

//WorkRecords
Route::resource('work_records', 'WorkRecordController');
Route::get('work_records_table', ['as' => 'work_records_table', 'uses' => 'WorkRecordController@workRecordsTable']);

//WorkTask
Route::resource('work_tasks', 'WorkTaskController');

//WorkDiary
Route::resource('work_diaries', 'WorkDiaryController');

//WorkDiaryItem
Route::resource('work_diary_items', 'WorkDiaryItemController');

// Employee
Route::resource('employees', 'EmployeeController');
Route::get('employees/show_print/{id}', ['as' => 'show_print', 'uses' => 'EmployeeController@showPrint']);

// Education
Route::resource('educations', 'EducationController');

// Education theme
Route::resource('education_themes', 'EducationThemeController');

// Education article
Route::resource('education_articles', 'EducationArticleController');

// Document
Route::resource('documents', 'DocumentController');

// DocumentCategory
Route::resource('document_categories', 'DocumentCategoryController');

// Upload image 
Route::get('upload_image', ['as' => 'upload', 'uses' => 'DocumentController@uploadImage']);
Route::get('imageDelete', ['as' => 'imageDelete', 'uses' => 'DocumentController@imageDelete']);

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
Route::post('/commentStore', ['as' => 'commentStore', 'uses' => 'PostController@storeComment', function () {
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
Route::get('confirmation', ['as' => 'confirmation', 'uses' => 'AbsenceController@storeConf']); // Send absence confirmation mail
Route::get('confirmation_update/{id}', ['as' => 'confirmation_update', 'uses' => 'AbsenceController@storeConf_update']);
Route::get('absence/confirmation_show', ['as' => 'confirmation_show', 'uses' => 'AbsenceController@confirmation_show']);// Open absence confirmation page
Route::get('absence/absences_table', ['as' => 'absences_table', 'uses' => 'AbsenceController@absences_table']);
Route::get('absence/absences_requests', ['as' => 'absences_requests', 'uses' => 'AbsenceController@absences_requests']);
Route::get('absence/absencesYears', ['as' => 'absencesYears', 'uses' => 'AbsenceController@absencesYears']);
Route::get('absence/print_requests', ['as' => 'print_requests', 'uses' => 'AbsenceController@printRequests']);
Route::get('getTasks', ['as' => 'getTasks', 'uses' => 'AbsenceController@getTasks']);
Route::get('getProject', ['as' => 'getProject', 'uses' => 'AbsenceController@getProject']);
Route::get('getDays/{id}', 'AbsenceController@getDays');
Route::get('requestsFromPlan', ['as' => 'requestsFromPlan', 'uses' => 'AbsenceController@requestsFromPlan']);

Route::get('days_offUnused/{id}', 'BasicAbsenceController@days_offUnused');
Route::get('daniGO', 'BasicAbsenceController@daniGO');

// Vacation
Route::resource('vacations', 'VacationController');
Route::get('vacationPlan', ['as' => 'vacationPlan', 'uses' => 'VacationPlanController@vacationPlan']);

// VacationPlan
Route::resource('vacation_plans', 'VacationPlanController');

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

// Benefit
Route::resource('suitabilities', 'SuitabilityController');

// Car
Route::resource('cars', 'CarController');

// Fuel
Route::resource('fuels', 'FuelController');
Route::post('importFuel', 'FuelController@importFuel')->name('importFuel'); 

// Locco
Route::resource('loccos', 'LoccoController');
Route::get('loccos_qr/create_qr', ['as' => 'loccos_qr.create_qr', 'uses' => 'LoccoController@create_qr']);

// Task
Route::resource('tasks', 'TaskController');
Route::get('task_list', ['as' => 'task_list', 'uses' => 'TaskController@openTaskList']);

// EmployeeTask
Route::resource('employee_tasks', 'EmployeeTaskController');
Route::get('tasks_confirm', ['as' => 'tasks_confirm', 'uses' => 'EmployeeTaskController@tasks_confirm']); // Send absence confirmation mail

// Setting
Route::resource('settings', 'SettingController');

// VehicalService
Route::resource('vehical_services', 'VehicalServiceController');
Route::post('importService', 'VehicalServiceController@importService')->name('importService'); 

// Template
Route::resource('templates', 'TemplateController');

// DayOff
Route::resource('day_offs', 'DayOffController');

// Dashboard 
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('change_lang/{lang}', ['as' => 'change_lang', 'uses' => 'DashboardController@change_lang']);

/*
Route::get('dashboard', function () {
    return view('Centaur::dashboard');
})->name('dashboard');*/

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
Route::get('shortcut_exist', ['as' => 'shortcut_exist', 'uses' => 'ShortcutController@shortcutExist']);

// Terminations
Route::resource('terminations', 'TerminationController');

// EmployeeTermination
Route::resource('employee_terminations', 'EmployeeTerminationController');

// Project
Route::resource('projects', 'ProjectController');
Route::post('importProject', 'ProjectController@importProject')->name('importProject'); 


// ProjectWorkTask
Route::resource('project_work_tasks', 'ProjectWorkTaskController');
Route::get('getProjectTasks', ['as' => 'getProjectTasks', 'uses' => 'ProjectWorkTaskController@getProjectTasks']);

// Customer
Route::resource('customers', 'CustomerController');

// Customer
Route::resource('customer_locations', 'CustomerLocationController');
Route::get('getCustomerLocation', ['as' => 'getCustomerLocation', 'uses' => 'CustomerLocationController@getCustomerLocation']);

// EnergyLocation
Route::resource('energy_locations', 'EnergyLocationController');

// EnergySource
Route::resource('energy_sources', 'EnergySourceController');

// EnergyConsumption
Route::resource('energy_consumptions', 'EnergyConsumptionController');
Route::get('lastCounter/{id1}/{id2}', ['as' => 'lastCounter', 'uses' => 'EnergyConsumptionController@lastCounter']);
Route::get('lastCounter_Skip/{id1}/{id2}/{date}', ['as' => 'lastCounter_Skip', 'uses' => 'EnergyConsumptionController@lastCounter_Skip']);

// Afterhour
Route::resource('afterhours', 'AfterhourController');
Route::get('confirmationAfterHours', ['as' => 'confirmationAfterHours', 'uses' => 'AfterhourController@storeConf']);
Route::post('confirmationAfterHoursMultiple', ['as' => 'confirmationAfterHoursMultiple', 'uses' => 'AfterhourController@storeConfMulti']);
Route::get('afterhours_approve', ['as' => 'afterhours_approve', 'uses' => 'AfterhourController@afterhours_approve']);
Route::get('afterhours/confirmation_show_after/{id}', ['as' => 'confirmation_show_after', 'uses' => 'AfterhourController@confirmation_show_after']);
Route::get('confirmation_update_after/{id}', ['as' => 'confirmation_update_after', 'uses' => 'AfterhourController@storeConf_update']);
Route::post('paidHours', ['as' => 'paidHours', 'uses' => 'AfterhourController@paidHours']);

// JobInterview
Route::resource('job_interviews', 'JobInterviewController');

// Training
Route::resource('trainings', 'TrainingController');

// EmployeeTraining
Route::resource('employee_trainings', 'EmployeeTrainingController');

// TemporaryEmployee
Route::resource('temporary_employees', 'TemporaryEmployeeController');

// TemporaryEmployeeRequest
Route::resource('temporary_employee_requests', 'TemporaryEmployeeRequestController');
Route::get('confirmationTemp', ['as' => 'confirmationTemp', 'uses' => 'TemporaryEmployeeRequestController@storeConf']);

// Kid
Route::resource('kids', 'KidController');

// Instruction
Route::resource('instructions', 'InstructionController');
Route::get('radne_upute', ['as' => 'radne_upute', 'uses' => 'InstructionController@radne_upute']);

// MailTemplate
Route::resource('mail_templates', 'MailTemplateController');
Route::get('mail_test/{id}', ['as' => 'mail_test', 'uses' => 'MailTemplateController@mailTest']);
Route::post('create_style', ['as' => 'create_style', 'uses' => 'MailTemplateController@create_style']);
Route::post('edit_style', ['as' => 'edit_style', 'uses' => 'MailTemplateController@edit_style']);

// MailText
Route::resource('mail_texts', 'MailTextController');

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

// User edit 
Route::get('user/edit_user/{id}', ['as' => 'user.edit', 'uses' => 'UserController@edit_user']);
Route::get('activateUser/{id}',array('as'=>'activateUser','uses'=>'UserController@activateUser'));

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

Route::get('contacts', ['as' => 'contacts', 'uses' => 'EmployeeController@contacts']);

Route::resource('android', 'ConnectController');

// Categorization
Route::resource('categorizations', 'CategorizationController');

// AnnulaGoal
Route::resource('annual_goals', 'AnnualGoalController');

// OKR
Route::resource('okrs', 'OkrController');
Route::get('progressOkr', ['as' => 'progressOkr', 'uses' => 'OkrController@progressOkr']);
Route::get('exportOkr', 'OkrController@exportOkr')->name('exportOkr'); 
Route::get('reminderOkr', 'OkrController@reminderOkr')->name('reminderOkr'); 

// OkrComment
Route::resource('okr_comments', 'OkrCommentController');

// KeyResult
Route::resource('key_results', 'KeyResultController');
Route::get('progressKeyResult', ['as' => 'progressKeyResult', 'uses' => 'KeyResultController@progressKeyResult']);
Route::get('reminderKeyResult', 'KeyResultController@reminderKeyResult')->name('reminderKeyResult'); 

// KeyResultsComment
Route::resource('key_results_comments', 'KeyResultsCommentController');

// KeyResultTask
Route::resource('key_result_tasks', 'KeyResultTaskController');
Route::get('progressTask', ['as' => 'progressTask', 'uses' => 'KeyResultTaskController@progressTask']);

// KeyResultTasksComment
Route::resource('key_result_tasks_comments', 'KeyResultTasksCommentController');

// Visitor
Route::resource('visitors', 'VisitorController');
Route::get('visitors/hr/{id}', 'VisitorController@visitors_show_hr');
Route::get('visitors/en/{id}', 'VisitorController@visitors_show_en');
Route::get('visitors/de/{id}', 'VisitorController@visitors_show_de');

// Competence
Route::resource('competences', 'CompetenceController');

// CompetenceDepartment
Route::resource('competence_departments', 'CompetenceDepartmentController');

// CompetenceGroupQuestion
Route::resource('competence_group_questions', 'CompetenceGroupQuestionController');

// CompetenceQuestion
Route::resource('competence_questions', 'CompetenceQuestionController');
Route::post('importQuestions/{id}', 'CompetenceQuestionController@importQuestions')->name('importQuestions'); 

// CompetenceRating
Route::resource('competence_ratings', 'CompetenceRatingController');

// CompetenceEvaluation
Route::resource('competence_evaluations', 'CompetenceEvaluationController');
Route::post('updateEvaluation', ['as' => 'updateEvaluation', 'uses' => 'CompetenceEvaluationController@updateEvaluation']);
 
// ImprovementRecommendation
Route::resource('improvement_recommendations', 'ImprovementRecommendationController');
Route::get('getRecommendations', ['as' => 'getRecommendations', 'uses' => 'ImprovementRecommendationController@getRecommendations']);

// WorkCorrecting
Route::resource('work_correctings', 'WorkCorrectingController');
Route::get('confirmationWorkCorrecting', ['as' => 'confirmationWorkCorrecting', 'uses' => 'WorkCorrectingController@storeConf']);

// Contract
Route::resource('contracts', 'ContractController');
Route::get('getConctract', ['as' => 'getConctract', 'uses' => 'ContractController@getConctract']);

// Contract
Route::resource('contract_subjects', 'ContractSubjectController');

// ContractArticle
Route::resource('contract_articles', 'ContractArticleController');

// ContractTemplate
Route::resource('contract_templates', 'ContractTemplateController');