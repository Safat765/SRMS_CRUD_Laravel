<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Services\ExamService;
use Illuminate\Support\Facades\Session;

class ExamController extends \BaseController
{
	protected $examService;

	public function __construct(ExamService $examService)
	{
		$this->examService = $examService;
	}
	
	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$service = $this->examService;
		$search = Input::get('search');
		$data = $service->getAllExams($search);
		
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
		$service = $this->examService;
		$validator = $service->checkValidation(Input::all());
		
		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$data = Input::all();
		$createdBy = Session::get('user_id');
		$exist = $service->searchExamByName($data);

		if ($exist) {
			return Response::json([
				'status' => 'error'
			], 403);
		} else {
			$create = $service->storeExam($data, $createdBy);
			
			if ($create) {
				return Response::json([
					'status' => 'success',
				], 200);
			} else {
				return Response::json([
					'status' => 'error'
				], 409);
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
		$service = $this->examService;
		$examFind = $service->find($id);

		if (!$examFind) {
			return Response::json([
				'status' => 'error',
				'message' => 'Exam not found'
			], 404);
		}
		$validator = $service->updateValidation(Input::all());

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$update = $service->updateExam(Input::all(), $id);

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
		$service = $this->examService;
		$examFind = $service->find($id);
		
		if (!$examFind) {
			return Response::json([
				'status' => 'error',
				'message' => 'Exam not found'
			], 404);
		}
		$delete = $service->destroy($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
				'message' => 'Failed to delete exam'
			], 400);
		} else{
			return Response::json([
				'status' => 'success',
				'message' => 'Exam deleted successfully'
			], 200);
		}
	}
}