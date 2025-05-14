@extends('layout.main')
@push("title")
<title>Course View</title>
@section('main')
<div class="table-responsive courseIndex">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">
                <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-success m-2">Create</a>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/admin/courses')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>
        </div>
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('admin.courses.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by courses name and credit',
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
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Course : {{ $totalCourse }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Course name</th>
                <th scope="col">Credit</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($course as $courses)
            <tr>
                <td scope="row" class="p-3">{{$courses->name}}</td>
                <td scope="row" class="p-3">{{$courses->credit}}</td>
                <td scope="row" class="p-3">
                    @if ($courses->status == $ACTIVE)
                    <a href="" data-id="{{ $courses->course_id }}">
                        <span class="badge bg-success" id="statusBtn" data-id="{{ $courses->course_id }}">Active</span>
                    </a>
                    @else
                    <a href="" data-id="{{ $courses->course_id }}">
                        <span class="badge bg-danger" id="statusBtn" data-id="{{ $courses->course_id }}">Inactive</span>
                    </a>
                    @endif
                </td>
                <td class="d-flex justify-content-center gap-2 p-3">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                'class' => 'btn btn-success btn-sm btnEdit',
                                'type' => 'submit',
                                'id' => 'btnEdit',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#updateModal',
                                'data-id' => $courses->course_id,
                                'data-name' => $courses->name,
                                'data-credit' => $courses->credit
                            ])}}
                        </div>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                'class' => 'btn btn-danger btn-sm',
                                'id' => 'courseDelete',
                                'data-id' => $courses->course_id,
                                'type' => 'submit'
                            ])}}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{ $course->links() }}
    </div>
</div>

@include('course.createModal')
@include('course.updateModal')

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '#courseDelete', function() {
            let courseId = $(this).data('id');
            $.ajax({
                url : `/admin/courses/${courseId}`,
                type : 'delete',
                data : {id : courseId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.courseIndex').load(location.href + ' .courseIndex');
                        Swal.fire({
                            title: "Good job!",
                            text: "Course deleted successfully",
                            icon: "success"
                        });
                    }
                },
                error :function (err)
                {
                    if (err.status === 'error') {
                        console.log(err.status);
                    }
                    Swal.fire({
                        title: "Oops...",
                        text: err.responseJSON.message,
                        icon: "error"
                    });
                }
            });
        });
        $(document).on('click', '#statusBtn', function(e) {
            e.preventDefault();
            let courseId = $(this).data('id');

            let status = $("#statusBtn").text().trim();
            console.log(courseId, status);
            $.ajax({
                url : `/admin/courses/status/${courseId}`,
                type : 'get',
                data : {id : courseId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.courseIndex').load(location.href + ' .courseIndex')
                        if (status === 'Active') {
                            Swal.fire({
                                title: "Good job!",
                                text: "Course Inactivated successfully",
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                title: "Good job!",
                                text: "Course Activated successfully",
                                icon: "success"
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error changing status. Please try again."
                        });
                    }
                },
                error :function (err)
                {
                    if (err.status === 'error') {
                        console.log(err.status);
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message
                    });
                }
            });
        });
    });
</script>