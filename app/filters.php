<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{ 
	$publicRoutes = ['login', 'login/create', 'logout'];

    // Get the current path (e.g., /profile, /login)
    $path = $request->path();

    // Check if the user is logged in and if the route requires authentication
    if (!in_array($path, $publicRoutes) && !Session::has('user_id')) {
        // Redirect to login page if the user is not logged in and the route is protected
        return Redirect::to('login/create')->with('message', 'You need to log in first.');
    }
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

// In app/filters.php
Route::filter('onlyInstructor', function()
{
    if (!Session::has('user_type') || Session::get('user_type') != 2) {
        // Redirect with error message if not instructor
        return Redirect::to('/login')->with('message', "Only instructors can access this section <br> You are not allow to access this page");
    }
});
Route::filter('onlyStudents', function()
{
    if (!Session::has('user_type') || Session::get('user_type') != 3) {
        // Redirect with error message if not instructor
        return Redirect::to('/login')->with('message', "Only students can access this section <br> You are not allow to access this page");
    }
});
