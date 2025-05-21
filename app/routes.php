<?php

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


Route::group(['before'=> 'admin'], function() {
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
});

Route::group(['before'=> 'instructor'], function() {
    Route::group(['prefix' => 'instructor'], function() {
        Route::get('/course/{courseId}/semester/{semesterId}', 'MarkController@students');

        Route::resource('marks', 'MarkController');
        Route::group(['prefix'=> 'marks'], function() {
            Route::get('/all/students', 'MarkController@studentList');
            Route::get('/assigned/courses', 'MarkController@courseView');
            Route::post('/go', 'MarkController@create');
        });
    });
});

Route::group(['before'=> 'students'], function() {
    Route::group(['prefix'=> 'students'], function() {
        Route::get('/results/{id}', ['before' => 'result', 'uses' => 'ResultController@show']);

        Route::get('/results/semester/{id}', 'ResultController@semesterWise');
        Route::get('/results/enrolled', 'ResultController@enrolledCourse');
        
    });
});

foreach (['admin', 'instructor', 'students'] as $role) {
    Route::group([
        'prefix' => $role,
        'before' => $role
    ], function() use ($role) { 
        Route::get('/dashboard', ['uses' => 'LoginController@index']);

        Route::get('/profiles/{id}', ['before' => 'profile', 'uses' => 'ProfileController@show']);
        Route::resource('profiles', 'ProfileController', ['except' => ['show']]);
 
        Route::group(['prefix' => 'profiles'], function() {
            Route::get('/search/{id}', ['uses' => 'ProfileController@search']);
            Route::get('/add/{id}', ['uses' => 'ProfileController@addName']);
        });
 
    });
}

Route::get('/session', function(){
    $all = Session::all();
    p($all);
});


Route::get('/logout', ['uses' => 'LoginController@logout']);