<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use App\Services\UserService;

class UserController extends \BaseController
{
	private $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	public function index()
	{
		$search = Input::get('search');
		$data = $this->userService->getAllUser($search);
		
		return View::make('user.index')->with($data);
	}
	
	public function create()
	{	
		//
	}
	
	public function store()
	{
		$validator = $this->userService->checkValidation(Input::all());
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		
		$user = $this->userService->storeUser(Input::all());
		if ($user) {
			$profile = $this->userService->createProfile(Input::all());
			
			if (!$profile) {
				return Response::json(['status' => 'fail', 'message' => 'Failed to create profile'], 500);
			}
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['status' => 'fail', 'message' => 'Failed to create user'], 500);
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
		$user = $this->userService->findUser($id);
		
		if (!$user) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$validator = $this->userService->updateValidation(Input::all(), $id);
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		
		$update = $this->userService->updateUser(Input::all(), $id);
		$result = $this->userService->updateProfileDuringUserUpdate(Input::all());

		if ($update || $result) {
			return Response::json(['status' => 'success'], 200);
		} else {			
			return Response::json(['status' => 'error'], 400);
		}
	}
	
	public function destroy($id)
	{
		$user = $this->userService->findUser($id);
		
		if (!$user) {
			return Response::json(['status' => 'error'], 404);
		}
		$delete = $this->userService->destroyUser($id);
		
		if (!$delete) {
			return Response::json(['status' => 'error'], 404);
		} else{
			return Response::json(['status' => 'success'], 200);
		}		
	}
	
	public function status($id)
	{
		$user = $this->userService->findUser($id);
		
		if (!$user) {
			return Response::json(['status' => 'error'], 404);
		}
		$status = $this->userService->statusUpdate($id);
		
		if (!$status) {
			return Response::json(['status' => 'error'], 404);
		} else {
			return Response::json(['status' => 'success'], 200);
		}
	}
	
	public function allStudents()
	{
		$result = $this->userService->allStudents();
		$getResults = $result['getResults'];
		$totalStudents = $result['totalStudents'];
		
		return View::make('user.results')->with(['getResults' => $getResults, 'totalStudents' => $totalStudents]);
	}
	
	public function semesterWise($id)
	{
		$result = $this->userService->semesterWise($id);
		$getResults = $result['getResults'];
		
		if ($getResults) {
			return Response::json(['status' => 'success', 'records' => $getResults], 200);
		} else {
			return Response::json(['status' => 'error'], 400);
		}
	}
}