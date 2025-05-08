<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }
    
    public function getAllCourse($search)
    {
        $repo = $this->courseRepository;
        
        $statusConstants = $repo->getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];

        if ($search != '') {
            $result = $repo->filter($search);
        } else {
            $result = $repo->showAll();
        }
        $totalCourse = $result['courseCount']->count();
        $course = $result['course'];

        $data = compact('course', 'totalCourse', 'search', 'ACTIVE', 'INACTIVE');

        return $data;
    }

    public function checkValidation(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|min:2',
            'credit' => 'required|numeric'
        ], [
			'required' => 'The Course field is required.',
			'min' => 'The Course must be at least :min characters.',
			'numeric' => 'The Course must be a number.'	
        ]);
    }

    public function storeCourse(array $data)
    {
        $repo = $this->courseRepository;
        $exist = $repo->searchName($data['name']);
        
        if ($exist) {
            return false;
        } else {
            $result = [
                'name' => $data['name'],
                'credit' => $data['credit'],
                'created_by' => Session::get('user_id'),
                'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'updated_at' => ""
            ];

            return $repo->createCourse($result);
        }
    }

    public function checkCourse($id)
    {
        $repo = $this->courseRepository;
        $course = $repo->find($id);

        return $course;
    }

    public function updateValidation(array $data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|min:1|unique:courses,name,' . $id . ',course_id',
			'credit' => 'required|numeric'
		], [
			'required' => 'The Course field is required.',
			'min' => 'The Course must be at least :min characters.',
			'numeric' => 'The Course must be a number.'	
		]);
    }

    public function checkCourseName($name)
    {
        $repo = $this->courseRepository;
        
        return $repo->searchName($name);
    }

    public function updateCourse(array $data, $id)
    {
        $repo = $this->courseRepository;

        if ($repo->find($id)) {
            $result = [
                'name' => $data['name'],
                'credit' => $data['credit'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ];

            return $repo->updateCourse($result, $id);
        } else {
            return false;
        }
    }

    public function destroyCourse($id)
    {
        $repo = $this->courseRepository;

        return $repo->deleteCourse($id);
    }

    public function statusUpdate($id)
    {
        $repo = $this->courseRepository;
        $statusConstants = $repo->getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];
        $exist = $repo->find($id);

        if ($exist) {
            if ($exist->status == $ACTIVE) {
                $status = $INACTIVE;
            } else {
                $status = $ACTIVE;
            }

            return $repo->status($id, $status);
        } else {
            return false;
        }
    }

    public function assignedCourse()
    {
        $repo = $this->courseRepository;

        $courses = $repo->assignedCourse();
		$getCourses = [];
		
		foreach ($courses as $instructor) {
			$getCourses[$instructor->semester_name][] = $instructor;
		}

        return $getCourses;
    }
    
}