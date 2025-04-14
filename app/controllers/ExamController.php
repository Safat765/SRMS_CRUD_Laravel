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
		echo "Hello";
		// $exam = new Exam();
		// $search = Input::get('search');

		$examCount = Exam::all();
		$totalExams = $examCount->count();
		$exams = $examCount->paginate(5);
		
		// if ($search != '') {
		// 	$data = $exam->filter($search);
			
		// 	$totalExams = $data['totalExams'];
		// 	$exams = $data['exams'];
		// } else {
		// 	$data = $exam->showAll();
		// 	$totalExams = $data['totalExams'];
		// 	$exams = $data['exams'];
		// }
		echo p($totalExams);

		// $data = compact('exams', 'totalExams', 'search');

		// return View::make('User.index')->with($data);
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
		$data = Input::all();
		$createdBy = 11;
		$exam = new Exam();
		$exist = $exam->searchName($data['examTitle'], $data['semesterId']);
		

		echo $exist;

		if ($exist) {
			Session::flash('message', 'Exam already exists');
			return Redirect::back();
		}
		$exist = $exam->createExam($data, $createdBy);

		echo $exist;
		
		if ($exist) {
			Session::flash('success', 'Exam created successfully');
			return Redirect::back();
		} else {
			Session::flash('message', 'Failed to create exam');
			return Redirect::back();
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
		//
	}
	
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id)
	{
		//
	}
	
	
}
