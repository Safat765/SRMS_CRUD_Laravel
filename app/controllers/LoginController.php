<?php

use App\Models\User;
use App\Models\Mark;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\LoginService;
class LoginController extends BaseController
{
	protected $loginService;

	public function __construct(LoginService $loginService)
	{
		$this->loginService = $loginService;
	}

	public function index()
	{
		$service = $this->loginService;
		$marks = new Mark();
		$studentId = Session::get("user_id");
		$results = $marks->assignedCourses($studentId);
		$totalCourse = count($results);
		$marksResults = $marks->viewMarks($studentId);

		$courses = $service->dashboard();
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
		$service = $this->loginService;
		$validator = $service->loginValidation(Input::all());
		
		if ($validator->fails()) {
			return Redirect::back()
			->withErrors($validator)
			->withInput(Input::except('password'));
		}
		
		$username = Input::get('username');
		$password = Input::get('password');
		$loginPassword = $service->loginPassword($username, $password);
		if (!$loginPassword) {
			Session::flash('message', 'Incorrect Password');
			return Redirect::to('login/create');
		}

		$password = $loginPassword['password'];
		$userDetails = $loginPassword['user'];
		$userExists = $service->loginUser($username, $password, $userDetails);
		
		if ($userExists) {

			if ($loginPassword) {
				Session::flash('success', 'Login Successful');

				if ($loginPassword['user']->user_type == 1) {
					return Redirect::to('/admin/dashboard');
				} elseif ($loginPassword['user']->user_type == 2) {
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