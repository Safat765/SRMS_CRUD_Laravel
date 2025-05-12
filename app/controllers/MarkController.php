<?php

use App\Models\Mark;
use App\Models\Result;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Services\MarkService;

class MarkController extends BaseController
{
	protected $markService;

	public function __construct(MarkService $markService)
	{
		$this->markService = $markService;
	}
	
	public function index()
	{
		$service = $this->markService;
		$data = $service->getAllMarks();
			
		return View::make("mark.addMarks")->with($data);
	}

	public function courseView()
	{
		$service = $this->markService;
		$data = $service->courseView();
			
		return View::make("mark.courseList")->with($data);
	}
				
	public function createMark()
	{
		$service = $this->markService;
		$data = Input::all();
		$totalMarks = Input::get('totalMarks');
		$givenMarks = Input::get('givenMark');

		if (empty($givenMarks)) {
			return Response::json([
				'status' => 'error',
				'message' => 'Enter the Marks first'
			], 400);
		}

		if ($totalMarks < $givenMarks) {
			return Response::json([
				'status' => 'error',
				'message' => "Total marks must be within the range of 0 to $totalMarks"
			], 400);
		} elseif ($givenMarks < 0) {
			return Response::json([
				'status' => 'error',
				'message' => "Marks cannot be negative"
			], 400);
		}
		$exist = $service->checkMarks($data);

		if ($exist) {
			return Response::json([
				'status' => 'error',
				'message' => 'Marks already assigned for this student.'
			], 400);
		}
		$result = $service->createMark($data);

		if ($result) {
			$this->addResult($data['studentId']);
			return Response::json([
				'status' => 'success',
				'message' => 'Marks added successfully for student - "'. $data['username'].'" for the course of "'. $data['courseName'].' and result also added"'
			], 200);
		} else {
			return Response::json([
				'status' => 'error',
				'message' => 'Marks not added'
			], 500);
		}
	}

	public function addResult($studentId)
	{
		$this->markService->addResult($studentId);
	}

				
	public function store()
	{
		// Handle form submission
	}
				
	public function show($id)
	{
		$service = $this->markService;
		$records = $service->showMarks(Input::all(), $id);
		$pageName = "View Marks";

		if ($records) {
			$data = compact('records', 'pageName');
			return View::make('mark/View')->with($data);
		} else {
			Session::flash('message', 'Student not added');
			return Redirect::to('instructor/marks');
		}
	}
				
	public function students($courseId, $semesterId)
	{
		$service = $this->markService;
		$data = $service->students($courseId, $semesterId);
		p($data['marks']);
			
		return View::make("mark.studentList")->with($data);
	}
				
	public function edit($studentId)
	{
		$service = $this->markService;
		$records = Input::all();
		$examId = Input::get('examId');
        
        if (empty($examId)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Exam ID is required'
            ], 400);
        }
		$mark = new Mark();
		$records = $service->edit($records, $studentId);

		if ($records) {
			return Response::json([
				'status' => 'success',
				'records' => $records
			], 200); 
		} else {
			return Response::json([
				'status' => 'error',
				'message' => 'Student not found'
			], 404);
		}
	}
				
	public function update($id)
	{
		$service = $this->markService;
		$records = Input::all();
		$totalMarks = $records['totalMarks'];
		$givenMarks = $records['givenMark'];

		if ($totalMarks < $givenMarks) {
			return Response::json([
				'status'=> 'error',
				'message'=> "Total marks must me within the range of 0 to $totalMarks"
			]);
		} elseif ($givenMarks < 0) {
			return Response::json([
				'status'=> 'error',
				'message'=> "Total marks must me within the range of 0 to $totalMarks"
			]);
		} elseif (empty($givenMarks)) {
			return Response::json([
				'status'=> 'error',
				'message'=> "Enter the Marks first"
			]);
		}
		$exist = $service->checkExistMarks($id);

		if (!$exist) {
			return Response::json([
				'status'=> 'error',
				'message'=> 'Marks not assigned for this student. Assign the marks first'
			]);
		}
		$result = $service->update($records, $id);

		if ($result) {
			Session::flash('success', 'Marks Updated successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
			return Response::json([
				'status'=> 'success',
				'message'=> 'Marks Updated successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\""
			]);
		} else {
			Session::flash('message', 'Failes to update marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
			return Response::json([
				'status'=> 'error',
				'message'=> 'Failes to update marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\""
			]);
		}
	}
				
	public function destroy($id)
	{
		$service = $this->markService;
		$records = Input::all();
		$exist = $service->checkExistMarksForDelete($id, $records['examId']);

		if (!$exist) {
			Session::flash('message', 'Marks not assigned for this student. Assign the marks first');
			return Redirect::to('instructor/marks');
			die();
		}
		$result = $service->destroy($id, $records['examId']);

		if ($result) {
			Session::flash('success', 'Marks Deleted successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		} else {
			Session::flash('message', 'Failes to Delete marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		}

		return Redirect::to('/instructor/marks/all/students');
	}

	public function studentList()
	{
		$service = $this->markService;
		$data = $service->studentList();

		return View::make('mark/courseWiseStudent')->with($data);
	}
}