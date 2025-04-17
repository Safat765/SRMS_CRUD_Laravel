<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

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
				
	public function create()
	{
		$pageName = "Give Mark";			
		$url = url('/users');
		// Show create form
		$data = compact('url', 'pageName');
		
		return View::make('Mark/create')->with($data);
	}
				
	public function createMark()
	{
		p(Input::all());
		$pageName = "Give Mark";			
		$url = url('/users');
		// Show create form
		$data = compact('url', 'pageName');
		
		return View::make('Mark/create')->with($data);
	}
				
	public function store()
	{
		p(Input::all());
		// Handle form submission
	}
				
	// public function show($id)
	// {
	// 	echo $id;
	// 	$marks = new Mark();
	// 	$results = $marks->getStudents($id);
	// 	p($results);
	// 	$totalStudent = count($results);	
	// 	$data = compact('results', 'totalStudent');
			
	// 	return View::make("Mark.studentList")->with($data);
	// }
				
	public function students()
	{
		// p(Input::all());
		$semesterId = Input::get("semesterId");
		$courseId = Input::get("courseID");
		$marks = new Mark();
		$results = $marks->getStudents(Session::get("user_id"), $semesterId, $courseId);
		// p($results);
		$totalStudent = count($results);	
		echo "<br>".$totalStudent;
		$data = compact('results', 'totalStudent');
			
		return View::make("Mark.studentList")->with($data);
	}
				
	public function edit($id)
	{
		// Show edit form
	}
				
	public function update($id)
	{
		// Handle update
	}
				
	public function destroy($id)
	{
		// Handle deletion
	}
}