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
Route::get('/down', function(){
    $exitCode = Artisan::call('down');
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

// Employee
Route::resource('employees', 'EmployeeController');

// Project
Route::resource('projects', 'ProjectController');
Route::get('url_project_update/{project?}/{date?}', ['as' => 'url_project_update', 'uses' => 'ProjectController@url_project_update']);
Route::get('close_project/{project?}', ['as' => 'close_project', 'uses' => 'ProjectController@close_project']);
Route::post('updateProject', ['as' => 'updateProject', 'uses' => 'ProjectController@updateProject']);

// Project
Route::resource('category_employees', 'CategoryEmployeeController');

// Publish
Route::resource('publishes', 'PublishController');
Route::post('saveImg', ['as' => 'saveImg', 'uses' => 'PublishController@saveImg']);

// Preparations
Route::resource('preparations', 'PreparationController');
Route::get('close_preparation/{preparation?}', ['as' => 'close_preparation', 'uses' => 'PreparationController@close_preparation']);
Route::get('delivered/{id?}', ['as' => 'delivered', 'uses' => 'PreparationController@delivered']);
Route::get('Centaur::preparations/index', ['as' => 'preparations_active', 'uses' => 'PreparationController@preparations_active']);

// PreparationsEmployee
Route::resource('preparation_employees', 'PreparationEmployeeController');

// PreparationRecord
Route::resource('preparation_records', 'PreparationRecordController');

// ListUpdate
Route::resource('list_updates', 'ListUpdateController');

// Designing
Route::resource('designings', 'DesigningController');
Route::get('close_designing/{designing?}', ['as' => 'close_designing', 'uses' => 'DesigningController@close_designing']);

//DesigningComment
Route::resource('designing_comments', 'DesigningCommentController');

// EquipmentList
Route::resource('equipment_lists', 'EquipmentListController');
Route::post('addItem', 'EquipmentListController@addItem');
Route::get('equipmentList/{id?}', ['as' => 'equipmentList', 'uses' => 'EquipmentListController@equipmentList']);
Route::post('replaceItem', ['as' => 'replaceItem', 'uses' => 'EquipmentListController@replaceItem']);
Route::get('multiReplaceItem/{id?}', ['as' => 'multiReplaceItem', 'uses' => 'EquipmentListController@multiReplaceItem']);
Route::post('multiReplaceStore', ['as' => 'multiReplaceStore', 'uses' => 'EquipmentListController@multiReplaceStore']);
Route::post('import', 'EquipmentListController@import')->name('import'); 
Route::post('import_with_replace', 'EquipmentListController@import_with_replace')->name('import_with_replace'); 
Route::post('importSiemens', 'EquipmentListController@importSiemens')->name('importSiemens'); 
Route::get('export/{id?}', 'EquipmentListController@export')->name('export');
Route::get('exportList/', 'EquipmentListController@exportList')->name('export');

// Project Employees
Route::resource('project_employees', 'ProjectEmployeeController');
Route::get('save/{employee?}/{date?}/{project?}/{all_days?}', ['as' => 'save', 'uses' => 'ProjectEmployeeController@save']);
Route::get('brisi/{project?}', ['as' => 'brisi', 'uses' => 'ProjectEmployeeController@brisi']);
Route::get('uskladi/{project?}', ['as' => 'uskladi', 'uses' => 'ProjectEmployeeController@uskladi']);

// Dashboard
 /*
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/

// Dashboard 
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('live/{date?}', ['as' => 'live', 'uses' => 'DashboardController@live']);
Route::get('missing', ['as' => 'missing', 'uses' => 'DashboardController@missing']);

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('errorMessage', ['as' => 'errorMessage', 'uses' => 'ErrorController@errorMessage']);

// Android
Route::resource('android', 'ConnectController');



Route::get('delete_file', 'DesigningController@delete_file')->name('delete_file');
 