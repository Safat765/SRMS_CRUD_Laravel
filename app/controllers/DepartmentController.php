<?php

use App\Services\DepartmentService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class DepartmentController extends BaseController
{
	private $departmentService;
	
	public function __construct(DepartmentService $departmentService)
	{
		$this->departmentService = $departmentService;
	}
	
	public function index()
	{
		$record = Input::all();
		
		return View::make('department.index', [
			'data' => $this->departmentService->getAll(isset($record['search']) ? $record['search'] : '')
		]);
	}
	
	public function store()
	{
		$validator = $this->departmentService->checkValidation(Input::all());
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		$result = $this->departmentService->store(Input::all());
		
		if ($result) {
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['status' => 'error'], 409);
		}
	}
	
	public function update($id)
	{
		$department = $this->departmentService->checkById($id);
		
		if (!$department) {
			return Response::json(['errors' => 'department not found'], 404);
		}
		
		$validator = $this->departmentService->updateValidation(Input::all(), $id);
		
		if ($validator->fails()) {
			return Response::json(['errors' => $validator->errors()], 422);
		}
		$exist = $this->departmentService->checkByName(Input::get('name'));
		
		if ($exist) {
			return Response::json(['errors' => 'Department already exist'], 409);
		}
		$update = $this->departmentService->update(Input::all(), $id);
		
		if ($update) {
			return Response::json(['status' => 'success'], 200);
		} else {
			return Response::json(['errors' => 'error'], 409);
		}
	}
	
	public function destroy($id)
	{
		$department = $this->departmentService->checkById($id);
		
		if (!$department) {
			return Response::json(['status' => 'error'], 404);
		}
		$delete = $this->departmentService->destroy($id);
		
		if (!$delete) {
			return Response::json(['status' => 'error'], 404);
		} else{
			return Response::json(['status' => 'success'], 200);
		}
	}
}