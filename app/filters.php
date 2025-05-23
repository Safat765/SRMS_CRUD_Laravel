<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Models\User;

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
	$publicRoutes = ['/admin/dashboard', 'login/create', 'logout', '/instructor/dashboard', '/students/dashboard', 'login'];
    $path = $request->path();

    if (!in_array($path, $publicRoutes) && !Session::has('user_id')) {
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

Route::filter('instructor', function()
{
    if (!Session::has('user_type') || Session::get('user_type') != User::USER_TYPE_INSTRUCTOR) {
		return Response::make( warningMessage(). '    
			<p>Only <strong>instructors</strong> can access this section.</p>
			<p>You are not allowed to access this page.</p>
		</div>
		', 403);
    }
});
Route::filter('students', function()
{
    if (!Session::has('user_type') || Session::get('user_type') != User::USER_TYPE_STUDENT) {
		return Response::make( warningMessage(). '    
			<p>Only <strong>students</strong> can access this section.</p>
			<p>You are not allowed to access this page.</p>
		</div>
		', 403);
    }
});

Route::filter('admin', function() {
    if (!Session::has('user_type') || Session::get('user_type') != User::USER_TYPE_ADMIN) {
		return Response::make( warningMessage(). '    
			<p>Only <strong>admin</strong> can access this section.</p>
			<p>You are not allowed to access this page.</p>
		</div>
		', 403);
    }
});

App::missing(function()
{
	$addURL = getRole();
    return Redirect::to('/' . $addURL . '/dashboard')->with('message', "Invalid URL.");
});

Route::filter('profile', function() {
	$url = Request::fullUrl();
	$seperateURL = explode('/', $url);
	$getIdFromUrl = $seperateURL[count($seperateURL) - 1];
	$getIdFromUrl = (int) $getIdFromUrl;
	$userId = Session::get('user_id');

	if (!Session::has('user_id')) {
		return Redirect::to('/logout')->with('message', "You need to log in first.");
	} else {
		if ($getIdFromUrl != $userId) {
			return Response::make( warningMessage(). '    
				<p>You are not allowed to access this page.</p>
			</div>
			', 403);
		}
	}
});

Route::filter('result', function() {
	$url = Request::fullUrl();
	$seperateURL = explode('/', $url);
	$getIdFromUrl = $seperateURL[count($seperateURL) - 1];
	$getIdFromUrl = (int) $getIdFromUrl;
	$userId = Session::get('user_id');

	if (!Session::has('user_id')) {
		return Redirect::to('/logout')->with('message', "You need to log in first.");
	} else {
		if ($getIdFromUrl != $userId) {
			return Response::make( warningMessage(). '    
				<p>You are not allowed to access this page.</p>
			</div>
			', 403);
		}
	}
});