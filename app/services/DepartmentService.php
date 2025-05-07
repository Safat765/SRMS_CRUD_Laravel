<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Validator;

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
            $data = $repo->filter($search);
            $totalDepartment = $data['totalDepartment'];
            $department = $data['department'];
        } else {
			$data = $repo->showAll();
			$totalDepartment = $data['totalDepartment'];
			$department = $data['department'];
        }

        $data = compact('department', 'totalDepartment', 'search');

        return $data;
    }

    public function createValidation(array $data)// check validation
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
        $exist = $repo->createDepartment($name);
        
        return $exist;
    }

    public function checkDepartment($id)
    {
        $repo = $this->departmentRepository;
        $department = $repo->edit($id);

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
        $update = $repo->updateDepartment($data, $id);

        return $update;
    }

    public function destroyDepartment($id)
    {
        $repo = $this->departmentRepository;
        $delete = $repo->deleteDepartment($id);

        return $delete;
    }
}
