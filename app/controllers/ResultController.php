<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use App\Services\ResultService;

class ResultController extends BaseController
{
	private $resultService;

	public function __construct(ResultService $resultService)
	{
		$this->resultService = $resultService;
	}

	public function semesterWise($semesterId)
	{
		$data = $this->resultService->getSemesterWiseResult(Input::get('studentId'), $semesterId);

		if ($data) {
			return Response::json(['status' => 'success', 'message' => 'Result fetched successfully', 'records' => $data], 200);
		} else {
			return Response::json(['status' => 'error', 'message' => 'Result not found'], 400);
		}
	}
	
	public function enrolledCourse()
	{
		$groupedResults = $this->resultService->enrolledCourse();
		return View::make('result/enrollCourse', ['groupedResults' => $groupedResults]);
	}

	public function show($id)
	{
		$data = $this->resultService->getAll($id);

		if ($data) {
			return View::make('result/index', $data);
		}
	}
}