<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends BaseController
{				
	public function index()
	{
		// Show all items
	}
				
	public function create()
	{
		$pageName = "Change Password";			
		$url = url('/profiles');
		$data = compact('url' ,'pageName');
		return View::make("Profile/changePassword")->with($data);
	}
				
	public function store()
	{
		p(Input::all());
		$currentPassword = Session::get("password");
		echo"". $currentPassword ."<br>";
		if (password_verify(Input::get("oldPassword"), $currentPassword)) {
			echo "Password Match";
		} else {
			Session::flash("message", "Incorrect Previous Password");
			return Redirect::back();
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

	public function changePassword()
	{
		return View::make("Profile/changePassword");
	}
}