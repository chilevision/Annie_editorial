<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Users_controller;
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
Route::view('/', 'auth.first')->middleware('first_user');
Route::post('/createfirst', [Users_controller::class, 'store'])->middleware('first_user');
Auth::routes(['register' => false]);
Route::get('/old/api', [\App\Http\Controllers\Rundowns_controller::class, 'old_api']);

Route::get('/cas/login', [\App\Http\Controllers\CasController::class, 'login'])->name('cas.login');
Route::post('cas/logout', [\App\Http\Controllers\CasController::class, 'logout'])->name('cas.logout');
Route::get('/cas/callback', [\App\Http\Controllers\CasController::class, 'callback'])->name('cas.callback');

Auth::routes();

//Routes for authenticated users:
Route::group(['prefix' => 'dashboard','middleware' => 'auth'], function () {
	Route::get('/', [\App\Http\Controllers\Dashboard_controller::class, 'index'])->name('dashboard');
	Route::resource('/rundown', \App\Http\Controllers\Rundowns_controller::class, [
		'names' => [ 'index' => 'rundown.index'] ]);
	Route::get('/rundown/{id}/editcal', [\App\Http\Controllers\Rundowns_controller::class, 'edit_calendar']);
	Route::post('/rundown/updatecal', [\App\Http\Controllers\Rundowns_controller::class, 'update_calendar']);
	Route::get('/old/load/{id}', [\App\Http\Controllers\Rundowns_controller::class, 'load']);
});

//Routes for administrator users: 
Route::group(['prefix' => 'dashboard/settings', 'middleware' => 'is_admin'], function () {
	Route::get('/', [\App\Http\Controllers\Settings_controller::class, 'index'])->name('settings');
	Route::put('/update', [\App\Http\Controllers\Settings_controller::class, 'update'])->name('settings.update');
	Route::view('/users', 'settings.users')->name('users');
	Route::delete('users/{id}', [Users_controller::class, 'destroy'])->name('users.delete');
});
