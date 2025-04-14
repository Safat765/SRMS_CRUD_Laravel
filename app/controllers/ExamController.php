<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ExamController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$exam = new Exam();
		$allTables = $exam->join();		
		// $pageName = "Create Exam";			
		// $url = url('/exams');
		// $examType = [
		// 	$mid = Exam::EXAM_TYPE_MID => 'Mid',
		// 	$quiz = Exam::EXAM_TYPE_QUIZ => 'Quiz',
		// 	$viva = Exam::EXAM_TYPE_VIVA => 'Viva',
		// 	$final = Exam::EXAM_TYPE_FINAL => 'Final'
		// ];
		// $data = compact('url', 'pageName', 'allTables', 'examType');

		// return View::make('Exam/create')->with($data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
