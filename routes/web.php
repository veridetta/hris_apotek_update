<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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
Route::get('/start', [App\Http\Controllers\AttendanceController::class, 'start'])->name('start');
Route::get('/master', [App\Http\Controllers\AttendanceController::class, 'master'])->name('master');
Route::post('/masterAuth', [App\Http\Controllers\AttendanceController::class, 'masterAuth'])->name('masterAuth');
Route::get('/view', [App\Http\Controllers\AttendanceController::class, 'view'])->name('view');
Route::get('history/{month}/{id}/{year}', [AttendanceController::class, 'history'])->name('history');
Route::post('start_attendance', 'App\Http\Controllers\AttendanceController@start_attendance')->name('start_attendance');
Route::post('start_attendance_master', 'App\Http\Controllers\AttendanceController@start_attendance_master')->name('start_attendance_master');
Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
    dd("Cache Clear All");
});
Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	// Rute untuk pengguna dengan peran 'admin'
    Route::middleware(['role:admin'])->group(function () {
        // Rute admin di sini
        // Contoh: Route::get('/admin', 'AdminController@index')->name('admin.home');
		Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
		Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
		Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
		Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
		Route::get('map', function () {return view('pages.maps');})->name('map');
		Route::get('icons', function () {return view('pages.icons');})->name('icons');
		Route::get('employee', 'App\Http\Controllers\EmployeeController@index')->name('employee');
		Route::get('employees/ajax', 'App\Http\Controllers\EmployeeController@getEmployees')->name('employees_get');
		Route::post('employee_store', 'App\Http\Controllers\EmployeeController@store')->name('employee_store');
		Route::post('employee_edit', [EmployeeController::class, 'edit'])->name('employee_edit');;
		Route::post('employee_delete', [EmployeeController::class, 'destroy'])->name('employee_delete');
		Route::post('/update-employee/{id}', [EmployeeController::class, 'updateEmployee'])->name('employee.update');
		//JABATAN
		Route::get('jabatan', 'App\Http\Controllers\JabatanController@index')->name('jabatan');
		Route::post('jabatan_store', 'App\Http\Controllers\JabatanController@store')->name('jabatan_store');
		Route::post('jabatan_edit', [JabatanController::class, 'edit'])->name('jabatan_edit');;
		Route::post('jabatan_delete', [JabatanController::class, 'destroy'])->name('jabatan_delete');
		//SHIFT
		Route::get('shift', 'App\Http\Controllers\ShiftController@index')->name('shift');
		Route::post('shift_store', 'App\Http\Controllers\ShiftController@store')->name('shift_store');
		Route::post('shift_edit', [ShiftController::class, 'edit'])->name('shift_edit');;
		Route::post('shift_delete', [ShiftController::class, 'destroy'])->name('shift_delete');
		//GAJI
		Route::get('salary', 'App\Http\Controllers\SalaryController@index')->name('salary');
		Route::post('salary_store', 'App\Http\Controllers\SalaryController@store')->name('salary_store');
		Route::post('salary_edit', [SalaryController::class, 'edit'])->name('salary_edit');;
		Route::post('salary_delete', [SalaryController::class, 'destroy'])->name('salary_delete');
		//SCHEDULE
		Route::get('schedule', 'App\Http\Controllers\ScheduleController@index')->name('schedule');
		Route::post('schedule_store', 'App\Http\Controllers\ScheduleController@store')->name('schedule_store');
		Route::post('schedule_edit', [ScheduleController::class, 'edit'])->name('schedule_edit');
		Route::post('schedule_delete', [ScheduleController::class, 'destroy'])->name('schedule_delete');
		//ATTENDANCE
		Route::get('attendance', 'App\Http\Controllers\AttendanceController@index')->name('attendance');
		Route::post('attendance_store', 'App\Http\Controllers\AttendanceController@store')->name('attendance_store');
		Route::post('attendance_edit', [AttendanceController::class, 'edit'])->name('attendance_edit');
		Route::post('attendance_delete', [AttendanceController::class, 'delete'])->name('attendance_delete');
		//SETTING
		Route::get('/setting', 'App\Http\Controllers\SettingController@index')->name('setting');
		Route::post('setting_store', 'App\Http\Controllers\SettingController@store')->name('setting_store');
		//PAYMENTS
		//Route::get('report/{month?}/{year?}', [PaymentController::class, 'show'])->name('report');
		//Route::get('report/details/{month}/{id}/{year}', [PaymentController::class, 'detail'])->name('detail');
		//Route::post('report/generate_payments', 'App\Http\Controllers\PaymentController@generate_payments')->name('generate_payments');
		//Route::get('pdf/{month}/{id}/{year}', [PaymentController::class, 'view'])->name('download_payments');
		//Route::get('get_pdf/{month}/{id}/{year}', [PaymentController::class, 'view'])->name('get_download_payments');
		Route::post('payments_delete', [PaymentController::class, 'delete'])->name('payments_delete');
		//
		Route::get('table-list', function () {return view('pages.tables');})->name('table');
		Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    });
	Route::get('report/{month?}/{year?}', [PaymentController::class, 'show'])->name('report');
	Route::get('report/details/{month}/{id}/{year}', [PaymentController::class, 'detail'])->name('detail');
	Route::get('absen/details/{month}/{id}/{year}', [PaymentController::class, 'absen'])->name('absen');
	Route::post('report/generate_payments', 'App\Http\Controllers\PaymentController@generate_payments')->name('generate_payments');
	Route::get('pdf/{month}/{id}/{year}', [PaymentController::class, 'view'])->name('download_payments');
	Route::get('get_pdf/{month}/{id}/{year}', [PaymentController::class, 'view'])->name('get_download_payments');
	Route::get('attendance/{from}/{to}', 'App\Http\Controllers\AttendanceController@index')->name('attendance');
});

