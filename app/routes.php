<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');

Route::group(['prefix' => 'users'], function() {
    Route::get('/status/{id}', ['as' => 'userStatus', 'uses' => 'UserController@status']);
});

Route::resource('/users', 'UserController');
Route::resource('/departments', 'DepartmentController');
Route::resource('/semesters', 'SemesterController');
Route::resource('/courses', 'CourseController');
Route::group(['prefix' => 'courses'], function() {
        Route::get('/status/{id}', ['as' => 'courseStatus', 'uses' => 'CourseController@status']);
});
Route::resource('/exams', 'ExamController');
Route::resource('/marks', 'CourseController');
Route::resource('/results', 'CourseController');
Route::resource('/profiles', 'CourseController');