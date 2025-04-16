<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

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

		$data = compact('users', 'totalUsers', 'search');

		return View::make('User.index')->with($data);
	}
	
	public function create()
	{	
		$pageName = "Create User";			
		$url = url('/users');
		$ADMIN = User::USER_TYPE_ADMIN;
		$INSTRUCTOR = User::USER_TYPE_INSTRUCTOR;
		$STUDENT = User::USER_TYPE_STUDENT;
		$ACTIVE = User::STATUS_ACTIVE;
		$INACTIVE = User::STATUS_INACTIVE;
		$data = compact('url', 'pageName', 'type', 'ADMIN', 'INSTRUCTOR', 'STUDENT', 'ACTIVE', 'INACTIVE');
		
		return View::make('User/create')->with($data);
	}
	
	public function store()
	{
		$validator = Validator::make(Input::all(), [
			'username' => 'required|min:3|max:20',
			'email' => 'required|email',
			'password' => 'required|min:4',
			'userType' => 'required|in:1,2,3',
			'registrationNumber' => 'required|min:3',
			'phoneNumber' => 'required|numeric|digits_between:10,15',
			'departmentId'=> 'required',
			'firstName'=> 'required|string|min:3|max:30',
			'middleName' => 'sometimes|string|max:50',
			'lastName' => 'required|string|max:50'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);
		
		if ($validator->fails()) {
			Session::flash('message', "validation fail");
			return Redirect::back()
			->withErrors($validator)
			->withInput(Input::except('password'));
		}
		$user = new User();
		
		$username = Input::get('username');
		$email = Input::get('email');
		$password = Input::get('password');
		$userType = Input::get('userType');
		$status = User::STATUS_ACTIVE;
		$registrationNumber = Input::get('registrationNumber');
		$phoneNumber = Input::get('phoneNumber');
		$firstName = Input::get('firstName');
		$middleName = Input::get('middleName');
		$lastName = Input::get('lastName');
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
				Session::flash('message', 'Failed to create profile');
				return Redirect::back();
			}
			Session::flash('success', 'User created successfully');
			return Redirect::to('users');
		} else {
			Session::flash('message', 'Failed to create user');
			return Redirect::back();
		}
	}
	
	public function show($id)
	{
		// Show single item
	}
	
	public function edit($id)
	{
		$user = new User();
		$users = $user->edit($id);
		$pageName = "Edit User";
		$url = url('/users/' . $id);
		if (!$users) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$data = compact('users', 'url', 'pageName');
		return View::make('User/update')->with($data);
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
			'userType' => 'required|in:1,2,3',
			'status' => 'required|in:0,1',
			'phoneNumber' => 'required|numeric|digits_between:10,15'
		], [
			'required' => 'The :attribute field is required.',
			'min' => 'The :attribute must be at least :min characters.',
			'in' => 'Please select a valid :attribute.'
		]);
		
		if ($validator->fails()) {
			return Redirect::back()
			->withErrors($validator);
		}
		
		$update = $user->updateUser(Input::all(), $id);
		
		if ($update) {
			Session::flash('success', 'User updated successfully');
			return Redirect::to('users');
		} else {
			Session::flash('message', 'Failed to update user');
			return Redirect::back();
		}
	}
	
	public function destroy($id)
	{
		$user = new User();
		$users = $user->edit($id);
		
		if (!$users) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$delete = $user->deleteUser($id);
		
		if (!$delete) {
			Session::flash('message', 'Failed to delete user');
			return Redirect::back();
		} else{
			Session::flash('success', 'User deleted successfully');
			return Redirect::back();
		}		
	}
	
	public function status($id)
	{
		$user = new User();
		$users = $user->edit($id);
		
		if (!$users) {
			Session::flash('message', 'User not found');
			return Redirect::back();
		}
		$status = $user->statusUpdate($id);
		
		if (!$status) {
			Session::flash('message', 'Failed to update user status');
			return Redirect::back();
		} else {
			return Redirect::back();
		}
	}
}