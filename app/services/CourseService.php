<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Course;

class CourseService
{
    private $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }
    
    public function getAll($search)
    {
        $statusConstants = Course::getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];

        if ($search != '') {
            $result = $this->courseRepository->filter($search);
        } else {
            $result = $this->courseRepository->showAll();
        }
        $totalCourse = $result['courseCount']->count();
        $course = $result['coursePaginate'];

        return [
            'course' => $course,
            'totalCourse' => $totalCourse,
            'search' => $search,
            'ACTIVE' => $ACTIVE,
            'INACTIVE' => $INACTIVE
        ];
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

    public function store(array $data)
    {        
        if ($this->courseRepository->searchName($data['name'])) {
            return false;
        } else {
            return $this->courseRepository->createCourse([
                'name' => $data['name'],
                'credit' => $data['credit'],
                'created_by' => Session::get('user_id'),
                'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'updated_at' => ""
            ]);
        }
    }

    public function findById($id)
    {
        return $this->courseRepository->find($id);
    }

    public function updateValidation(array $data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|min:1|unique:courses,name,' . $id . ',course_id',
			'credit' => 'required|numeric|min:1'
		], [
			'required' => 'The Course field is required.',
			'min' => 'The Course must be at least :min characters.',
			'numeric' => 'The Course must be a number.'	
		]);
    }

    public function findByNameAndCredit(array $data)
    {        
        return $this->courseRepository->search($data['name'], $data['credit']);
    }

    public function update(array $data, $id)
    {
        if ($this->courseRepository->find($id)) {

            return $this->courseRepository->updateCourse([
                'name' => $data['name'],
                'credit' => $data['credit'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ], $id);
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        return $this->courseRepository->deleteCourse($id);
    }

    public function status($id)
    {
        $statusConstants = Course::getStatusConstants();
        $ACTIVE = $statusConstants['ACTIVE'];
		$INACTIVE = $statusConstants['INACTIVE'];
        $exist = $this->courseRepository->find($id);

        if ($exist) {
            if ($exist->status == $ACTIVE) {
                $status = $INACTIVE;
            } else {
                $status = $ACTIVE;
            }

            return $this->courseRepository->status($id, $status);
        } else {
            return false;
        }
    }

    public function assignedTo()
    {
        $courses = $this->courseRepository->assignedCourse();
		$getCourses = [];
		
		foreach ($courses as $instructor) {
			$getCourses[$instructor->semester_name][] = $instructor;
		}

        return $getCourses;
    }    
}