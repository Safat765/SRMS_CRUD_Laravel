<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Services\ProfileService;

class ProfileController extends BaseController
{
	private $profileService;

	public function __construct(ProfileService $profileService)
	{
		$this->profileService = $profileService;
	}
				
	public function create()
	{
		return View::make("profile/changePassword", ['data' => $this->profileService->create()]);
	}
				
	public function store()
	{
		$data = $this->profileService->checkValidation(Input::all());

		if ($data->fails()) {
			return Redirect::back()->withErrors($data);
		}
		$currentPassword = Session::get("password");

		if ($this->profileService->checkPassword(Input::get("oldPassword"), $currentPassword)) {
			
			if ($this->profileService->matchPassword(Input::get("newPassword"), Input::get("oldPassword"))) {
				Session::flash("message", "Previous password and New Password can not be same");

				return Redirect::back();
				die();
			} else {

				if ($this->profileService->changePassword(Input::get("newPassword"))) {
					Session::flash("success", "Password Changed Successfully");

					return Redirect::to('/'.$this->profileService->getURL().'/dashboard');
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
				
	public function edit($userID)
	{
		if (Session::get("user_type") == 3) {
			$user = $this->profileService->joinWithSemester($userID);
		}
		$user = $this->profileService->join($userID);

		if ($user) {
			return Response::json(['status' => 'success', 'records' => $user], 200);
		} else {
			return Response::json(['status' => 'error'], 404);
		}
	}
				
	public function update($id)
	{
		$validator = $this->profileService->updateValidation(Input::all());
		
		if ($validator->fails()) {
			Session::flash('message', "validation fail");
			
			return Redirect::back()->withErrors($validator);
		}

		if ($this->profileService->updateProfile(Input::all(), $id)) {
			Session::flash('success', 'Profile updated successfully');

			return Redirect::to('/'.$this->profileService->getURL().'/profiles/show/profile');
		} else {
			Session::flash('message', 'Failed to update Profile');

			return Redirect::back();
		}
	}

	public function editProfile()
	{
		return View::make("profile/editProfile", ['user' => $this->profileService->exist(Session::get('user_id'))]);
	}

	public function searchProfile($userID)
	{		
		if ($userID == "") {
			return Response::json(["status"=> "error", "message"=> "Registration Number not found"], 404);
		}

		if ($this->profileService->check($userID)) {			
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['status' => 'error', 'message' => "Profile Already exist"], 404);
		}
	}

	public function addName($id)
	{
		if ($id == "") {
			return Response::json(['status' => 'error', 'message' => "user Id not found"], 404);
		}

		$validator = $this->profileService->addNameValidation(Input::all());

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}

		if ($this->profileService->addName(Input::all(), $id)) {
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['status' => 'fail', 'message' => 'Failed to create user'], 500);
		}
	}
}