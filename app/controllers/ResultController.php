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

	public function index()
	{
		return View::make('result/index', ['data' => $this->resultService->getAll()]);
	}

	public function semeterWise($semesterId)
	{
		$data = $this->resultService->getSemesterWiseResult(Input::get('studentId'), $semesterId);

		if ($data) {
			return Response::json(['status' => 'success', 'records' => $data], 200);
		} else {
			return Response::json(['status' => 'error'], 400);
		}
	}
	
	public function enrolledCourse()
	{
		return View::make('result/enrollCourse', ['groupedResults' => $this->resultService->enrolledCourse()]);
	}
}