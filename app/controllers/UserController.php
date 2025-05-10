<?php

use App\Models\User;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use App\Services\UserService;

class UserController extends \BaseController
{
	protected $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	public function index()
	{
		$service = $this->userService;
		$search = Input::get('search');
		$data = $service->getAllUser($search);
		
		return View::make('user.index')->with($data);
	}
	
	public function create()
	{	
		//
	}
	
	public function store()
	{
		$service = $this->userService;
		$validator = $service->checkValidation(Input::all());
		
		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		
		$user = $service->storeUser(Input::all());
		if ($user) {
			$profile = $service->createProfile(Input::all());
			
			if (!$profile) {
				return Response::json([
					'status' => 'fail',
					'message' => 'Failed to create profile'
				], 500);
			}
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			return Response::json([
				'status' => 'fail',
				'message' => 'Failed to create user'
			], 500);
		}
	}
	
	public function show($id)
	{
		// Show single item
	}
	
	public function edit($id)
	{
		//
	}
	
	public function update($id)
	{
		$service = $this->userService;
		$user = $service->findUser($id);
		
		if (!$user) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$validator = $service->updateValidation(Input::all(), $id);
		
		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		
		$update = $service->updateUser(Input::all(), $id);
		$result = $service->updateProfileDuringUserUpdate(Input::all());

		if ($update || $result) {
			return Response::json([
				'status' => 'success'
			]);
		} else {			
			return Response::json([
				'errors' => 'error'
			]);
		}
	}
	
	public function destroy($id)
	{
		$service = $this->userService;
		$user = $service->findUser($id);
		
		if (!$user) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$delete = $service->destroyUser($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
			]);
		} else{
			return Response::json([
				'status' => 'success',
			], 200);
		}		
	}
	
	public function status($id)
	{
		$service = $this->userService;
		$user = $service->findUser($id);
		
		if (!$user) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$status = $service->statusUpdate($id);
		
		if (!$status) {
			return Response::json([
				'status' => 'error',
			]);
		} else {
			return Response::json([
				'status' => 'success',
			]);
		}
	}
	
	public function allStudents()
	{
		$service = $this->userService;
		$result = $service->allStudents();
		$getResults = $result['getResults'];
		$totalStudents = $result['totalStudents'];
		
		return View::make('user.results')->with(['getResults' => $getResults, 'totalStudents' => $totalStudents]);
	}
	
	public function semesterWise($id)
	{
		$service = $this->userService;
		$result = $service->semesterWise($id);
		$getResults = $result['getResults'];
		
		if ($getResults) {
			return Response::json([
				'status' => 'success',
				'records' => $getResults
			], 200);
		} else {
			return Response::json([
				'status' => 'error'
			], 400);
		}
	}
}