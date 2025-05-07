<?php

use App\Models\Mark;
use App\Models\Result;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class MarkController extends BaseController
{				
	public function index()
	{
		$marks = new Mark();
		$results = $marks->assignedCourses(Session::get("user_id"));
		$totalCourse = count($results);
		$userType = Session::get("user_type");
		$data = compact('userType', 'results', 'totalCourse');
			
		return View::make("mark.addMarks")->with($data);
	}
	public function courseView()
	{
		$marks = new Mark();
		$results = $marks->assignedCourses(Session::get("user_id"));
		$totalCourse = count($results);
		$userType = Session::get("user_type");
		$data = compact('userType', 'results', 'totalCourse', 'url');
			
		return View::make("mark.courseList")->with($data);
	}
				
	public function createMark()
	{
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

		$percentage = ($givenMarks / $totalMarks) * 100;

		if ($percentage >= 90) $gpa = 4.00;
		elseif ($percentage >= 85) $gpa = 3.75;
		elseif ($percentage >= 80) $gpa = 3.50;
		elseif ($percentage >= 75) $gpa = 3.25;
		elseif ($percentage >= 70) $gpa = 3.00;
		elseif ($percentage >= 65) $gpa = 2.75;
		elseif ($percentage >= 60) $gpa = 2.50;
		elseif ($percentage >= 50) $gpa = 2.25;
		else $gpa = 0.00;

		$mark = new Mark();
		$exist = $mark->existOrNot($data['studentId'], $data['examId']);

		if ($exist) {
			return Response::json([
				'status' => 'error',
				'message' => 'Marks already assigned for this student.'
			], 400);
		}
		$result = $mark->createMarks($data, $gpa);

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
		$result = new Result();
		$records = $result->results($studentId);
		$info = [];
		foreach ($records as $record) {
			array_push($info, $record->gpa);
		}
		
		$cgpSum = [];
		$credit = [];
		foreach ($records as $record) {
			array_push($credit, $record->credit);
			$totalGpa = $record->gpa * $record->credit;
			array_push($cgpSum, $totalGpa);
		}

		$credit = array_sum($credit);
		$gpa = array_sum($cgpSum);
		$CGPA = $gpa / $credit;

		$get = $result->resultExistonNot($studentId);
		if ($get) {
			$result->updateResult($studentId, $CGPA);
		} else {
			$result->createResult($studentId, $CGPA);
		}
	}

				
	public function store()
	{
		// Handle form submission
	}
				
	public function show($id)
	{
		$records = Input::all();
		$studentId = $id;
		$mark = new Mark();
		$records = $mark->editMarks($studentId, $records['examId']);
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
		$marks = new Mark();
		$results = $marks->getStudents(Session::get("user_id"), $semesterId, $courseId);
		$userId = [];

		foreach ($results as $result) {
			array_push($userId, $result->user_id);
		}

		foreach ($results as $result) {
			$examId = $result->exam_id;
			break;
		}
		$marks = $marks->getMarks($userId, $examId);
		$totalStudent = count($results);
		$data = compact('results', 'totalStudent', 'marks', 'userId');
			
		return View::make("mark.studentList")->with($data);
	}
				
	public function edit($studentId)
	{
		$records = Input::all();
		$examId = Input::get('examId');
        
        if (empty($examId)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Exam ID is required'
            ], 400);
        }
		$mark = new Mark();
		$records = $mark->editMarks($studentId, $records['examId']);

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
		$percentage = ($givenMarks / $totalMarks) * 100;

		if ($percentage >= 90) $gpa = 4.00;
		elseif ($percentage >= 85) $gpa = 3.75;
		elseif ($percentage >= 80) $gpa = 3.50;
		elseif ($percentage >= 75) $gpa = 3.25;
		elseif ($percentage >= 70) $gpa = 3.00;
		elseif ($percentage >= 65) $gpa = 2.75;
		elseif ($percentage >= 60) $gpa = 2.50;
		elseif ($percentage >= 50) $gpa = 2.25;
		else $gpa = 0.00;
		
		$mark = new Mark();
		$exist = $mark->marksExistOrNot($id);

		if (!$exist) {
			return Response::json([
				'status'=> 'error',
				'message'=> 'Marks not assigned for this student. Assign the marks first'
			]);
		}
		$result = $mark->updateMarks($id, $givenMarks, $gpa);

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
		$records = Input::all();
		$mark = new Mark();
		$exist = $mark->marksExistOrNotForDelete($id, $records['examId']);

		if (!$exist) {
			Session::flash('message', 'Marks not assigned for this student. Assign the marks first');
			return Redirect::to('instructor/marks');
			die();
		}
		$result = $mark->deleteMarks($id, $records['examId']);

		if ($result) {
			Session::flash('success', 'Marks Deleted successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		} else {
			Session::flash('message', 'Failes to Delete marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		}

		return Redirect::to('/instructor/marks/all/students');
	}

	public function studentList()
	{
		$marks = new Mark();
		$results = $marks->viewMarks(Session::get("user_id"));
		
		$groupedResults = [];
		foreach ($results as $result) {
			$groupedResults[$result->course_name][] = $result;
		}

		return View::make('mark/courseWiseStudent')->with([
			'results'=> $results,
			'groupedResults'=> $groupedResults
		]);
	}
}