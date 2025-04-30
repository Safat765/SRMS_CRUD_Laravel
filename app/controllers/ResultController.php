<?php

use App\Models\Result;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ResultController extends BaseController
{				
	public function index()
	{
		$result = new Result();
		$records = $result->results(Session::get("user_id"));
		// p($records);
		$info = [];
		foreach ($records as $record) {
			array_push($info, $record->gpa);
		}
		// p($info);
		foreach ($records as $record) {
			$record->full_name = $record->first_name . " " . $record->middle_name . " " . $record->last_name;
			
			$name = $record->full_name;
			$session = $record->session;
			$semester_name = $record->semester_name;
			break;
		}
		// echo $name. "<br>" . $session . "<br>" . $semester_name;
		
		$cgpSum = [];
		$credit = [];
		foreach ($records as $record) {
			array_push($credit, $record->credit);
			$totalGpa = $record->gpa * $record->credit;
			array_push($cgpSum, $totalGpa);
		}
		// p($cgpSum);
		// p($credit);
		// echo "<br>Sum - " . array_sum($credit) . "<br>";
		$credit = array_sum($credit);
		$gpa = array_sum($cgpSum);
		$CGPA = $gpa / $credit;
		// echo "<br>gpa - " . array_sum($cgpSum) . "<br>";
		// echo "<br>CGPA - " . $CGPA . "<br>";
		$result = [
			"CGPA"=> $CGPA,
			"name"=> $name,
			"session"=> $session,
			"credit"=> $credit
		];
		// p($result);

		$data = compact('result', 'records');

		return View::make('Result/index')->with($data);
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