<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class MarkController extends BaseController
{				
	public function index()
	{
		$url = '/marks/students';
		$marks = new Mark();
		$results = $marks->assignedCourses(Session::get("user_id"));
		// p($results);
		$totalCourse = count($results);
		$userType = Session::get("user_type");
		$data = compact('userType', 'results', 'totalCourse', 'url');
			
		return View::make("Mark.courseList")->with($data);
	}
				
	// public function create()
	// {
	// 	$pageName = "Give Mark";			
	// 	$url = url('/users');
	// 	// Show create form
	// 	$data = compact('url', 'pageName');
		
	// 	return View::make('Mark/create')->with($data);
	// }
				
	public function createMark()
	{
		// p(Input::all());
		$data = Input::all();
		$totalMarks = Input::get('totalMarks');
		$givenMarks = Input::get('givenMark');

		if ($totalMarks < $givenMarks) {
			Session::flash('message', "Total marks must me within the range of 0 to $totalMarks");
			return Redirect::to('marks');
		} elseif ($givenMarks < 0) {
			Session::flash('message', "Total marks must me within the range of 0 to $totalMarks");
			return Redirect::to('marks');
		} elseif (empty($givenMarks)) {
			Session::flash('message', "Enter the Marks first");
			return Redirect::to('marks');
		}
		$percentage = ($givenMarks / $totalMarks) * 100;
		// echo "<br>". $percentage;

		if ($percentage >= 90) $gpa = 4.00;
		elseif ($percentage >= 85) $gpa = 3.75;
		elseif ($percentage >= 80) $gpa = 3.50;
		elseif ($percentage >= 75) $gpa = 3.25;
		elseif ($percentage >= 70) $gpa = 3.00;
		elseif ($percentage >= 65) $gpa = 2.75;
		elseif ($percentage >= 60) $gpa = 2.50;
		elseif ($percentage >= 50) $gpa = 2.25;
		else $gpa = 0.00;

		// echo "<br>". $gpa;
		$mark = new Mark();
		$exist = $mark->existOrNot($data['studentId'], $data['examId']);
		p($exist);

		if ($exist) {
			Session::flash('message', 'Marks already assigned for this student - '. $data['username']);
			return Redirect::to('marks');
			die();
		}
		$result = $mark->createMarks($data, $gpa);
		// p($result);

		if ($result) {
			Session::flash('success', 'Marks added successfully for - '. $data['username']);
		} else {
			Session::flash('message', 'Marks not added');
		}

		return Redirect::to('marks');
		die();
	}
				
	public function store()
	{

		
		// Handle form submission
	}
				
	public function show($id)
	{
		$records = Input::all();
		$studentId = $id;
		// p($records);
		$mark = new Mark();
		$records = $mark->editMarks($studentId, $records['examId']);
		// p($records);
		// echo "<br>";
		$pageName = "View Marks";

		if ($records) {
			$data = compact('records', 'pageName');
			return View::make('Mark/View')->with($data);
		} else {
			Session::flash('message', 'Student not added');
			return Redirect::to('marks');
		}
	}

	public function addMark()
	{
		$records = Input::all();
		// p($records);
		$pageName = "Give Mark";			
		$url = URL::route('marks.store');
		// Show create form
		$data = compact('records', 'url', 'pageName');
		
		return View::make('Mark/create')->with($data);
		die();
	}
				
	public function students()
	{
		// p(Input::all());
		$semesterId = Input::get("semesterId");
		$courseId = Input::get("courseID");
		$marks = new Mark();
		$results = $marks->getStudents(Session::get("user_id"), $semesterId, $courseId);
		// p($results);

		$userId = [];
		foreach ($results as $result) {
			array_push($userId, $result->user_id);
		}
		// p($userId);

		foreach ($results as $result) {
			$examId = $result->exam_id;
			break;
		}
		// echo "<br>". $examId;

		$marks = $marks->getMarks($userId, $examId);
		// p($marks);

		$totalStudent = count($results);
		$data = compact('results', 'totalStudent', 'marks', 'userId');
			
		return View::make("Mark.studentList")->with($data);
	}
				
	public function edit($studentId)
	{
		// echo "edit --".$studentId;
		$records = Input::all();
		// p($records);
		// Show edit form
		$mark = new Mark();
		$records = $mark->editMarks($studentId, $records['examId']);
		// p($records);
		// echo "<br>";
		$pageName = "Edit Marks";

		if ($records) {
			$data = compact('records', 'pageName');
			return View::make('Mark/update')->with($data);
		} else {
			Session::flash('message', 'Student not added');
			return Redirect::to('marks');
		}
	}
				
	public function update($id)
	{
		// Handle update
		echo "update --".$id;
		$records = Input::all();
		p($records);
		$totalMarks = $records['totalMarks'];
		$givenMarks = $records['givenMark'];

		if ($totalMarks < $givenMarks) {
			Session::flash('message', "Total marks must me within the range of 0 to $totalMarks");
			return Redirect::to('marks');
		} elseif ($givenMarks < 0) {
			Session::flash('message', "Total marks must me within the range of 0 to $totalMarks");
			return Redirect::to('marks');
		} elseif (empty($givenMarks)) {
			Session::flash('message', "Enter the Marks first");
			return Redirect::to('marks');
		}
		$percentage = ($givenMarks / $totalMarks) * 100;
		// echo "<br>". $percentage;

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
		// p($exist);

		if (!$exist) {
			Session::flash('message', 'Marks not assigned for this student. Assign the marks first');
			return Redirect::to('marks');
			die();
		}
		$result = $mark->updateMarks($id, $givenMarks, $gpa);
		p($result);

		if ($result) {
			Session::flash('success', 'Marks Updated successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		} else {
			Session::flash('message', 'Failes to update marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		}

		return Redirect::to('marks');
		die();
	}
				
	public function destroy($id)
	{
		echo "destroy --".$id;
		$records = Input::all();
		p($records);
		$mark = new Mark();
		$exist = $mark->marksExistOrNotForDelete($id, $records['examId']);
		p($exist);

		if (!$exist) {
			Session::flash('message', 'Marks not assigned for this student. Assign the marks first');
			return Redirect::to('marks');
			die();
		}
		$result = $mark->deleteMarks($id, $records['examId']);
		// p($result);

		if ($result) {
			Session::flash('success', 'Marks Deleted successfully for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		} else {
			Session::flash('message', 'Failes to Delete marks for - "'. $records['username']."\" for the course of \"". $records['courseName']."\"");
		}
		return Redirect::to('marks');
		die();
	}
}