<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAllDepartment($search)
    {
        $repo = $this->departmentRepository;
        
        if ($search != '') {
            $result = $repo->filter($search);
        } else {
			$result = $repo->showAll();
        }
        $totalDepartment = $result['departmentCount']->count();
        $department = $result['department'];

        $data = compact('department', 'totalDepartment', 'search');

        return $data;
    }

    public function checkValidation(array $data)
    {        
        $validator = Validator::make($data, [
			'name' => 'required|min:3|unique:departments'
		], [
			'required' => 'The Department field is required.',
			'min' => 'The Department must be at least :min characters.'
		]);

        return $validator;
    }

    public function storeDepartment(array $data)
    {
        $repo = $this->departmentRepository;
        $name = $data['name'];

        if ($repo->searchName($name)) {
            return false;
        }

        $result = [
            'name' => $name,
            'created_by' => Session::get('user_id'),
            'created_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s'),
            'updated_at' => ""
        ];
        return $repo->createDepartment($result);
    }

    public function checkDepartment($id)
    {
        $repo = $this->departmentRepository;
        $department = $repo->find($id);

        return $department;
    }

    public function updateValidation(array $data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|min:3|unique:departments,name,'.$id.',department_id'
        ], [
            'required' => 'The Department field is required.',
            'min' => 'The Department must be at least :min characters.'
        ]);
        
        return $validator;
    }

    public function checkDepartmentName($name)
    {
        $repo = $this->departmentRepository;
        $exist = $repo->searchName($name);

        return $exist;
    }

    public function updateDepartment(array $data, $id)
    {
        $repo = $this->departmentRepository;
        
        if ($repo->find($id)) {
            $result = [
                'name' => $data['name'],
                'updated_at' => Carbon::now('Asia/Dhaka')->format('Y-m-d H:i:s')
            ];

            return $repo->updateDepartment($result, $id);
        } else {
            return false;
        }
    }

    public function destroyDepartment($id)
    {
        $repo = $this->departmentRepository;

        if ($repo->find($id)) {
            $delete = $repo->deleteDepartment($id);
            
            return $delete;
        } else {
            return false;
        }
    }
}
