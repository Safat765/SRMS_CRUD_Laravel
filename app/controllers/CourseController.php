<?php

use App\Services\CourseService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CourseController extends \BaseController
{
	protected $courseService;
	
	public function __construct(CourseService $courseService)
	{
		$this->courseService = $courseService;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$service = $this->courseService;
		$search = Input::get('search');
		$data = $service->getAllCourse($search);

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
		$service = $this->courseService;
		$validator = $service->checkValidation(Input::all());

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$exist = $service->storeCourse(Input::all());
		
		if ($exist) {
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			return Response::json([
				'status' => 'error'
			], 409);
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
		$service = $this->courseService;
		$course = $service->checkCourse($id);

		if (!$course) {
			return Response::json([
				'errors' => 'Course not found'
			], 404);
		}
		$validator = $service->updateValidation(Input::all(), $id);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$exist = $service->checkCourseName(Input::get('name'));

		if ($exist) {
			return Response::json([
				'errors' => 'Course already exist'
			]);
		}
		$update = $service->updateCourse(Input::all(), $id);

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
		$service = $this->courseService;
		$course = $service->checkCourse($id);
		
		if (!$course) {
			Response::json([
				'status' => 'error'
			], 404);
		}
		$delete = $service->destroyCourse($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
			], 404);
		} else{
			return Response::json([
				'status' => 'success',
			], 200);
		}
	}
	
	public function status($id)
	{
		$service = $this->courseService;
		$course = $service->checkCourse($id);
		
		if (!$course) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$status = $service->statusUpdate($id);
		
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

	public function assignedCourse()
	{
		$service = $this->courseService;
		$getCourses = $service->assignedCourse();
		
		return View::make('course/assigned')->with(['getCourses' => $getCourses]);
	}
}
