<?php

namespace App\Services;

use App\Repositories\ResultRepository;
use Illuminate\Support\Facades\Session;

class ResultService
{
    private $resultRepository;

    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public function getAll()
    {
		$studentId = Session::get("user_id");
		$requiredMinCredit = 9;
		$requiredMinGPA = 3.00;

        $semesters = $this->resultRepository->getSemester($studentId);
		$semestersInfo = [];

		foreach ($semesters as $semester) {
			array_push($semestersInfo, $semester->semester_id);
		}
		$semesterWiseResult = [];

		foreach ($semestersInfo as $semesterIds) {
			$SWR = $this->resultRepository->getData($studentId, $semesterIds);
			array_push($semesterWiseResult, $SWR);
		}
		$semesterGpas = [];
		$credits = [];

		foreach ($semestersInfo as $semesterId)
		{
			$semesterData = $this->resultRepository->getData($studentId, $semesterId);
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
			$updateSem = ['semester_id' => $updateSemester];
			$this->resultRepository->updateStudentsSemester($studentId, $updateSem);
		}

		$getInfo = $this->resultRepository->showResult($studentId);
		$records = $this->resultRepository->results($studentId);
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

		return [
			'result' => [
				"CGPA"=> round($CGPA, 2),
				"name"=> $name,
				"session"=> $session,
				"credit"=> $credit
			],
			'getInfo' => $getInfo,
			'semesters' => $semesters,
			'GPA' => $GPA,
			'totalCredits' => $totalCredits
		];
    }

    public function getSemesterWiseResult($studentId, $semesterId)
    {
        return $this->resultRepository->getResult($studentId, $semesterId);
    }

    public function enrolledCourse()
    {
        $studentId = Session::get("user_id");
        $records = $this->resultRepository->results($studentId);
		$groupedResults = [];

		foreach ($records as $result) {
			$groupedResults[$result->semester_name][] = $result;
		}

        return $groupedResults;

    }
}

