<?php

namespace App\Services;

use App\Repositories\MarkRepository;
use App\Repositories\ResultRepository;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class MarkService
{
    private $markRepository;
    private $resultRepository;
    
    public function __construct(MarkRepository $markRepository, ResultRepository $resultRepository)
    {
        $this->markRepository = $markRepository;
        $this->resultRepository = $resultRepository;
    }
    
    public function addResult($studentId)
    {
        $records = $this->resultRepository->results($studentId);
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
        
        $get = $this->resultRepository->resultExistonNot($studentId);
        if ($get) {
            $result = [
                'cgpa' => $CGPA
            ];
            $this->resultRepository->updateResult($studentId, $result);
        } else {
            $result = [
                'student_id' => $studentId,
                'cgpa' => $CGPA
            ];
            $this->resultRepository->createResult($result);
        }
    }
    
    public function getAll()
    {
        $results = $this->markRepository->assignedCourses(Session::get("user_id"));
        $totalCourse = count($results);
        
        return [
            'userType' => Session::get("user_type"),
            'results' => $results,
            'totalCourse' => $totalCourse
        ];
    }
    
    public function checkMarks(array $data)
    {
        return $this->markRepository->existOrNot($data['studentId'], $data['examId']);
    }
    
    public function createMark(array $data)
    {        
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
        
        return $this->markRepository->createMarks([
            'student_id' => $data['studentId'],
            'exam_id' => $data['examId'],
            'course_id' => $data['courseId'],
            'semester_id' => $data['semesterId'],
            'marks' => $givenMarks,
            'gpa' => $gpa,
            'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ]);
    }
    
    public function show(array $data, $studentId)
    {
        return $this->markRepository->editMarks($studentId, $data['examId']);
    }
    
    public function students($courseId, $semesterId)
    {
        $results = $this->markRepository->getStudents(Session::get("user_id"), $semesterId, $courseId);
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
            $getMark = $this->markRepository->getMarks($studentId, $examId);
            $marks[$studentId] = $getMark;
        }
        
        return [
            'results' => $results,
            'totalStudent' => $totalStudent,
            'marks' => $marks,
            'userId' => $userId
        ];
    }
    
    public function edit(array $data, $studentId)
    {
        return $this->markRepository->editMarks($studentId, $data['examId']);
    }
    
    public function checkExist($id)
    {
        return $this->markRepository->marksExistOrNot($id);
    }
    
    public function update(array $data, $id)
    {
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
        
        return $this->markRepository->updateMarks($id, [
            'marks' => $givenMarks,
            'gpa' => $gpa,
            'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
        ]);
    }
    
    public function checkExistMarksForDelete($id, $examId)
    {
        return $this->markRepository->marksExistOrNotForDelete($id, $examId);
    }
    
    public function destroy($id, $examId)
    {
        return $this->markRepository->deleteMarks($id, $examId);
    }
    
    public function studentList()
    {
        $results = $this->markRepository->view(Session::get("user_id"));
        $groupedResults = [];
        
        foreach ($results as $result) {
            $groupedResults[$result->course_name. " - ". $result->semester_name][] = $result;
        }
        
        return [
            'groupedResults' => $groupedResults
        ];
    }
    
    public function assignedCourses($studentId)
    {
        return $this->markRepository->assignedCourses($studentId);
    }
    
    public function view($studentId)
    {
        return $this->markRepository->view($studentId);
    }
}
