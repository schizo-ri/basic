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
    return view('welcome');
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
Route::post('/comment/store', ['as' => 'comment.store', 'uses' => 'PostController@storeComment']);

// Event
Route::resource('events', 'EventController');

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

// Dashboard 
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
/*
Route::get('dashboard', function () {
    return view('Centaur::dashboard');
})->name('dashboard');*/

// Oglasnik
Route::get('oglasnik', ['as' => 'oglasnik', 'uses' => 'AdController@oglasnik']);
Route::get('sort', ['as' => 'sort', 'uses' => 'AdController@sort']);

// Noticeboard
Route::get('noticeboard', ['as' => 'noticeboard', 'uses' => 'NoticeController@noticeboard']);
Route::get('notices.schedule', ['as' => 'notices.schedule', 'uses' => 'NoticeController@schedule']);

// Send mail questionnaire
Route::get('sendEmail', ['as' => 'sendEmail', 'uses' => 'QuestionnaireController@sendEmail']);

// Send absence confirmation mail
Route::get('confirmation', ['as' => 'confirmation', 'uses' => 'AbsenceController@storeConf']);
Route::get('confirmation_update', ['as' => 'confirmation_update', 'uses' => 'AbsenceController@storeConf_update']);
// Open absence confirmation page
Route::get('absence/confirmation_show', ['as' => 'confirmation_show', 'uses' => 'AbsenceController@confirmation_show']);
// User edit 
Route::get('user/edit_user/{id}', ['as' => 'user.edit', 'uses' => 'UserController@edit_user']);

