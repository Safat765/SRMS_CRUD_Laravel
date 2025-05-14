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
		
		return View::make('department.index', $this->departmentService->getAll(isset($record['search']) ? $record['search'] : ''));
	}
	
	public function store()
	{
		$record = Input::all();
		$validator = $this->departmentService->checkValidation($record);
		
		if ($validator->fails()) {			
			return Response::json(['errors' => $validator->errors()], 422);
		} else {		
			if ($this->departmentService->store($record)) {				
				return Response::json(['status' => 'success'], 200);
			} else {				
				return Response::json(['status' => 'error'], 409);
			}
		}
	}
	
	public function update($id)
	{
		if (!$this->departmentService->checkById($id)) {
			
			return Response::json(['errors' => 'department not found'], 404);
		}
		
		$record = Input::all();
		$validator = $this->departmentService->updateValidation($record, $id);
		
		if ($validator->fails()) {			
			return Response::json(['errors' => $validator->errors()], 422);
		} elseif ($this->departmentService->checkByName($record['name'])) {
			return Response::json(['errors' => 'Department already exist'], 409);
		} else {
			if ($this->departmentService->update($record, $id)) {			
				return Response::json(['status' => 'success', 'message' => 'Department updated successfully'], 200);
			} else {			
				return Response::json(['errors' => 'error', 'message' => 'Department update failed'], 409);
			}
		}
	}
	
	public function destroy($id)
	{
		if (!$this->departmentService->checkById($id)) {			
			return Response::json(['status' => 'error', 'message' => 'Department not found'], 404);
		} else {		
			if (!$this->departmentService->destroy($id)) {			
				return Response::json(['status' => 'error', 'message' => 'Department delete failed'], 500);
			} else{			
				return Response::json(['status' => 'success', 'message' => 'Department deleted successfully'], 200);
			}
		}
	}
}