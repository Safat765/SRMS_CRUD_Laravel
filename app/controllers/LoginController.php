<?php

use Illuminate\Support\Facades\View;
		
class LoginController extends BaseController {
				
	public function index()
	{
		return View::make('login');
	}
				
	public function create()
	{
		// Show create form
	}
				
	public function store()
	{
		// Handle form submission
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
}