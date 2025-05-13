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
		
		return View::make('department.index', ['data' => $this->departmentService->getAll(isset($record['search']) ? $record['search'] : '')]);
	}
	
	public function store()
	{
		$validator = $this->departmentService->checkValidation(Input::all());
		
		if ($validator->fails()) {
			
			return Response::json(['errors' => $validator->errors()], 422);
		}
		
		if ($this->departmentService->store(Input::all())) {
			
			return Response::json(['status' => 'success'], 200);
		} else {
			
			return Response::json(['status' => 'error'], 409);
		}
	}
	
	public function update($id)
	{
		if (!$this->departmentService->checkById($id)) {
			
			return Response::json(['errors' => 'department not found'], 404);
		}
		
		$validator = $this->departmentService->updateValidation(Input::all(), $id);
		
		if ($validator->fails()) {
			
			return Response::json(['errors' => $validator->errors()], 422);
		}
		
		if ($this->departmentService->checkByName(Input::get('name'))) {
			
			return Response::json(['errors' => 'Department already exist'], 409);
		}
		
		if ($this->departmentService->update(Input::all(), $id)) {
			
			return Response::json(['status' => 'success'], 200);
		} else {
			
			return Response::json(['errors' => 'error'], 409);
		}
	}
	
	public function destroy($id)
	{
		if (!$this->departmentService->checkById($id)) {
			
			return Response::json(['status' => 'error'], 404);
		}
		
		if (!$this->departmentService->destroy($id)) {
			
			return Response::json(['status' => 'error'], 404);
		} else{
			
			return Response::json(['status' => 'success'], 200);
		}
	}
}