<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::post('/createfirst', [App\Http\Controllers\Users_controller::class, 'store'])->middleware('first_user');
Auth::routes(['register' => false]);

Auth::routes();

//Routes for authenticated users:
Route::group(['prefix' => 'dashboard','middleware' => 'auth'], function () {
	Route::get('/', [App\Http\Controllers\Dashboard_controller::class, 'index']);
	Route::resource('/rundown', App\Http\Controllers\Rundowns_controller::class);
	Route::get('/rundown/{id}/editcal', [App\Http\Controllers\Rundowns_controller::class, 'edit_calendar']);
	Route::post('/rundown/updatecal', [App\Http\Controllers\Rundowns_controller::class, 'update_calendar']);
});

//Routes for administrator users: 
Route::group(['prefix' => 'dashboard/settings', 'middleware' => 'is_admin'], function () {
	Route::get('/', [App\Http\Controllers\Settings_controller::class, 'index'])->name('settings');
	Route::put('/update', [App\Http\Controllers\Settings_controller::class, 'update'])->name('settings.update');
	Route::get('/users', App\Http\Livewire\Users::class);
});
