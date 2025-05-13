<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use Illuminate\Support\Facades\Validator;
use App\Models\Exam;

class ExamService
{
    private $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function getAll($search)
    {
        if ($search != '') {
            $exams = $this->examRepository->filter($search);
        } else {
            $exams = $this->examRepository->joinTables();
        }        
        $totalExams = count($exams);
        $examType = Exam::getExamTypeConstants();

        return [
            'exams' => $exams,
            'totalExams' => $totalExams,
            'search' => $search,
            'examType' => [
                "Mid" => $examType['Mid'],
                "Quiz" => $examType['Quiz'],
                "Viva" => $examType['Viva'],
                "Final" => $examType['Final']
            ],
            'list' => [
                'courses' => $this->examRepository->getCourseList(),
                'department' => $this->examRepository->getDepartmentList(),
                'semester' => $this->examRepository->getSemesterList(),
                'instructor' => $this->examRepository->getInstructorList()
            ]
        ];
    }

    public function checkValidation(array $data)
    {
        return Validator::make($data, [
            'courseId' => 'required',
            'examTitle' => 'required|min:3|max:100',
            'departmentId' => 'required',
            'semesterId' => 'required',
            'examType' => 'required|in:1,2,3,4',
            'credit' => 'required|numeric',
            'marks' => 'required|numeric',
            'instructorId' => 'required'
        ], [
            'required' => 'The :attribute field is required.',
            'numeric' => 'The :attribute must be a number.',
            'in' => 'Please select a valid :attribute.'
        ]);
    }

    public function searchExamByName(array $data)
    {
        return $this->examRepository->searchName($data['courseId'], $data['departmentId'], $data['semesterId'], $data['examType']);
    }

    public function store(array $data, $createdBy)
    {
        return $this->examRepository->create([  
			'course_id' => $data['courseId'], 
			'department_id' => $data['departmentId'], 
			'semester_id' => $data['semesterId'], 
			'exam_title' => $data['examTitle'], 
			'exam_type' => $data['examType'], 
			'credit' => $data['credit'], 
			'marks' => $data['marks'], 
			'instructor_id' => $data['instructorId'], 
			'created_by' => $createdBy
        ]);
    }

    public function find($id)
    {
        return $this->examRepository->find($id);
    }

    public function updateValidation(array $data)
    {
        return Validator::make($data, [
			'courseId' => 'required',
			'examTitle' => 'required|min:3|max:100',
			'departmentId' => 'required',
			'semesterId' => 'required',
			'credit' => 'required|numeric',
			'examType' => 'required|in:1,2,3,4',
			'marks' => 'required|numeric',
			'instructorId' => 'required'
		], [
			'required' => 'The :attribute field is required.',
			'numeric' => 'The :attribute must be a number.',
			'in' => 'Please select a valid :attribute.'
		]);
    }

    public function update(array $data, $id)
    {
        if ($this->examRepository->find($id)) {

            return $this->examRepository->update([            
                'course_id' => $data['courseId'],
                'department_id' => $data['departmentId'],
                'semester_id' => $data['semesterId'],
                'exam_title' => $data['examTitle'],
                'credit' => $data['credit'],
                'exam_type' => $data['examType'],
                'marks' => $data['marks'],
                'instructor_id' => $data['instructorId']
            ], $id);
        } else {
            return false;
        }   
    }

    public function destroy($id)
    {
        if ($this->examRepository->find($id)) {
            return $this->examRepository->delete($id);
        } else {
            return false;
        }
    }
}
