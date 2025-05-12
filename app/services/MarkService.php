<?php

namespace App\Services;

use App\Repositories\MarkRepository;
use App\Repositories\ResultRepository;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class MarkService
{
    protected $markRepository;
    protected $resultRepository;

    public function __construct(MarkRepository $markRepository, ResultRepository $resultRepository)
    {
        $this->markRepository = $markRepository;
        $this->resultRepository = $resultRepository;
    }

    public function addResult($studentId)
    {
        $repo = $this->resultRepository;
        $records = $repo->results($studentId);
		$info = [];
		foreach ($records as $record) {
			array_push($info, $record->gpa);
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

		$get = $repo->resultExistonNot($studentId);
		if ($get) {
            $result = [
                'cgpa' => $CGPA
            ];
			$repo->updateResult($studentId, $result);
		} else {
            $result = [
                'student_id' => $studentId,
                'cgpa' => $CGPA
            ];
			$repo->createResult($result);
		}
    }

    public function getAllMarks()
    {
        $repo = $this->markRepository;
		$results = $repo->assignedCourses(Session::get("user_id"));
		$totalCourse = count($results);
		$userType = Session::get("user_type");
		
        return compact('userType', 'results', 'totalCourse');
    }

    public function courseView()
    {
        $repo = $this->markRepository;
        $results = $repo->assignedCourses(Session::get("user_id"));
        $totalCourse = count($results);
        $userType = Session::get("user_type");

        return compact('userType', 'results', 'totalCourse');
    }

    public function checkMarks(array $data)
    {
        $repo = $this->markRepository;
        return $repo->existOrNot($data['studentId'], $data['examId']);
    }

    public function createMark(array $data)
    {
        $repo = $this->markRepository;
        
		$totalMarks = $data['totalMarks'];
		$givenMarks = $data['givenMark'];

        $percentage = ($givenMarks / $totalMarks) * 100;

		if ($percentage >= 90) $gpa = 4.00;
		elseif ($percentage >= 85) $gpa = 3.75;
		elseif ($percentage >= 80) $gpa = 3.50;
		elseif ($percentage >= 75) $gpa = 3.25;
		elseif ($percentage >= 70) $gpa = 3.00;
		elseif ($percentage >= 65) $gpa = 2.75;
		elseif ($percentage >= 60) $gpa = 2.50;
		elseif ($percentage >= 50) $gpa = 2.25;
		else $gpa = 0.00;

        $result = [
            'student_id' => $data['studentId'],
            'exam_id' => $data['examId'],
            'course_id' => $data['courseId'],
            'semester_id' => $data['semesterId'],
            'marks' => $givenMarks,
            'gpa' => $gpa,
            'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ];

        return $repo->createMarks($result);
    }

    public function showMarks(array $data, $studentId)
    {
        $repo = $this->markRepository;

        return $repo->editMarks($studentId, $data['examId']);
    }

    public function students($courseId, $semesterId)
    {
        $repo = $this->markRepository;
        $results = $repo->getStudents(Session::get("user_id"), $semesterId, $courseId);
        $userId = [];

        foreach ($results as $result) {
            array_push($userId, $result->user_id);
        }

		foreach ($results as $result) {
			$examId = $result->exam_id;
			break;
		}
        $totalStudent = count($results);
        $marks = [];

        foreach ($userId as $studentId) {
            $getMark = $repo->getMarks($studentId, $examId);
            $marks[$studentId] = $getMark;
        }

        return compact('results', 'totalStudent', 'marks', 'userId');
    }

    public function edit(array $data, $studentId)
    {
        $repo = $this->markRepository;

        return $repo->editMarks($studentId, $data['examId']);
    }

    public function checkExistMarks($id)
    {
        $repo = $this->markRepository;

        return $repo->marksExistOrNot($id);
    }

    public function update(array $data, $id)
    {
        $repo = $this->markRepository;
		$totalMarks = $data['totalMarks'];
		$givenMarks = $data['givenMark'];

        $percentage = ($givenMarks / $totalMarks) * 100;

		if ($percentage >= 90) $gpa = 4.00;
		elseif ($percentage >= 85) $gpa = 3.75;
		elseif ($percentage >= 80) $gpa = 3.50;
		elseif ($percentage >= 75) $gpa = 3.25;
		elseif ($percentage >= 70) $gpa = 3.00;
		elseif ($percentage >= 65) $gpa = 2.75;
		elseif ($percentage >= 60) $gpa = 2.50;
		elseif ($percentage >= 50) $gpa = 2.25;
		else $gpa = 0.00;

        $result = [
            'marks' => $givenMarks,
            'gpa' => $gpa,
            'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ];
		
        return $repo->updateMarks($id, $result);
    }

    public function checkExistMarksForDelete($id, $examId)
    {
        $repo = $this->markRepository;

        return $repo->marksExistOrNotForDelete($id, $examId);
    }

    public function destroy($id, $examId)
    {
        $repo = $this->markRepository;

        return $repo->deleteMarks($id, $examId);
    }

    public function studentList()
    {
        $repo = $this->markRepository;
        $results = $repo->viewMarks(Session::get("user_id"));
		$groupedResults = [];
        
		foreach ($results as $result) {
			$groupedResults[$result->course_name][] = $result;
		}

		return compact('results', 'groupedResults');
    }

    public function assignedCourses($studentId)
    {
        $repo = $this->markRepository;

        return $repo->assignedCourses($studentId);
    }

    public function viewMarks($studentId)
    {
        $repo = $this->markRepository;
        
        return $repo->viewMarks($studentId);
    }
}
