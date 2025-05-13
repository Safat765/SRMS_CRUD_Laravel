@extends('layout.main')
@push("title")
<title>Department View</title>
@section('main')
<div class="table-responsive departmentUpdate">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">
                <div class="d-flex justify-content-start mt-2 mb-3">
                    <button class="btn btn-success" id="createDepartment">Create Department</button>
                </div>
            </div>
            <div class="p-1">
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/admin/departments')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>  
        </div>
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('admin.departments.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $data['search'], [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Department name',
                    'required' => true
                    ])}}
                </div>                                            
                <div class="p-1">
                    {{ Form::submit('Search', [
                    'class' => 'btn btn-primary btn-block'
                    ]) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>     
    </div>
    <div id="createForm" style="display: none;">
        @include('department.slideDepCreate')
    </div>
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Department : {{ $data['totalDepartment'] }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Department Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['department'] as $departments)
            <tr>
                <td scope="row" class="p-3">{{$departments->name}}</td>
                <td class="d-flex justify-content-center gap-2 p-3">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                'class' => 'btn btn-success btn-sm btnEdit',
                                'type' => 'submit',
                                'id' => 'btnEdit',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#updateDepartmentModal',
                                'data-id' => $departments->department_id,
                                'data-name' => $departments->name
                            ])}}
                        </div>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                'class' => 'btn btn-danger btn-sm',
                                'id' => 'deleteBtn',
                                'data-id' => $departments->department_id,
                                'data-name' => $departments->name,
                                'type' => 'button'
                            ])}}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $data['department']->links() }}
    </div>
    @include('department.updateModal')
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", "#deleteBtn", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let name = $(this).data('name');

            if (confirm("Are you sure you want to delete '" + name + "' ?")) {
                $.ajax({
                    url: `/admin/departments/${id}`,
                    type: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('.departmentUpdate').load(location.href + ' .departmentUpdate');
                            Swal.fire({
                                title: "Good job!",
                                text: "Department deleted successfully",
                                icon: "success"
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error deleting user. Please try again."
                        });
                        $('.departmentUpdate').load(location.href + ' .departmentUpdate')
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Cenceled deleting '"+ name +"'"
                });
            }
        });
        $(document).on("click", "#createDepartment", function(e) {
            e.preventDefault();
            $("#createForm").load('slideDepCreate', function() {
                $(this).slideToggle(500);
            });
        });
    })
</script>
