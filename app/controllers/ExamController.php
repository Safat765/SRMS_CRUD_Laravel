<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\Exam;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class ExamController extends \BaseController {
	
	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$exam = new Exam();
		$search = Input::get('search');		
		$results = $exam->joinTables();
		
		if ($search != '') {
			$data = $exam->filter($search);
			
			$totalExams = $data['totalExams'];
			$exams = $data['exams'];
		} else {		
			$results = $exam->joinTables();
			$totalExams = $results['totalExams'];
			$exams = $results['results'];
		}
		$examType = [
			"Mid" => Exam::EXAM_TYPE_MID,
			"Quiz" => Exam::EXAM_TYPE_QUIZ,
			"Viva" => Exam::EXAM_TYPE_VIVA,
			"Final" => Exam::EXAM_TYPE_FINAL
		];
		$list = [
			'courses' => Course::lists('name', 'course_id'),
			'department' => Department::lists('name', 'department_id'),
			'semester' => Semester::lists('name', 'semester_id'),
			'instructor' => User::where('user_type', 2)->lists('username', 'user_id')
		];

		$data = compact('exams', 'totalExams', 'search', 'examType', 'list');

		return View::make('exam.index')->with($data);
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
		$validator = Validator::make(Input::aLL(), [
			'courseId' => 'required',
			'examTitle' => 'required|min:3|max:100',
			'departmentId' => 'required',
			'semesterId' => 'required',
			'examType' => 'required|in:1,2,3,4',
			'credit' => 'required|numeric',
			'marks' => 'required|numeric',
			'instructorId' => 'required'
		], [
			'required' => 'The :attribute field is required.',
			'numeric' => 'The :attribute must be a number.',
			'in' => 'Please select a valid :attribute.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$exam = new Exam();
		$data = Input::all();
		$createdBy = 1;
		$exist = $exam->searchName($data['courseId'], $data['departmentId'], $data['semesterId'], $data['examType']);

		if ($exist) {
			Session::flash('message', 'Exam already exists');
			return Redirect::to('exams/create');
		} else {
			$create = $exam->createExam($data, $createdBy);
			
			if ($create) {
				$exam = new Exam();
				Session::flash('success', 'Exam created successfully');
				// return Redirect::to('exams');
				return Response::json([
					'status' => 'success',
				], 200);
			} else {
				Session::flash('message', 'Failed to create exam');
				return Redirect::back();
			}
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
		$exam = new Exam();
		$pageName = "Edit Exam";		
		$url = url('/exams/'.$id);
		$exams = $exam->editFind($id);
		$data = compact('exams', 'url', 'pageName');

		if (empty($exams) || $exams->count() == 0) {
			Session::flash('message', 'Exam not found');
			return Redirect::back();
		}
		return View::make('Exam/update')->with($data);
	}
	
	
	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id)
	{
		$exam = new Exam();
		$examFind = $exam->editFind($id);

		if (!$examFind) {
			return Response::json([
				'status' => 'error',
				'message' => 'Exam not found'
			], 404);
		}

		$validator = Validator::make(Input::all(), [
			'courseId' => 'required',
			'examTitle' => 'required|min:3|max:100',
			'departmentId' => 'required',
			'semesterId' => 'required',
			'credit' => 'required|numeric',
			'examType' => 'required|in:1,2,3,4',
			'marks' => 'required|numeric',
			'instructorId' => 'required'
		],
		[
			'required' => 'The :attribute field is required.',
			'numeric' => 'The :attribute must be a number.',
			'in' => 'Please select a valid :attribute.'
		]);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}

		$update = $exam->updateExam(Input::all(), $id);

		if ($update) {
			return Response::json([
				'status' => 'success',
				'message' => 'Exam updated successfully'
			], 200);
		} else {
			return Response::json([
				'status' => 'error',
				'message' => 'Failed to update exam'
			], 500);
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
		$exam = new Exam();
		$exam = $exam->editFind($id);
		
		if (!$exam) {
			Session::flash('message', 'Exam not found');
			return Redirect::back();
		}
		$delete = $exam->deleteExam($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
			], 400);
		} else{
			return Response::json([
				'status' => 'success',
			], 200);
		}
	}
}