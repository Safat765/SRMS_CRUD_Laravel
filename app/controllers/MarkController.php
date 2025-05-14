<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Services\MarkService;

class MarkController extends BaseController
{
	private $markService;

	public function __construct(MarkService $markService)
	{
		$this->markService = $markService;
	}
	
	public function index()
	{			
		return View::make("mark.addMarks", ['data' => $this->markService->getAll()]);
	}

	public function courseView()
	{
		return View::make("mark.courseList", ['data' => $this->markService->getAll()]);
	}
				
	public function createMark()
	{
		$data = Input::all();
		$totalMarks = isset($data['totalMarks']) ? $data['totalMarks'] : null;
		$givenMarks = isset($data['givenMark']) ? $data['givenMark'] : null;
		$username = isset($data['username']) ? $data['username'] : null;
		$courseName = isset($data['courseName']) ? $data['courseName'] : null;

		if (empty($givenMarks)) {
			return Response::json(['status' => 'error', 'message' => 'Enter the Marks first'], 400);
		} else {

			if ($totalMarks < $givenMarks) {
				return Response::json(['status' => 'error', 'message' => "Total marks must be within the range of 0 to $totalMarks"], 400);
			} elseif ($givenMarks < 0) {
				return Response::json(['status' => 'error', 'message' => "Marks cannot be negative"], 400);
			} else {
				if ($this->markService->checkMarks($data)) {
					return Response::json(['status' => 'error', 'message' => 'Marks already assigned for this student.'], 400);
				}

				if ($this->markService->createMark($data)) {
					$this->addResult($data['studentId']);
					return Response::json(['status' => 'success', 'message' => 'Marks added successfully for student - "'. $username .'" for the course of "'. $courseName .'" and result also added'], 200);
				} else {
					return Response::json(['status' => 'error', 'message' => 'Marks not added'], 500);
				}
			}
		}
	}

	public function addResult($studentId)
	{
		$this->markService->addResult($studentId);
	}
				
	public function show($id)
	{
		$records = $this->markService->show(Input::all(), $id);

		if ($records) {
			return View::make('mark/View', ['records' => $records, 'pageName' => "View Marks"]);
		} else {
			Session::flash('message', 'Student not added');
			return Redirect::to('instructor/marks');
		}
	}
				
	public function students($courseId, $semesterId)
	{			
		return View::make("mark.studentList", ['data' => $this->markService->students($courseId, $semesterId)]);
	}
				
	public function edit($studentId)
	{
		$records = Input::all();
		$examId = isset($records['examId']) ? $records['examId'] : null;
        
        if (empty($examId)) {
            return Response::json(['status' => 'error', 'message' => 'Exam ID is required'], 400);
        }
		$records = $this->markService->edit($records, $studentId);

		if ($records) {
			return Response::json(['status' => 'success','records' => $records], 200); 
		} else {
			return Response::json(['status' => 'error','message' => 'Student not found'], 404);
		}
	}
				
	public function update($id)
	{
		$records = Input::all();
		$totalMarks = isset($records['totalMarks']) ? $records['totalMarks'] : null;
		$givenMarks = isset($records['givenMark']) ? $records['givenMark'] : null;
		$username = isset($records['username']) ? $records['username'] : null;
		$courseName = isset($records['courseName']) ? $records['courseName'] : null;

		if ($totalMarks < $givenMarks) {
			return Response::json(['status' => 'error', 'message' => "Total marks must me within the range of 0 to $totalMarks"], 400);
		} elseif ($givenMarks < 0) {
			return Response::json(['status' => 'error', 'message' => "Total marks must me within the range of 0 to $totalMarks"], 400);
		} elseif (empty($givenMarks)) {
			return Response::json(['status' => 'error', 'message' => "Enter the Marks first"], 400);
		}

		if (!$this->markService->checkExist($id)) {
			return Response::json(['status' => 'error', 'message' => 'Marks not assigned for this student. Assign the marks first'], 400);
		}

		if ($this->markService->update($records, $id)) {
			return Response::json(['status' => 'success', 'message' => 'Marks Updated successfully for - "'. $username."\" for the course of \"". $courseName."\""], 200);
		} else {
			return Response::json(['status' => 'error', 'message' => 'Failes to update marks for - "'. $username."\" for the course of \"". $courseName."\""], 500);
		}
	}
				
	public function destroy($id)
	{
		$records = Input::all();
		$examId = isset($records['examId']) ? $records['examId'] : null;
		$username = isset($records['username']) ? $records['username'] : null;
		$courseName = isset($records['courseName']) ? $records['courseName'] : null;
		
		if (!$this->markService->checkExistMarksForDelete($id, $examId)) {
			Session::flash('message', 'Marks not assigned for this student. Assign the marks first');
			return Redirect::to('instructor/marks');
			die();
		}

		if ($this->markService->destroy($id, $examId)) {
			Session::flash('success', 'Marks Deleted successfully for - "'. $username."\" for the course of \"". $courseName."\"");
		} else {
			Session::flash('message', 'Failes to Delete marks for - "'. $username."\" for the course of \"". $courseName."\"");
		}

		return Redirect::to('/instructor/marks/all/students');
	}

	public function studentList()
	{
		return View::make('mark/courseWiseStudent', ['data' => $this->markService->studentList()]);
	}
}