<?php

use App\Models\Semester;
use App\Services\SemesterService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
		
class SemesterController extends BaseController
{
	protected $semesterService;

	public function __construct(SemesterService $semesterService)
	{
		$this->semesterService = $semesterService;
	}
				
	public function index()
	{
		$service = $this->semesterService;
		$search = Input::get('search');
		$data = $service->getAllSemester($search);

		return View::make('semester/index')->with($data);
	}
				
	public function create()
	{
		//
	}
				
	public function store()
	{
		$service = $this->semesterService;
		$validator = $service->checkValidation(Input::all());

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$exist = $service->storeSemester(Input::all());
		
		if ($exist) {
			return Response::json([
				'status' => 'success',
				'message'=> 'Semester created successfully'
			], 200);
		} else {
			return Response::json([
				'status' => 'error',
				'message'=> 'Semester already exist'
			], 403);
		}
	}
				
	public function show($id)
	{
		// Show single item
	}
				
	public function edit($id)
	{
		//
	}
				
	public function update($id)
	{
		$service = $this->semesterService;
		$semester = $service->checkSemester($id);
		
		if (!$semester) {			
			return Response::json([
				'status' => 'error',
			]);
		}
		
		$validator = $service->updateValidation(Input::all(), $id);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$update = $service->updateSemester(Input::all(), $id);
		
		if ($update) {
			return Response::json([
				'status' => 'success',
			]);
		} else {
			return Response::json([
				'status' => 'error',
			]);
		}
	}
				
	public function destroy($id)
	{
		$service = $this->semesterService;
		$semester = $service->checkSemester($id);
		
		if (!$semester) {
			return Response::json([
				'status' => 'error',
			]);
		}
		$delete = $service->destroySemester($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error'
			]);
		} else{
			return Response::json([
				'status' => 'success'
			]);
		}
	}
}