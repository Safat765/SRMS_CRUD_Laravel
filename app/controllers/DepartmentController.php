<?php

use App\Services\DepartmentService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class DepartmentController extends BaseController
{
	protected $departmentService;

	public function __construct(DepartmentService $departmentService)
	{
		$this->departmentService = $departmentService;
	}

	public function index()
	{
		$service = $this->departmentService;
		$search = Input::get('search');
		$data = $service->getAllDepartment($search);

		return View::make('department.index')->with($data);
	}
				
	public function create()
	{
		//
	}
				
	public function store()
	{
		$service = $this->departmentService;
		$validator = $service->createValidation(Input::all());

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$result = $service->storeDepartment(Input::all());
		
		if ($result) {
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			return Response::json([
				'status' => 'error'
			], 409);
		}
	}
				
	public function show($id)
	{
		//
	}
				
	public function edit($id)
	{
		//
	}
				
	public function update($id)
	{
		$service = $this->departmentService;
		$department = $service->checkDepartment($id);
		
		if (!$department) {
			return Response::json([
				'errors' => 'department not found'
			], 404);
		}
		
		$validator = $service->updateValidation(Input::all(), $id);

		if ($validator->fails()) {
			return Response::json([
				'errors' => $validator->errors()
			], 422);
		}
		$exist = $service->checkDepartmentName(Input::get('name'));

		if ($exist) {
			return Response::json([
				'errors' => 'Department already exist'
			]);
		}
		$update = $service->updateDepartment(Input::all(), $id);
		
		if ($update) {
			return Response::json([
				'status' => 'success',
			], 200);
		} else {
			return Response::json([
				'errors' => 'error'
			]);
		}
	}
				
	public function destroy($id)
	{
		$service = $this->departmentService;
		$department = $service->checkDepartment($id);
		
		if (!$department) {
			Response::json([
				'status' => 'error'
			], 404);
		}
		$delete = $service->destroyDepartment($id);
		
		if (!$delete) {
			return Response::json([
				'status' => 'error',
			]);
		} else{
			return Response::json([
				'status' => 'success',
			]);
		}
	}
}