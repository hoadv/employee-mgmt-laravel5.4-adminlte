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
    return view('dashboard');
})->middleware('auth');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');
Route::get('/user-management', 'UserMgmtController@index');
Route::get('/system-management/{option}', 'SystemMgmtController@index');
Route::get('/employee-management', 'EmployeeMgmtController@index');

Route::get('/profile', 'ProfileController@index');