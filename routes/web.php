<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Users_controller;
use App\Http\Controllers\CasController;
use App\Http\Controllers\Dashboard_controller;
use App\Http\Controllers\Email_controller;
use App\Http\Controllers\Rundowns_controller;
use App\Http\Controllers\Settings_controller;
use App\Http\Controllers\Api_controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
|   _____ _    _ _____ _      ________      _______  _____ _____ ____  _   _ 
|  / ____| |  | |_   _| |    |  ____\ \    / /_   _|/ ____|_   _/ __ \| \ | |
| | |    | |__| | | | | |    | |__   \ \  / /  | | | (___   | || |  | |  \| |
| | |    |  __  | | | | |    |  __|   \ \/ /   | |  \___ \  | || |  | | . ` |
| | |____| |  | |_| |_| |____| |____   \  /   _| |_ ____) |_| || |__| | |\  |
|  \_____|_|  |_|_____|______|______|   \/   |_____|_____/|_____\____/|_| \_|
|
*/

//Routes to create the first user:
Route::view('/', 'auth.first')->middleware('first_user')->name('home');
Route::post('/createfirst', [Users_controller::class, 'store'])->middleware('first_user')->name('users.first');
Auth::routes(['register' => false]);
Route::get('/old/api', [Rundowns_controller::class, 'old_api']);
Route::group(['prefix' => 'api'], function () {
	Route::get('settings/{key}', [Api_controller::class, 'settings']);
	Route::get('prompter/{key}/{id}', [Api_controller::class, 'prompter']);
});
Route::get('/teleprompter', [Rundowns_controller::class, 'show_prompter']);

Route::get('/cas/login', [CasController::class, 'login'])->name('cas.login');
Route::post('cas/logout', [CasController::class, 'logout'])->name('cas.logout');
Route::get('/cas/callback', [CasController::class, 'callback'])->name('cas.callback');

Route::get('/email', [Email_controller::class, 'test']);
Route::get('/email/{id}/{token}', [Email_controller::class, 'view']);

Route::post('/setlang', [Dashboard_controller::class, 'setlang'])->name('app.setlang');

//Routes for authenticated users:
Route::group(['prefix' => 'dashboard','middleware' => 'auth'], function () {
	Route::get('/', [Dashboard_controller::class, 'index'])->name('dashboard');
	Route::post('/getcalendardata', [Dashboard_controller::class, 'getCalendarData'])->name('calenderdata');
	Route::resource('/rundown', Rundowns_controller::class, [
		'names' => ['index' => 'rundown.index']]);
	Route::get('/rundown/{id}/editcal', [Rundowns_controller::class, 'edit_calendar']);
	Route::post('/rundown/updatecal', [Rundowns_controller::class, 'update_calendar']);
	Route::get('/old/load/{id}', [Rundowns_controller::class, 'load']);
	Route::post('/rundown/print', [Rundowns_controller::class, 'print'])->name('rundown.print');
	Route::get('/rundown/{id}/generatexml', [Rundowns_controller::class, 'generateXML']);
	Route::get('/rundown/{id}/teleprompter', [Rundowns_controller::class, 'show_prompter']);
	Route::post('/rundown/teleprompter', [Rundowns_controller::class, 'rule_prompter']);
	Route::post('/rundown/users', [Rundowns_controller::class, 'users'])->name('rundown.users');
	Route::resource('/users', Users_controller::class);
});

//Routes for administrator users: 
Route::group(['prefix' => 'dashboard/settings', 'middleware' => 'is_admin'], function () {
	Route::get('/', [Settings_controller::class, 'index'])->name('settings');
	Route::put('/update', [Settings_controller::class, 'update'])->name('settings.update');
});
