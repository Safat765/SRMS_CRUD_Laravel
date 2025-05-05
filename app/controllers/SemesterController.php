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

		return View::make('semester/index')->with($data);
	}
				
	public function create()
	{
		//
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
			return Response::json([
				'status' => 'success',
				'message'=> 'Semester created successfully'
			], 200);
		} else {
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
		//
	}
				
	public function update($id)
	{
		$semester = new Semester();
		$semester = $semester->edit($id);
		
		if (!$semester) {			
			return Response::json([
				'status' => 'error',
			]);
		}
		
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:3|unique:semesters,name,'.$id.',semester_id',
		], [
			'required' => 'The Semester field is required.',
			'min' => 'The Semester must be at least :min characters.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$update = $semester->updateSemester(Input::all(), $id);
		
		if ($update) {
			return Response::json([
				'status' => 'success',
			]);
		} else {
			return Response::json([
				'status' => 'error',
			]);
		}
	}
				
	public function destroy($id)
	{
		$semester = new Semester();
		$semester = $semester->edit($id);
		
		if (!$semester) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$delete = $semester->deleteSemester($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
			]);
		} else{
			return Response::json([
				'status' => 'success',
			]);
		}
	}
}