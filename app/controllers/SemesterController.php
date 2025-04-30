<?php

use App\Models\Semester;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
		
class SemesterController extends BaseController {
				
	public function index()
	{
		$semester = new Semester();
		$search = Input::get('search');
		
		if ($search != '') {
			$data = $semester->filter($search);
			
			$totalSemester = $data['totalSemester'];
			$semester = $data['semester'];
		} else {
			$data = $semester->showAll();
			$totalSemester = $data['totalSemester'];
			$semester = $data['semester'];
		}

		$data = compact('semester', 'totalSemester', 'search');

		return View::make('Semester/index')->with($data);
	}
				
	public function create()
	{
		$pageName = "Create Semester";			
		$url = url('/semesters');
		$data = compact('url', 'pageName');
		
		return View::make('Semester/create')->with($data);
	}
				
	public function store()
	{
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:3|unique:semesters,name'
		], [
			'required' => 'The Semester field is required.',
			'min' => 'The Semester must be at least :min characters.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$name = Input::get('name');
		$semester = new Semester();
		$exist = $semester->createSemester($name);
		
		if ($exist) {
			Session::flash('success', 'Semester created successfully');
			// return Redirect::to('semesters');
			return Response::json([
				'status' => 'success',
				'message'=> 'Semester created successfully'
			], 200);
		} else {
			Session::flash('message', 'Semester already exist');
			return Response::json([
				'status' => 'error',
				'message'=> 'Semester already exist'
			], 403);
		}
	}
				
	public function show($id)
	{
		// Show single item
	}
				
	public function edit($id)
	{
		$semester = new Semester();
		$semester = $semester->edit($id);
		$pageName = "Edit Semester";
		$url = url('/semesters/' . $id);
		$data = compact('semester', 'url', 'pageName');

		if ($semester) {
			return View::make('Semester/update')->with($data);
		}
		Session::flash('message', 'Semester not found');
		return Redirect::back();
	}
				
	public function update($id)
	{
		$semester = new Semester();
		$semester = $semester->edit($id);
		
		if (!$semester) {
			Session::flash('message', 'Semester not found');
			return Redirect::back();
		}
		
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:3|unique:semesters,name,'.$id.',semester_id',
		], [
			'required' => 'The Semester field is required.',
			'min' => 'The Semester must be at least :min characters.'
		]);
		
		// if ($validator->fails()) {
		// 	return Redirect::back()
		// 	->withErrors($validator);
		// }

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		// $name = Input::get('name');
		// $exist = $semester->searchName($name);

		// if ($exist) {
		// 	Session::flash('message', $name.' Semester already exist');
		// 	return Redirect::back();
		// }
		$update = $semester->updateSemester(Input::all(), $id);
		
		if ($update) {
			Session::flash('success', 'Semester updated successfully');
			// return Redirect::to('semesters');
			return Response::json([
				'status' => 'success',
			]);
		} else {
			Session::flash('message', 'Failed to update Semester');
			return Redirect::back();
		}
	}
				
	public function destroy($id)
	{
		$semester = new Semester();
		$semester = $semester->edit($id);
		
		if (!$semester) {
			Session::flash('message', 'Semester not found');
			return Redirect::back();
		}
		$delete = $semester->deleteSemester($id);
		
		if (!$delete) {
			Session::flash('message', 'Failed to delete Semester');
			// return Redirect::back();
			return Response::json([
				'status' => 'error',
			]);
		} else{
			Session::flash('success', 'Semester deleted successfully');
			// return Redirect::to('semesters');
			return Response::json([
				'status' => 'success',
			]);
		}
	}
}