<?php

use App\Models\Course;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class CourseController extends \BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$course = new Course();
		$search = Input::get('search');

		$ACTIVE = Course::STATUS_ACTIVE;
		$INACTIVE = Course::STATUS_INACTIVE;
		
		if ($search != '') {
			$data = $course->filter($search);			
			$totalCourse = $data['totalCourse'];
			$course = $data['course'];
		} else {
			$data = $course->showAll();			
			$totalCourse = $data['totalCourse'];
			$course = $data['course'];
		}

		$data = compact('course', 'totalCourse', 'search', 'ACTIVE', 'INACTIVE');

		return View::make('course.index')->with($data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:2',
			'credit' => 'required|numeric'
		], [
			'required' => 'The Course field is required.',
			'min' => 'The Course must be at least :min characters.',
			'numeric' => 'The Course must be a number.'	
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$course = new Course();
		$exist = $course->createCourse(Input::all());
		
		if ($exist) {
			Session::flash('success', 'Course created successfully');
			// return Redirect::to('courses');
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			Session::flash('message', 'Course already exist');
			return Redirect::back();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name = Input::get('name');

		$course = new Course();
		$course = $course->edit($id);
		
		if (!$course) {
			Session::flash('message', 'Course not found');
			return Redirect::back();
		}
		
		$validator = Validator::make(Input::all(), [
			'name' => 'required|min:1|unique:courses,name,' . $id . ',course_id',
			'credit' => 'required|numeric'
		], [
			'required' => 'The Course field is required.',
			'min' => 'The Course must be at least :min characters.',
			'numeric' => 'The Course must be a number.'	
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		
		$update = $course->updateCourse(Input::all(), $id);
		
		if ($update) {
			return Response::json([
				'status' => 'success',
			]);
		} else {
			return Response::json([
				'errors' => 'error'
			]);
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$course = new Course();
		$course = $course->edit($id);
		
		if (!$course) {
			Session::flash('message', 'Course not found');
			return Redirect::back();
		}
		$delete = $course->deleteCourse($id);
		
		if (!$delete) {
			Session::flash('message', 'Failed to delete course');
			return Response::json([
				'status' => 'error',
			]);
		} else{
			Session::flash('success', 'Course deleted successfully');
			return Response::json([
				'status' => 'success',
			]);
		}
	}
	
	public function status($id)
	{
		$course = new Course();
		$course = $course->edit($id);
		
		if (!$course) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$status = $course->statusUpdate($id);
		
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
