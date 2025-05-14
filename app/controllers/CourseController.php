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
		
		return View::make('course.index', $this->courseService->getAll(isset($record['search']) ? $record['search'] : ''));
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
		} else {		
			if ($this->courseService->store(Input::all())) {			
				return Response::json(['status' => 'success', 'message' => 'Course created successfully'], 200);
			} else {			
				return Response::json(['status' => 'error', 'message' => 'Course not created'], 409);
			}
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
		$data = Input::all();

		if (!$this->courseService->findById($id)) {			
			return Response::json(['status' => 'error', 'message' => 'Course not found'], 404);
		} else {			
			$validator = $this->courseService->updateValidation($data, $id);

			if ($validator->fails()) {			
				return Response::json(['status' => $validator->errors()], 422);
			} elseif ($this->courseService->findByNameAndCredit($data)) {			
				return Response::json(['status' => 'error', 'message' => 'Course already exist'], 409);
			} else {
				if ($this->courseService->update($data, $id)) {			
					return Response::json(['status' => 'success', 'message' => 'Course updated successfully'], 200);
				} else {			
					return Response::json(['status' => 'error', 'message' => 'Course not updated'], 409);
				}
			}
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
			return Response::json(['status' => 'error', 'message' => 'Course not found'], 404);
		} else {		
			if ($this->courseService->destroy($id)) {			
				return Response::json(['status' => 'success', 'message' => 'Course deleted successfully'], 200);
			} else {			
				return Response::json(['status' => 'error', 'message' => 'Course not deleted'], 404);
			}
		}
	}
	
	public function status($id)
	{
		if (!$this->courseService->findById($id)) {			
			return Response::json(['status' => 'error', 'message' => 'Course not found'], 404);
		} else {		
			if (!$this->courseService->status($id)) {			
				return Response::json(['status' => 'error', 'message' => 'Course not updated'], 404);
			} else {			
				return Response::json(['status' => 'success', 'message' => 'Course updated successfully'], 200);
			}
		}
	}

	public function assignedCourse()
	{		
		return View::make('course/assigned', ['getCourses' => $this->courseService->assignedTo()]);		
	}
}
