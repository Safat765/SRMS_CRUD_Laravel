<?php

use App\Services\CourseService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CourseController extends \BaseController
{
	private $courseService;
	
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
		$record = Input::all();
		
		return View::make('course.index', [
			'data' => $this->courseService->getAll(isset($record['search']) ? $record['search'] : '')
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = $this->courseService->checkValidation(Input::all());

		if ($validator->fails()) {			
			return Response::json(['errors' => $validator->errors()], 422);
		}
		
		if ($this->courseService->store(Input::all())) {			
			return Response::json(['status' => 'success',], 200);
		} else {			
			return Response::json(['status' => 'error'], 409);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if (!$this->courseService->findById($id)) {			
			return Response::json(['errors' => 'Course not found'], 404);
		}
		
		$validator = $this->courseService->updateValidation(Input::all(), $id);

		if ($validator->fails()) {			
			return Response::json(['errors' => $validator->errors()], 422);
		}

		if ($this->courseService->findByNameAndCredit(Input::all())) {			
			return Response::json(['errors' => 'Course already exist'], 409);
		}

		if ($this->courseService->update(Input::all(), $id)) {			
			return Response::json(['status' => 'success'], 200);
		} else {			
			return Response::json(['errors' => 'error'], 409);
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
		if (!$this->courseService->findById($id)) {			
			return Response::json(['status' => 'error'], 404);
		}
		
		if ($this->courseService->destroy($id)) {			
			return Response::json(['status' => 'success'], 200);
		} else {			
			return Response::json(['status' => 'error'], 404);
		}
	}
	
	public function status($id)
	{
		if (!$this->courseService->findById($id)) {			
			return Response::json(['status' => 'error'], 404);
		}
		
		if (!$this->courseService->status($id)) {			
			return Response::json(['status' => 'error'], 404);
		} else {			
			return Response::json(['status' => 'success'], 200);
		}
	}

	public function assignedCourse()
	{		
		return View::make('course/assigned', ['getCourses' => $this->courseService->assignedTo()]);		
	}
}
