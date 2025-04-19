<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
			// $data = $exam->showAll();
			$totalExams = $results['totalExams'];
			$exams = $results['results'];
		}

		$data = compact('exams', 'totalExams', 'search');

		return View::make('Exam.index')->with($data);
	}
	
	
	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		$exam = new Exam();	
		$pageName = "Create Exam";			
		$url = url('/exams');
		$examType = [
			"Mid" => Exam::EXAM_TYPE_MID,
			"Quiz" => Exam::EXAM_TYPE_QUIZ,
			"Viva" => Exam::EXAM_TYPE_VIVA,
			"Final" => Exam::EXAM_TYPE_FINAL
		];
		$data = compact('url', 'pageName', 'allTables', 'examType');
		
		return View::make('Exam/create')->with($data);
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
			return Redirect::back()
			->withErrors($validator);
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
				return Redirect::to('exams');
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
		$exams = $exam->edit($id);
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
		$exams = $exam->edit($id);
		
		if (!$exams) {
			Session::flash('message', 'Exam not found');
			return Redirect::back();
		}		
		$validator = Validator::make(Input::aLL(), [
			'courseId' => 'required',
			'examTitle' => 'required|min:3|max:100',
			'departmentId' => 'required',
			'semesterId' => 'required',
			'credit' => 'required|numeric',
			'marks' => 'required|numeric',
			'instructorId' => 'required'
		], 
		[
			'required' => 'The :attribute field is required.',
			'numeric' => 'The :attribute must be a number.',
			'in' => 'Please select a valid :attribute.'
		]);
		
		if ($validator->fails()) {
			Session::flash('message', 'Fill up the criteria');
			return Redirect::back()
			->withErrors($validator);
		}
		$update = $exam->updateExam(Input::all(), $id);

		if ($update) {
			Session::flash('success', 'Exam updated successfully');
			return Redirect::to('exams');
		} else {
			Session::flash('message', 'Failed to update exam');
			return Redirect::back();
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
		$exam = $exam->edit($id);
		
		if (!$exam) {
			Session::flash('message', 'Exam not found');
			return Redirect::back();
		}
		$delete = $exam->deleteExam($id);
		
		if (!$delete) {
			Session::flash('message', 'Failed to delete Exam');
			return Redirect::back();
		} else{
			Session::flash('success', 'Exam deleted successfully');
			return Redirect::to('exams');
		}
	}
	
	
}
