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
    Route::get('/results/all', 'UserController@allStudents');
    Route::get('/results/semester/{id}', 'UserController@semesterWise');
    Route::group(['prefix' => 'users'], function() {
        Route::get('/status/{id}', ['as' => 'userStatus', 'uses' => 'UserController@status']);
    });
    Route::resource('users', 'UserController');

    Route::resource('departments', 'DepartmentController');
    
    Route::resource('exams', 'ExamController');
    
    Route::resource('semesters', 'SemesterController');
    
    Route::resource('courses', 'CourseController');
    Route::group(['prefix' => 'courses'], function() {
        Route::get('/assigned/all', 'CourseController@assignedCourse');
        Route::get('/status/{id}', ['as' => 'courseStatus', 'uses' => 'CourseController@status']);
    });
});

Route::group(['before'=> 'onlyInstructor'], function() {
    Route::group(['prefix' => 'instructor'], function() {
        Route::resource('marks', 'MarkController');
        Route::group(['prefix'=> 'marks'], function() {
            Route::get('/students/{courseId}/{semesterId}', 'MarkController@students');
            Route::get('/all/students', 'MarkController@studentList');
            Route::get('/assigned/courses', 'MarkController@courseView');
            Route::post('/go', 'MarkController@create');
        });
    });
});

Route::group(['before'=> 'onlyStudents'], function() {
    Route::group(['prefix'=> 'students'], function() {
        Route::resource('results', 'ResultController', ['only' => ['index']]);
        Route::get('/results/semester/{id}', 'ResultController@semeterWise');
        Route::get('/results/ecrolled', 'ResultController@enrolledCourse');
        
    });
});

foreach (['admin', 'instructor', 'students'] as $role) {
    Route::group(['prefix' => $role], function() use ($role) {
        Route::get('/dashboard', ['as' => 'login.index', 'uses' => 'LoginController@index']);

        Route::resource('profiles', 'ProfileController');
        Route::group(['prefix' => 'profiles'], function() {
            Route::get('/search/{id}', 'ProfileController@search');
            Route::get('/add/{id}', 'ProfileController@addName');
        });
    });
}

Route::get('/logout', function(){
    Session::flush();
	Session::flash('success', 'Logout Successful');
    return Redirect::to('login/create');
});