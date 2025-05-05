<?php

use App\Models\User;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class UserController extends \BaseController
{	
	public function index()
	{
		$user = new User();
		$search = Input::get('search');
		
		if ($search != '') {
			$data = $user->filter($search);
			
			$totalUsers = $data['totalUsers'];
			$users = $data['users'];
		} else {
			$data = $user->showAll();
			$totalUsers = $data['totalUsers'];
			$users = $data['users'];
		}
		$ADMIN = User::USER_TYPE_ADMIN;
		$INSTRUCTOR = User::USER_TYPE_INSTRUCTOR;
		$STUDENT = User::USER_TYPE_STUDENT;
		$ACTIVE = User::STATUS_ACTIVE;
		$INACTIVE = User::STATUS_INACTIVE;
		$info = ['Admin' => $ADMIN, 'Instructor' => $INSTRUCTOR, 'Student' => $STUDENT, 'Active' => $ACTIVE, 'Inactive' => $INACTIVE];
		$list = [
			'department' => Department::lists('name', 'department_id'),
			'semester' => Semester::lists('name', 'semester_id')
		];
		$data = compact('users', 'totalUsers', 'search', 'info', 'list');

		return View::make('user.index')->with($data);
	}
	
	public function create()
	{	
		//
	}
	
	public function store()
	{
		$validator = Validator::make(Input::all(), [
			'username' => 'required|min:3|max:20|unique:users',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:4',
			'userType' => 'required|in:1,2,3',
			'registrationNumber' => 'required|min:3|unique:users,registration_number',
			'phoneNumber' => ['required', 'regex:/^(\+?\d{1,4}[-.\s]?)?(\(?\d{2,4}\)?[-.\s]?)?[\d\-.\s]{6,15}$/'],
			'departmentId'=> 'required'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'regex' => 'Please enter a valid phone number.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$user = new User();
		$username = Input::get('username');
		$email = Input::get('email');
		$password = Input::get('password');
		$userType = Input::get('userType');
		$status = User::STATUS_ACTIVE;
		$registrationNumber = Input::get('registrationNumber');
		$phoneNumber = Input::get('phoneNumber');
		$firstName = null;
		$middleName = null;
		$lastName = null;

		if ($userType == 3) {
			$session = Input::get('session');
			$semesterId = Input::get('semesterId');
		} else {
			$session = null;
			$semesterId = null;
		}
		$departmentId = Input::get('departmentId');
		$user = $user->createUser($username, $email, $password, $userType, $status, $registrationNumber, $phoneNumber);

		if ($user) {
			$userId = $user->getUserId($username);
			$profile = $user->createProfile($firstName, $middleName, $lastName, $registrationNumber, $session, $departmentId, $semesterId, $userId);
			
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
		$user = new User();
		$users = $user->edit($id);
		
		if (!$users) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$validator = Validator::make(Input::all(), [
			'username' => 'required|min:3|max:20|unique:users,username,'.$id.',user_id',
			'email' => 'required|email|unique:users,email,'.$id.',user_id',
			'registrationNumber' => 'required|min:3|unique:users,registration_number,'.$id.',user_id',
			'phoneNumber' => ['required', 'regex:/^(\+?\d{1,4}[-.\s]?)?(\(?\d{2,4}\)?[-.\s]?)?[\d\-.\s]{6,15}$/']
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'regex' => 'Please enter a valid phone number.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);	

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		
		$update = $user->updateUser(Input::all(), $id);
		
		if ($update) {
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
		$user = new User();
		$users = $user->edit($id);
		
		if (!$users) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$delete = $user->deleteUser($id);
		
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
		$user = new User();
		$users = $user->edit($id);
		
		if (!$users) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$status = $user->statusUpdate($id);
		
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
}