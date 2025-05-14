<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Services\ExamService;
use Illuminate\Support\Facades\Session;

class ExamController extends \BaseController
{
	private $examService; 

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
		$record = Input::all();
		
		return View::make('exam.index', $this->examService->getAll(isset($record['search']) ? $record['search'] : ''));
	}
	
	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store()
	{
		$data = Input::all();
		$validator = $this->examService->checkValidation($data);
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		} elseif ($this->examService->searchExamByName($data)) {
			return Response::json(['status' => 'error', 'message' => 'Exam already exists'], 403);
		} else {
			if ($this->examService->store($data, Session::get('user_id'))) {
				return Response::json(['status' => 'success', 'message' => 'Exam created successfully'], 200);
			} else {
				return Response::json(['status' => 'error', 'message' => 'Failed to create exam'], 409);
			}
		}
	}	
	
	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id)
	{
		$data = Input::all();
		if (!$this->examService->find($id)) {
			return Response::json(['status' => 'error', 'message' => 'Exam not found'], 404);
		}
		$validator = $this->examService->updateValidation($data);

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		} else {
			if ($this->examService->update($data, $id)) {
				return Response::json(['status' => 'success', 'message' => 'Exam updated successfully'], 200);
			} else {
				return Response::json(['status' => 'error', 'message' => 'Failed to update exam'], 500);
			}
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
		if (!$this->examService->find($id)) {
			return Response::json(['status' => 'error', 'message' => 'Exam not found'], 404);
		} else {		
			if (!$this->examService->destroy($id)) {
				return Response::json(['status' => 'error', 'message' => 'Failed to delete exam'], 400);
			} else{
				return Response::json(['status' => 'success', 'message' => 'Exam deleted successfully'], 200);
			}
		}
	}
}