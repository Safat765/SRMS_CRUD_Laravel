<?php

use App\Models\Profile;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Services\ProfileService;

class ProfileController extends BaseController
{
	protected $profileService;

	public function __construct(ProfileService $profileService)
	{
		$this->profileService = $profileService;
	}

	public function index()
	{
		// Show all items
	}
				
	public function create()
	{
		$service = $this->profileService;
		$data = $service->createProfile();

		return View::make("profile/changePassword")->with($data);
	}
				
	public function store()
	{
		$service = $this->profileService;
		$data = $service->checkValidation(Input::all());

		if ($data->fails()) {
			return Redirect::back()
				->withErrors($data);
		}
		$currentPassword = Session::get("password");
		$verify = $service->checkPassword(Input::get("oldPassword"), $currentPassword);

		if ($verify) {
			$match = $service->matchPassword(Input::get("newPassword"), Input::get("oldPassword"));
			
			if ($match) {
				Session::flash("message", "Previous password and New Password can not be same");

				return Redirect::back();
				die();
			} else {
				$update = $service->changePassword(Input::get("newPassword"));

				if ($update) {
					Session::flash("success", "Password Changed Successfully");
					$addURL = $service->getURL();

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
		$service = $this->profileService;
		$profile = new Profile();
		if (Session::get("user_type") == 3) {
			$user = $service->joinProfileWithSemester($userID);
		}
		$user = $service->joinProfile($userID);

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
		$service = $this->profileService;
		$addURL = $service->getURL();

		$validator = $service->updateValidation(Input::all());
		
		if ($validator->fails()) {
			Session::flash('message', "validation fail");
			return Redirect::back()
			->withErrors($validator);
		}
		$update = $service->updateProfile(Input::all(), $id);

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
		$service = $this->profileService;
		$user = $service->existProfile(Session::get('user_id'));

		return View::make("profile/editProfile")->with('user', $user);
	}

	public function searchProfile($userID)
	{
		$service = $this->profileService;
		if ($userID == "") {
			return Response::json([
				"status"=> "error",
				"message"=> "Registration Number not found"
			], 404);
		}
		$exist = $service->checkProfile($userID);

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
		$service = $this->profileService;

		if ($id == "") {
			return Response::json([
				"status"=> "error",
				"message"=> "user Id not found"
			], 404);
		}

		$validator = $service->addNameValidation(Input::all());

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$update = $service->addName(Input::all(), $id);

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