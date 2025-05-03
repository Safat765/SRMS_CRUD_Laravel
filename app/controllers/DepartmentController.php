<?php

use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;


class DepartmentController extends BaseController
{				
	public function index()
	{
		$department = new Department();
		$search = Input::get('search');
		
		if ($search != '') {
			$data = $department->filter($search);
			$totalDepartment = $data['totalDepartment'];
			$department = $data['department'];
		} else {
			$data = $department->showAll();
			$totalDepartment = $data['totalDepartment'];
			$department = $data['department'];
		}

		$data = compact('department', 'totalDepartment', 'search');

		return View::make('Department.index')->with($data);
	}
				
	public function create()
	{
		$pageName = "Create Department";			
		$url = url('/departments');
		$data = compact('url', 'pageName');
		
		return View::make('Department/create')->with($data);
	}
				
	public function store()
	{
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:3|unique:departments'
		], [
			'required' => 'The Department field is required.',
			'min' => 'The Department must be at least :min characters.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$name = Input::get('name');
		$department = new Department();
		$exist = $department->createDepartment($name);
		
		if ($exist) {
			Session::flash('success', 'Department created successfully');
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			Session::flash('message', 'Department already exist');
			return Redirect::back();
		}
	}
				
	public function show($id)
	{
		echo "Hello show" . $id;
	}
				
	public function edit($id)
	{
		$department = new Department();
		$department = $department->edit($id);
		$pageName = "Edit Department";
		$url = url('/departments/' . $id);
		$data = compact('department', 'url', 'pageName');

		if ($department) {
			return View::make('Department/update')->with($data);
		}
		Session::flash('message', 'Department not found');
		return Redirect::back();
	}
				
	public function update($id)
	{
		$department = new Department();
		$department = $department->edit($id);
		
		if (!$department) {
			Session::flash('message', 'Department not found');
			return Redirect::back();
		}
		
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:3|unique:departments,name,'.$id.',department_id',
		], [
			'required' => 'The Department field is required.',
			'min' => 'The Department must be at least :min characters.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$name = Input::get('name');
		$exist = $department->searchName($name);

		if ($exist) {
			Session::flash('message', $name.' Department already exist');
			return Redirect::back();
		}
		$update = $department->updateDepartment(Input::all(), $id);
		
		if ($update) {
			Session::flash('success', 'Department updated successfully');
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			Session::flash('message', 'Failed to update department');
			return Redirect::back();
		}
	}
				
	public function destroy($id)
	{
		$department = new Department();
		$department = $department->edit($id);
		
		if (!$department) {
			Session::flash('message', 'Department not found');
			return Redirect::back();
		}
		$delete = $department->deleteDepartment($id);
		
		if (!$delete) {
			Session::flash('message', 'Failed to delete department');
			return Response::json([
				'status' => 'error',
			]);
		} else{
			Session::flash('success', 'Department deleted successfully');
			return Response::json([
				'status' => 'success',
			]);
		}
	}
}