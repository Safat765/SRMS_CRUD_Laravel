<?php

use App\Models\User;
use App\Models\Mark;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
		
class LoginController extends BaseController {
				
	public function index()
	{
		$marks = new Mark();
		$studentId = Session::get("user_id");
		$results = $marks->assignedCourses($studentId);
		$totalCourse = count($results);
		$marksResults = $marks->viewMarks($studentId);

		$user = new User();
		$courses = $user->enrollCourse($studentId);
		$totalEnrollCourse = count($courses);
		$data = compact('results', 'totalCourse', 'marksResults', 'courses', 'totalEnrollCourse');

		return View::make("dashboard")->with($data);
	}
				
	public function create()
	{
		return View::make('login');
	}
				
	public function store()
	{
		$user = new User();

		$validator = Validator::make(Input::all(), [
			'username' => 'required',
			'password' => 'required|min:4'
		]);		
		
		if ($validator->fails()) {
			return Redirect::back()
			->withErrors($validator)
			->withInput(Input::except('password'));
		}
		
		$username = Input::get('username');
		$password = Input::get('password');
		$user = $user->findPassword($username);
		
		if (!password_verify($password, $user->password)) {
			Session::flash('message', 'Incorrect Password');
			return Redirect::to('login/create');
		}
		$password = $user->password;		
		$userExists = $user->login($username, $password);
		
		if ($userExists) {

			if ($user) {				
				Session::put('username', $user->username);
				Session::put('user_id', $user->user_id);
				Session::put('email', $user->email);
				Session::put('registration_number', $user->registration_number);
				Session::put('phone_number', $user->phone_number);
				Session::put('password', $user->password);
				Session::put('user_type', $user->user_type);
				Session::put('status', $user->status);

				Session::flash('success', 'Login Successful');

				if ($user->user_type == 1) {
					return Redirect::to('/admin/dashboard');
				} elseif ($user->user_type == 2) {
					return Redirect::to('/instructor/dashboard');
				} else {
					return Redirect::to('/students/dashboard');
				}
			} else {
				Session::flash('message', 'User not found');
				return Redirect::to('login/create');
				die();
			}
		} else {
			Session::flash('message', 'Invalid Login');
			return Redirect::to('login/create');
			die();
		}
	}
				
	public function show($id)
	{
		// Show single item
	}
				
	public function edit($id)
	{
		// Show edit form
	}
				
	public function update($id)
	{
		// Handle update
	}
				
	public function destroy($id)
	{
		// Handle deletion
	}
}