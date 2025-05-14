<?php

use App\Services\SemesterService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
		
class SemesterController extends BaseController
{
	private $semesterService;

	public function __construct(SemesterService $semesterService)
	{
		$this->semesterService = $semesterService;
	}

	public function index()
	{
		return View::make('semester/index', $this->semesterService->getAll(Input::get('search')));
	}

	public function store()
	{
		$validator = $this->semesterService->checkValidation(Input::all());

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		} else {		
			if ($this->semesterService->store(Input::all())) {
				return Response::json(['status' => 'success', 'message'=> 'Semester created successfully'], 200);
			} else {
				return Response::json(['status' => 'error', 'message'=> 'Semester already exist'], 403);
			}
		}
	}

	public function update($id)
	{
		if (!$this->semesterService->checkById($id)) {			
			return Response::json(['status' => 'error', 'message' => 'Semester not found'], 404);
		}		
		$validator = $this->semesterService->updateValidation(Input::all(), $id);

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		} elseif ($this->semesterService->checkName(Input::get('name'))) {
			return Response::json(['status' => 'error', 'message'=> 'Semester already exist'], 403);
		} else {
			if ($this->semesterService->update(Input::all(), $id)) {
				return Response::json(['status' => 'success', 'message' => 'Semester updated successfully'], 200);
			} else {
				return Response::json(['status' => 'error', 'message' => 'Semester update failed'], 400);
			}
		}
	}

	public function destroy($id)
	{
		if (!$this->semesterService->checkById($id)) {
			return Response::json(['status' => 'error', 'message' => 'Semester not found'], 404);
		} else {		
			if (!$this->semesterService->destroy($id)) {
				return Response::json(['status' => 'error', 'message' => 'Semester delete failed'], 500);
			} else{
				return Response::json(['status' => 'success', 'message' => 'Semester deleted successfully'], 200);
			}
		}
	}
}