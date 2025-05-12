<?php

namespace App\Services;

use App\Repositories\ExamRepository;
use Illuminate\Support\Facades\Validator;

class ExamService
{
    protected $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function getAllExams($search)
    {
        $repo = $this->examRepository;
        $examType = $repo->getExamTypeConstants();

        if ($search != '') {
            $result = $repo->filter($search);
        } else {
            $result = $repo->joinTables();
        }        
        $totalExams = count($result);
        $exams = $result;
		$examType = [
			"Mid" => $examType['Mid'],
			"Quiz" => $examType['Quiz'],
			"Viva" => $examType['Viva'],
			"Final" => $examType['Final']
		];
        $list = [
            'courses' => $repo->getCourseList(),
            'department' => $repo->getDepartmentList(),
            'semester' => $repo->getSemesterList(),
            'instructor' => $repo->getInstructorList()
        ];

        $data = compact('exams', 'totalExams', 'search', 'examType', 'list');

        return $data;
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
        $repo = $this->examRepository;
        
        return $repo->searchName($data['courseId'], $data['departmentId'], $data['semesterId'], $data['examType']);
    }

    public function storeExam(array $data, $createdBy)
    {
        $repo = $this->examRepository;
        $result = [            
			'course_id' => $data['courseId'], 
			'department_id' => $data['departmentId'], 
			'semester_id' => $data['semesterId'], 
			'exam_title' => $data['examTitle'], 
			'exam_type' => $data['examType'], 
			'credit' => $data['credit'], 
			'marks' => $data['marks'], 
			'instructor_id' => $data['instructorId'], 
			'created_by' => $createdBy
        ];

        return $repo->createExam($result);
    }

    public function find($id)
    {
        $repo = $this->examRepository;
        
        return $repo->find($id);
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
		],
		[
			'required' => 'The :attribute field is required.',
			'numeric' => 'The :attribute must be a number.',
			'in' => 'Please select a valid :attribute.'
		]);
    }

    public function updateExam(array $data, $id)
    {
        $repo = $this->examRepository;
        $exist = $repo->find($id);
        
        if (!$exist) {
            return false;
        } else {
            $result = [            
                'course_id' => $data['courseId'],
                'department_id' => $data['departmentId'],
                'semester_id' => $data['semesterId'],
                'exam_title' => $data['examTitle'],
                'credit' => $data['credit'],
                'exam_type' => $data['examType'],
                'marks' => $data['marks'],
                'instructor_id' => $data['instructorId']
            ];

            return $repo->updateExam($result, $id);
        }
    }

    public function destroy($id)
    {
        $repo = $this->examRepository;
        $exist = $repo->find($id);
        
        if (!$exist) {
            return false;
        } else {
            return $repo->deleteExam($id);
        }
    }
}
