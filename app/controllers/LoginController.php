<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Services\LoginService;
use App\Services\MarkService;
use App\Models\User;

class LoginController extends BaseController
{
	private $loginService;
	private $markService;

	public function __construct(LoginService $loginService, MarkService $markService)
	{
		$this->loginService = $loginService;
		$this->markService = $markService;
	}

	public function index()
	{
		$studentId = Session::get("user_id");
		$results = $this->markService->assignedCourses($studentId);
		$totalCourse = count($results);
		$marksResults = $this->markService->view($studentId);

		$courses = $this->loginService->dashboard();
		$totalEnrollCourse = count($courses);

		return View::make("dashboard", ['data' => [
				'results' => $results,
				'totalCourse' => $totalCourse,
				'marksResults' => $marksResults,
				'courses' => $courses,
				'totalEnrollCourse' => $totalEnrollCourse
			]
		]);
	}
				
	public function create()
	{
		return View::make('login');
	}
				
	public function store()
	{
		$data = Input::all();
		$validator = $this->loginService->loginValidation($data);
		
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput(Input::except('password'));
		}
		
		$username = isset($data['username']) ? $data['username'] : null;
		$password = isset($data['password']) ? $data['password'] : null;
		$loginPassword = $this->loginService->loginPassword($username, $password);
		if (!$loginPassword) {
			Session::flash('message', 'Incorrect Password');
			return Redirect::to('login/create');
		}

		$password = isset($loginPassword['password']) ? $loginPassword['password'] : null;
		$userDetails = isset($loginPassword['user']) ? $loginPassword['user'] : null;
		$userExists = $this->loginService->loginUser($username, $password, $userDetails);
		
		if ($userExists) {

			if ($loginPassword) {
				Session::flash('success', 'Login Successful');

				if ($loginPassword['user']->user_type == User::USER_TYPE_ADMIN) {
					return Redirect::to('/admin/dashboard');
				} elseif ($loginPassword['user']->user_type == User::USER_TYPE_INSTRUCTOR) {
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
}