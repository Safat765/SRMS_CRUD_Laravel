<?php

use App\Models\Profile;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;


class ProfileController extends BaseController
{				
	public function index()
	{
		// Show all items
	}
				
	public function create()
	{
		$userType = Session::get('user_type');
		if ($userType == 1) {
		  $addURL = 'admin';
		} elseif ($userType == 2) {
		  $addURL = 'instructor';
		} elseif ($userType == 3) {
		  $addURL = 'students';
		}
		$pageName = "Change Password";			
		$url = url('/' . $addURL . '/profiles');
		$data = compact('url' ,'pageName');

		return View::make("profile/changePassword")->with($data);
	}
				
	public function store()
	{
		$userType = Session::get('user_type');
		if ($userType == 1) {
		  $addURL = 'admin';
		} elseif ($userType == 2) {
		  $addURL = 'instructor';
		} elseif ($userType == 3) {
		  $addURL = 'students';
		}
		$validation = Validator::make(Input::all(), [
			'oldPassword' => 'required|min:4',
			'newPassword' => 'required|min:4'
		], [
			'required' => 'The :attribute field is required.',
			'min' => 'The :attribute must be at least :min characters.'
		]);

		if ($validation->fails()) {
			return Redirect::back()
				->withErrors($validation);
		}
		$currentPassword = Session::get("password");

		if (password_verify(Input::get("oldPassword"), $currentPassword)) {
			
			if (Input::get("newPassword") === Input::get("oldPassword")) {
				Session::flash("message", "Previous password and New Password can not be same");

				return Redirect::back();
				die();
			} else {
				$profile = new Profile();
				$update = $profile->changePassword(Input::get("newPassword"));

				if ($update) {
					Session::put("password", Hash::make(Input::get("newPassword")));
					Session::flash("success", "Password Changed Successfully");

					return Redirect::to('/'.$addURL.'/dashboard');
				} else {
					Session::flash("message", "Failed to change password");

					return Redirect::back();
				}
			}
		} else {
			Session::flash("message", "Incorrect Previous Password");
			return Redirect::back();
		}
	}
				
	public function show($id)
	{
		//
	}
				
	public function edit($userID)
	{
		$profile = new Profile();
		if (Session::get("user_type") == 3) {
			$user = $profile->joinProfileWithSemester($userID);
		}
		$user = $profile->joinProfile($userID);

		if ($user) {
			return Response::json([
				'status' => 'success',
				'records' => $user
			], 200);
		} else {
			return Response::json([
				'status' => 'error'
			], 404);
		}
	}
				
	public function update($id)
	{
		$userType = Session::get('user_type');
		if ($userType == 1) {
		  $addURL = 'admin';
		} elseif ($userType == 2) {
		  $addURL = 'instructor';
		} elseif ($userType == 3) {
		  $addURL = 'students';
		}

		$validator = Validator::make(Input::all(), [
			'firstName'=> 'required|string|min:3|max:30',
			'middleName' => 'sometimes|string|max:50',
			'lastName' => 'required|string|max:50',
			'registrationNumber' => 'required|min:3|unique:profiles,registration_number',
			'departmentId'=> 'required',
			'session'=> 'sometimes|min:3',
			'semesterId'=> 'sometimes'
		], [
			'required' => 'The :attribute field is required.',
			'unique' => 'This :attribute is already taken.',
			'min' => 'The :attribute must be at least :min characters.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);
		
		if ($validator->fails()) {
			Session::flash('message', "validation fail");
			return Redirect::back()
			->withErrors($validator);
		}
		$profile = new Profile();
		$firstName = Input::get('firstName');
		$middleName = Input::get('middleName');
		$lastName = Input::get('lastName');
		$registrationNumber = Input::get('registrationNumber');
		$departmentId = Input::get('departmentId');
		$userType = Session::get('user_type');

		if ($userType == 3) {
			$session = Input::get('session');
			$semesterId = Input::get('semesterId');
		} else {
			$session = null;
			$semesterId = null;
		}
		$departmentId = $profile->getDepartmentId($departmentId);
		$semesterId = $profile->getSemesterId($semesterId);
		$data = [
			'profileId'=> $id,
			'firstName'=> $firstName,
			'middleName'=> $middleName,
			'lastName'=> $lastName,
			'registrationNumber'=> $registrationNumber,
			'departmentId'=> $departmentId,
			'session'=> $session,
			'semesterId'=> $semesterId
		];
		$update = $profile->updateProfile($data);

		if ($update) {
			Session::flash('success', 'Profile updated successfully');
			return Redirect::to('/'.$addURL.'/profiles/show/profile');
		} else {
			Session::flash('message', 'Failed to update Profile');
			return Redirect::back();
		}
	}
				
	public function destroy($id)
	{
		// Handle deletion
	}

	public function editProfile()
	{
		$profile = new Profile();
		$user = $profile->existProfile(Session::get('user_id'));
		$data = compact('title', 'user');

		return View::make("profile/editProfile")->with($data);
	}

	public function searchProfile($userID)
	{
		if ($userID == "") {
			return Response::json([
				"status"=> "error",
				"message"=> "Registration Number not found"
			], 404);
		}

		$profile = new Profile();
		$exist = $profile->checkProfile($userID);

		if ($exist) {			
			return Response::json([
				"status"=> "success"
			],200);
		} else {
			return Response::json([
				"status"=> "error",
				"message"=> "Profile Already exist"
			],404);
		}
	}

	public function addNameProfile($id)
	{
		if ($id == "") {
			return Response::json([
				"status"=> "error",
				"message"=> "user Id not found"
			], 404);
		}

		$validator = Validator::make(Input::all(), [
			'firstName'=> 'required|string|min:3|max:30',
			'middleName' => 'sometimes|string|max:50',
			'lastName' => 'required|string|max:50'
		], [
			'required' => 'The :attribute field is required.',
			'min' => 'The :attribute must be at least :min characters.',
    		'sometimes' => 'The :attribute must be a string when provided.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$profile = new Profile();

		$firstName = Input::get('firstName');
		$middleName = Input::get('middleName');
		$lastName = Input::get('lastName');
		$data = [
			'userId'=> $id,
			'firstName'=> $firstName,
			'middleName'=> $middleName,
			'lastName'=> $lastName
		];
		$update = $profile->addName($data);

		if ($update) {
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
}