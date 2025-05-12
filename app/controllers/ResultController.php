<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use App\Services\ResultService;

class ResultController extends BaseController
{
	protected $resultService;

	public function __construct(ResultService $resultService)
	{
		$this->resultService = $resultService;
	}

	public function index()
	{
		$service = $this->resultService;
		$data = $service->getAllResults();

		return View::make('result/index')->with($data);
	}

	public function semeterWise($semesterId)
	{
		$service = $this->resultService;
		$studentId = Input::get('studentId');
		$data = $service->getSemesterWiseResult($studentId, $semesterId);

		if ($data) {
			return Response::json([
				'status' => 'success',
				'records' => $data
			], 200);
		} else {
			return Response::json([
				'status' => 'error'
			], 400);
		}
	}
	
	public function enrolledCourse()
	{
		$service = $this->resultService;
		$groupedResults = $service->enrolledCourse();

		return View::make('result/enrollCourse')->with(['groupedResults' => $groupedResults]);
	}
				
	public function create()
	{
		// Show create form
	}
				
	public function store()
	{
		// Handle form submission
	}
				
	public function show($id)
	{
		// Show single item
	}
				
	public function edit($id)
	{
		// Show edit form
	}
				
	public function update($id)
	{
		// Handle update
	}
				
	public function destroy($id)
	{
		// Handle deletion
	}
}