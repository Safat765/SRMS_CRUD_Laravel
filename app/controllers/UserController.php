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
		return View::make('user.index', $this->userService->getAll(Input::get('search')));
	}
	
	public function store()
	{
		$validator = $this->userService->checkValidation(Input::all());
		
		if ($validator->fails()) {

			return Response::json(['errors' => $validator->errors()], 422);
		} elseif ($this->userService->store(Input::all())) {
			
			if (!$this->userService->create(Input::all())) {
				return Response::json(['status' => 'fail', 'message' => 'Failed to create profile'], 500);
			} else {
				return Response::json(['status' => 'success', 'message' => 'User created successfully'], 200);
			}
		} else {
			return Response::json(['status' => 'fail', 'message' => 'Failed to create user'], 500);
		}
	}
	
	public function update($id)
	{
		if (!$this->userService->find($id)) {
			Session::flash('message', 'User not found');

			return Redirect::back();
		}
		$validator = $this->userService->updateValidation(Input::all(), $id);
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}

		if ($this->userService->update(Input::all(), $id) || $this->userService->updateProfileDuringUserUpdate(Input::all())) {			
			return Response::json(['status' => 'success', 'message' => 'User updated successfully'], 200);
		} else {			
			return Response::json(['status' => 'error', 'message' => 'User update failed'], 400);
		}
	}
	
	public function destroy($id)
	{
		if (!$this->userService->find($id)) {			
			return Response::json(['status' => 'error', 'message' => 'User not found'], 404);
		}
		
		if (!$this->userService->destroy($id)) {
			return Response::json(['status' => 'error', 'message' => 'User delete failed'], 500);
		} else{
			return Response::json(['status' => 'success', 'message' => 'User deleted successfully'], 200);
		}		
	}
	
	public function status($id)
	{
		if (!$this->userService->find($id)) {			
			return Response::json(['status' => 'error', 'message' => 'User not found'], 404);
		}
		
		if (!$this->userService->statusUpdate($id)) {
			return Response::json(['status' => 'error', 'message' => 'User status update failed'], 404);
		} else {
			return Response::json(['status' => 'success', 'message' => 'User status updated successfully'], 200);
		}
	}
	
	public function allStudents()
	{
		$result = $this->userService->allStudents();
		
		return View::make('user.results')->with(['getResults' => $result['getResults'], 'totalStudents' => $result['totalStudents']]);
	}
	
	public function semesterWise($id)
	{
		$result = $this->userService->semesterWise($id);
		$getResults = $result['getResults'];
		
		if ($getResults) {			
			return Response::json(['status' => 'success', 'message' => 'Semester wise result fetched successfully', 'records' => $getResults], 200);
		} else {			
			return Response::json(['status' => 'error', 'message' => 'Semester wise result fetch failed'], 400);
		}
	}
}