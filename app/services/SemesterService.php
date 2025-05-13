<?php

namespace App\Services;

use App\Repositories\SemesterRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SemesterService
{
    private $semesterRepository;

    public function __construct(SemesterRepository $semesterRepository)
    {
        $this->semesterRepository = $semesterRepository;
    }
    
    public function getAllSemester($search)
    {
        if ($search != '') {
            $result = $this->semesterRepository->filter($search);
        } else {
            $result = $this->semesterRepository->showAll();
        }
        $totalSemester = $result['semesterCount']->count();
        $semester = $result['semester'];
		$data = compact('semester', 'totalSemester', 'search');

		return $data;
    }

    public function checkValidation(array $data)
    {
		return Validator::make($data, [
			'name' => 'required|min:3|unique:semesters,name'
		], [
			'required' => 'The Semester field is required.',
			'min' => 'The Semester must be at least :min characters.'
		]);
    }

    public function storeSemester(array $data)
    {
        $exist = $this->semesterRepository->searchName($data['name']);
        
        if ($exist) {
            return false;
        } else {
            $result = [
                'name' => $data['name'],
                'created_by' => Session::get('user_id'),
                'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
                'updated_at' => ""
            ];

            return $this->semesterRepository->createSemester($result);
        }
    }

    public function checkSemester($id)
    {
        return $this->semesterRepository->find($id);
    }
    
    public function updateValidation(array $data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|min:1|unique:semesters,name,' . $id . ',semester_id',
        ], [
            'required' => 'The Semester field is required.',
            'min' => 'The Semester must be at least :min characters.'
        ]);
    }

    public function checkSemesterName($name)
    {
        return $this->semesterRepository->searchName($name);
    }

    public function updateSemester(array $data, $id)
    {
        if ($this->semesterRepository->find($id)) {
            $result = [
                'name' => $data['name'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ];
            
            return $this->semesterRepository->updateSemester($result, $id);
        } else {
            return false;
        }
    }

    public function destroySemester($id)
    {
        if ($this->semesterRepository->find($id)) {
            return $this->semesterRepository->deleteSemester($id);
        } else {
            return false;
        }
    }
}