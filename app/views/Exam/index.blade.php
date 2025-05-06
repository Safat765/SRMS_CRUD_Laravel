@extends('layout.main')
@push("title")
<title>Exam View</title>
@section('main')
<div class="table-responsive examUpdate" id="examUpdate">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">
                <div class="d-flex justify-content-start mt-2 mb-3">
                    <button class="btn btn-success" id="createExam">Create Exam</button>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/admin/exams')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>
        </div>
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('admin.exams.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Exam Title or credit',
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
        @include('exam.slideCreate', ['examType' => $examType])
    </div>
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Exam : {{ $totalExams }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Exam Title</th> 
                <th scope="col">Course</th>               
                <th scope="col">Department</th>
                <th scope="col">Semester</th>
                <th scope="col">Exam Type</th>
                <th scope="col">Credit</th>
                <th scope="col">Marks</th>
                <th scope="col">Assigned Instructor</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($exams as $exam)
            <tr>
                <td scope="row" class="p-3">{{$exam->exam_title}}</td>
                <td scope="row" class="p-3">{{ $exam->course_name }}</td>
                <td scope="row" class="p-3">{{$exam->department_name}}</td>
                <td scope="row" class="p-3">{{$exam->semester_name}}</td>
                <td scope="row" class="p-3">
                    @if($exam->exam_type == 1)
                        Mid
                    @elseif($exam->exam_type == 2)
                        Quiz
                    @elseif($exam->exam_type == 3)
                        Viva
                    @else
                        Final Term
                    @endif
                </td>
                <td scope="row" class="p-3">{{$exam->credit}}</td>
                <td scope="row" class="p-3">{{$exam->marks}}</td>
                <td scope="row" class="p-3">{{$exam->username}}</td>
                <td class="d-flex justify-content-center gap-2 p-3">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                'class' => 'btn btn-success btn-sm btnEdit',
                                'type' => 'submit',
                                'id' => 'btnEdit',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#updateExamModal',
                                'data-id' => $exam->exam_id,
                                'data-exam_title' => $exam->exam_title,
                                'data-course_id' => $exam->course_id,
                                'data-department_id' => $exam->department_id,
                                'data-semester_id' => $exam->semester_id,
                                'data-credit' => $exam->credit,
                                'data-exam_type' => $exam->exam_type,
                                'data-marks' => $exam->marks,
                                'data-instructor_id' => $exam->instructor_id,
                            ])}}
                        </div>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                'class' => 'btn btn-danger btn-sm',
                                'id' => 'deleteBtn',
                                'data-id' => $exam->exam_id,
                                'type' => 'button'
                            ])}}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @include('exam.updateModal', ['examType' => $examType, 'list' => $list])
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", "#deleteBtn", function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: `/admin/exams/${id}`,
                    type: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('.examUpdate').load(location.href + ' .examUpdate')
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert("Error deleting exam. Please try again.");
                        $('.examUpdate').load(location.href + ' .examUpdate')
                    }
                });
            } else {
                console.log("Cenceled deleting.");
            }
        });
        $(document).on("click", "#createExam", function(e) {
            e.preventDefault();
            $("#createForm").load('slideCreate', function() {
                $(this).slideToggle(500);
            });
        });
    })
</script>