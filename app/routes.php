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

Route::get('/', 'LoginController@create');
Route::resource('/login', 'LoginController');

Route::group(['prefix' => 'admin'], function() {
    Route::group(['prefix' => 'users'], function() {
        Route::get('/status/{id}', ['as' => 'userStatus', 'uses' => 'UserController@status']);
    });
    Route::resource('users', 'UserController');
    Route::resource('departments', 'DepartmentController');
    Route::resource('exams', 'ExamController');
    Route::resource('semesters', 'SemesterController');
    Route::resource('courses', 'CourseController');
    Route::group(['prefix' => 'courses'], function() {
            Route::get('/status/{id}', ['as' => 'courseStatus', 'uses' => 'CourseController@status']);
    });
});
    Route::resource('/profiles', 'ProfileController');
    Route::group(['prefix' => 'profiles'], function() {
            Route::get('/change-password', 'ProfileController@changePassword');
            Route::get('/show/profile', ['as' => 'editProfile', 'uses' =>'ProfileController@editProfile']);
            Route::get('/search/{id}', 'ProfileController@searchProfile');
            Route::get('/add/{id}', 'ProfileController@addNameProfile');
    });
Route::group(['before'=> 'onlyInstructor'], function() {
    Route::group(['prefix' => 'instructor'], function() {
        Route::resource('marks', 'MarkController');
        Route::group(['prefix'=> 'marks'], function() {
            Route::get('/students/{courseId}/{semesterId}', 'MarkController@students');
            Route::get('/all/students', 'MarkController@studentList');
            Route::post('/go', 'MarkController@createMark');
        });
    });
});
Route::group(['before'=> 'onlyStudents'], function() {
    Route::group(['prefix'=> 'students'], function() {
        Route::resource('results', 'ResultController', ['only' => ['index']]);
        Route::get('/results/semester/{id}', 'ResultController@semeterWise');
    });
});
Route::get('/session', function(){
    $all = Session::all();
    p($all);
});

Route::get('/logout', function(){
    Session::flush();
	Session::flash('success', 'Logout Successful');
    return Redirect::to('login/create');
});