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

Route::get('excel', 'FrontController@excel')->name('excel');
Route::get('add-order', 'PagesController@addOrder')->name('add.order');
Route::get('update-name', 'PagesController@UpdateProfile')->name('update.name');
Route::get('free-time', 'PagesController@stopost')->name('stopost');
Route::get('workgroup', 'PagesController@workgroup')->name('workgroup');
//Route::get('/', 'FrontController@index')->name('index');
Route::get('/', 'Auth\LoginController@showLoginForm')->name('index');
Route::get('main', 'PagesController@main')->name('main');
Route::get('acts/{id}/{RecType}', 'PagesController@acts')->name('acts');
Route::any('index-acts', 'PagesController@indexActs')->name('index.acts');
Route::post('index-acts-selected', 'PagesController@indexActsSelected')->name('index.acts.selected');
Route::get('all-jobs', 'PagesController@allJobs')->name('all.jobs');
Route::get('recommendation', 'PagesController@recommendation')->name('recommendation');
Route::get('talon', 'PagesController@talon')->name('talon');
Route::get('acts_talon/{id}/{RecType}', 'PagesController@actstalon')->name('acts.talon');
Route::any('recommendation-all', 'PagesController@recommendationAll')->name('recommendationAll');
Route::post('index-jobs-selected', 'PagesController@indexJobsSelected')->name('index.jobs.selected');
Route::post('index-recomendation-selected', 'PagesController@indexActsSelectedRecomendation')->name('index.jobs.selected');
Route::post('search-jobs', 'PagesController@searchJobs')->name('search.jobs');
Route::post('search-recomendation', 'PagesController@searchRecomendation')->name('search.recomendation');
Route::any('login-client', 'FrontController@loginClient')->name('login.client');
Route::any('logout-client', 'FrontController@logoutClient')->name('client.logout');
Route::post('manager-connect', 'FrontController@managerConnect')->name('manager.connect');
Route::get('login-for-admin', 'FrontController@loginForAdmin')->name('login.for.admin');
Route::any('load-admin-modal', 'AdminController@loadAdminModal')->name('load.admin.modal');
Route::any('load-admin-modal-rec', 'AdminController@loadAdminModalRec')->name('load.admin.modal.rec');
Route::get('prepare-all-data', 'PagesController@prepareAllData')->name('prepare.all.data');
Route::get('update-all-data', 'PagesController@updateAllData')->name('update.all.data');
Route::post('check-available-time', 'PagesController@checkAvailableTime')->name('check.available.time');
Route::post('google-check-available-time', 'PagesController@googleCheckAvailableTime')->name('google.check.available.time');
Route::post('add-diagnostic-order', 'PagesController@addDiagnosticOrder')->name('add.diagnostic.order');
Route::post('add-google-diagnostic-order', 'PagesController@addGoogleDiagnosticOrder')->name('add.google.diagnostic.order');
Route::post('add-google-diagnostic-order-noAuthorization', 'FrontController@addGoogleDiagnosticOrderNoAuthorization')->name('add.google.diagnostic.order.noAuthorization');
Route::get('calendar', 'FrontController@calendar')->name('calendar');
Route::get('aoth', 'FrontController@aoth')->name('aoth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
