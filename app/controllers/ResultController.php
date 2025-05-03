<?php

use App\Models\Result;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

class ResultController extends BaseController
{				
	public function index()
	{
		$studentId = Session::get("user_id");
		$requiredMinCredit = 9;
		$requiredMinGPA = 3.00;
		$result = new Result();
		$semesters = $result->getSemester($studentId);
		$semestersInfo = [];

		foreach ($semesters as $semester) {
			array_push($semestersInfo, $semester->semester_id);
		}
		$semesterWiseResult = [];

		foreach ($semestersInfo as $semesterIds) {
			$SWR = $result->getData($studentId, $semesterIds);
			array_push($semesterWiseResult, $SWR);
		}
		$semesterGpas = [];
		$credits = [];

		foreach ($semestersInfo as $semesterId)
		{
			$semesterData = $result->getData($studentId, $semesterId);
			if (!isset($semesterGpas[$semesterId])) {
				$semesterGpas[$semesterId] = [];
			}
			foreach ($semesterData as $course) {
				$semesterGpas[$semesterId][] = $course->gread * $course->credit;
				$credits[$semesterId][] = $course->credit;
			}
		}
		$totalGreadSemesterWise = [];

		foreach ($semesterGpas as $semesterId => $gread)
		{
			$totalGreadSemesterWise[$semesterId] = array_sum($gread);
		}
		$totalCredits = [];

		foreach ($credits as $semesterId => $credit)
		{
			$totalCredits[$semesterId] = array_sum($credit);
		}
		$GPA = [];

		foreach ($totalCredits as $semesterId => $credit)
		{
			$GPA[$semesterId] = round($totalGreadSemesterWise[$semesterId]/$credit, 2);
		}
		$lastCompleteCredits = end($totalCredits);
		$lastSemester = key($totalCredits);
		$lastSemesterGPA = $GPA[$lastSemester];

		if ($lastCompleteCredits >= $requiredMinCredit && $lastSemesterGPA >= $requiredMinGPA) {
			$updateSemester = $lastSemester + 1;
			$result->updateStudentsSemester($studentId, $updateSemester);
		}

		// -----------------------------------------------------------------
		$getInfo = $result->showResult($studentId);
		$records = $result->results($studentId);
		$info = [];

		foreach ($records as $record) {
			array_push($info, $record->gpa);
		}
		
		foreach ($records as $record) {
			$record->full_name = $record->first_name . " " . $record->middle_name . " " . $record->last_name;
			
			$name = $record->full_name;
			$session = $record->session;
			$semester_name = $record->semester_name;
			break;
		}
		$cgpSum = [];
		$credit = [];

		foreach ($records as $record) {
			array_push($credit, $record->credit);
			$totalGpa = $record->gpa * $record->credit;
			array_push($cgpSum, $totalGpa);
		}
		$credit = array_sum($credit);
		$gpa = array_sum($cgpSum);
		$CGPA = $gpa / $credit;
		$result = [
			"CGPA"=> round($CGPA, 2),
			"name"=> $name,
			"session"=> $session,
			"credit"=> $credit
		];
		$data = compact('result', 'getInfo', 'semesters', 'GPA', 'totalCredits');

		return View::make('Result/index')->with($data);
	}

	public function semeterWise($semesterId)
	{
		$studentId = Input::get('studentId');
	
		$result = new Result();
		$getResult = $result->getResult($studentId, $semesterId);

		if ($getResult) {
			return Response::json([
				'status' => 'success',
				'records' => $getResult
			], 200);
		} else {
			return Response::json([
				// 'status' => $data
				'status' => 'error'
			], 400);
		}
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