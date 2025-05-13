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
		$search = Input::get('search');
		$data = $this->semesterService->getAllSemester($search);

		return View::make('semester/index')->with($data);
	}
				
	public function create()
	{
		//
	}
				
	public function store()
	{
		$validator = $this->semesterService->checkValidation(Input::all());

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		$exist = $this->semesterService->storeSemester(Input::all());
		
		if ($exist) {
			return Response::json(['status' => 'success', 'message'=> 'Semester created successfully'], 200);
		} else {
			return Response::json(['status' => 'error', 'message'=> 'Semester already exist'], 403);
		}
	}
				
	public function update($id)
	{
		$semester = $this->semesterService->checkSemester($id);
		
		if (!$semester) {			
			return Response::json(['status' => 'error'], 404);
		}
		
		$validator = $this->semesterService->updateValidation(Input::all(), $id);

		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		$update = $this->semesterService->updateSemester(Input::all(), $id);
		
		if ($update) {
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['status' => 'error'], 400);
		}
	}
				
	public function destroy($id)
	{
		$semester = $this->semesterService->checkSemester($id);
		
		if (!$semester) {
			return Response::json(['status' => 'error'], 404);
		}
		$delete = $this->semesterService->destroySemester($id);
		
		if (!$delete) {
			return Response::json(['status' => 'error'], 404);
		} else{
			return Response::json(['status' => 'success'], 200);
		}
	}
}