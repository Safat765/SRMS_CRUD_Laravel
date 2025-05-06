@extends('layout.main')

@push("title")
<title>Student List</title>
@section('main')

<div class="table-responsive pt-2" id="studentList">
    <div class="bg-warning text-black text-center mx-5">
        <h5>Total Students : {{ $totalStudent }}</h5>
    </div>
    <a href="{{ URL::route('instructor.marks.index') }}" class="col-md-1 btn btn-danger">Back</a>
    <hr>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr class="bg-info text-white">
                <td scope="row" colspan="6" class="fw-bold fs-3 text-center text-info">
                    Students of Course :
                    @foreach ($results as $result)
                        {{ $result->course_name }}
                        <?php break; ?>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <th>Registration Number</th>
                <th>Email</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $result)
                <tr>
                    <td>{{ $result->username }}</td>
                    <td>{{ $result->registration_number }}</td>
                    <td>{{ $result->email }}</td>
                    <td>{{ $result->department_name }}</td>
                    <td>{{ $result->semester_name }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                            @if (!empty($marks[$result->user_id]))
                                {{ Form::open(['url' => '/instructor/marks/'.$result->user_id, 'method' => 'get']) }}
                                {{ Form::hidden('examId', $result->exam_id) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                            'class' => 'btn btn-info btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-plus"></i>', [
                                        'class' => 'btn btn-success btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                                <div class="text-center">
                                    {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                        'class' => 'btn btn-warning btn-sm',
                                        'type' => 'submit',
                                        'id' => 'marksUpdate',
                                        'data-studentid' => $result->user_id,
                                        'data-examid' => $result->exam_id,
                                    ])}}
                                </div>
                                {{ Form::open(['url' => '/instructor/marks/'.$result->user_id, 'method' => 'delete']) }}
                                {{ Form::hidden('username', isset($result->username) ? $result->username : null) }}
                                {{ Form::hidden('courseName', isset($result->course_name) ? $result->course_name : null) }}
                                {{ Form::hidden('examId', isset($result->exam_id) ? $result->exam_id : null) }}
                                    <div class="text-center">
                                        {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                            'class' => 'btn btn-danger btn-sm',
                                            'type' => 'submit'
                                        ])}}
                                    </div>
                                {{ Form::close() }}
                            @else
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-eye"></i>', [
                                        'class' => 'btn btn-info btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                                <div class="text-center">
                                    {{ Form::button(HTML::decode('<i class="las la-plus"></i>'), [
                                        'class' => 'btn btn-success btn-sm',
                                        'type' => 'submit',
                                        'id' => 'marksCreate',
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#createMarksModal',
                                        'data-studentid' => $result->user_id,
                                        'data-username' => $result->username,
                                        'data-courseid' => $result->course_id,
                                        'data-cname' => $result->course_name,
                                        'data-depName' => $result->department_name,
                                        'data-examid' => $result->exam_id,
                                        'data-etitle' => $result->exam_title,
                                        'data-semesterid' => $result->semester_id,
                                        'data-semname' => $result->semester_name,
                                        'data-marks' => $result->marks,
                                    ])}}
                                </div>
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-edit"></i>', [
                                        'class' => 'btn btn-warning btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                                <div class="text-center">
                                    {{ Form::button('<i class="las la-trash-alt"></i>', [
                                        'class' => 'btn btn-danger btn-sm',
                                        'disabled' => 'disabled'
                                    ]) }}
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br><hr><hr><br><br>
@include('mark.createModal')
@include('mark.updateModal')
@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#marksUpdate', function() {
            let userId = $(this).data('studentid');
            let examId = $(this).data('examid');
            
            $.ajax({
                url : `/instructor/marks/${userId}/edit`,
                method: 'GET',
                data: {examId : examId},
                success:function(response)
                {
                    if (response.status == 'success') {
                        let data = response.records[0];
                        
                        $('#updateTotalMarks').val(data.total_marks);
                        $('#updateUsername').val(data.username);
                        $('#updateStudentId').val(data.user_id);
                        $('#updateExamTitle').val(data.exam_title);
                        $('#updateExamId').val(data.exam_id);
                        $('#updateSemesterName').val(data.semester_name);
                        $('#updateSemesterId').val(data.semester_id);
                        $('#updateCourseName').val(data.course_name);
                        $('#updateCourseId').val(data.course_id);
                        $('#updateGivenMark').val(data.given_marks);
                        $('#updateMarksId').val(data.mark_id);

                        $('#updateMarksModal').modal('show');
                    }
                },
                error:function(xhr)
                {
                    console.log(xhr.responseText);
                    alert('Something went wrong while fetching student marks!');
                }
            });
        });
    });
</script>