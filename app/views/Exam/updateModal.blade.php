<div class="modal fade" id="updateExamModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <form id="formExam">        
        <div class="modal-dialog modal-xl" style="width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Exam</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer"></div>
                    <div class="row mb-3">
                        {{ Form::hidden('examId', null, ['class' => 'examId1', 'id' => 'examId']) }}
                        <div class="col-md-4">
                            {{ Form::label('courseId', 'Course Name', ['class' => 'form-label']) }}
                            <span style="color: red; font-weight: bold;"> *</span>
                            
                            {{ Form::select('courseId', 
                                ['' => 'Select course'] + $list['courses'],
                                isset($exams->course_id) ? $exams->course_id : null, 
                                [
                                    'class' => 'form-control shadow-lg courseId1',
                                    'id' => 'courseId1',
                                    'required' => 'required',
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            ) }}
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('examTitle', isset($exams->exam_title) ? $exams->exam_title : null,
                                [
                                'class' => 'form-control shadow-lg examTitle1',
                                'placeholder' => 'Enter exam Title',
                                'id' => 'examTitle1',
                                'required' => true
                                ],  Input::old('examTitle')
                            )}}
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('departmentId', 
                                ['' => 'Select Department'] + $list['department'],
                                Input::old('departmentId', isset($exams->department_id) ? $exams->department_id : null), [
                                    'class' => 'form-control shadow-lg departmentId1',
                                    'id' => 'departmentId1',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('semesterId', 'Semester Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('semesterId', 
                                ['' => 'Select Department'] + $list['semester'],
                                Input::old('semesterId', isset($exams->semester_id) ? $exams->semester_id : null), [
                                    'class' => 'form-control shadow-lg semesterId1',
                                    'id' => 'semesterId1',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('credit', isset($exams->credit) ? $exams->credit : null, 
                                [
                                'class' => 'form-control shadow-lg credit1',
                                'id' => 'credit1',
                                'placeholder' => 'Enter exam Title',
                                'required' => true
                                ], Input::old('credit')
                            )}}
                        </div>
                        <div class="col-md-4">
                            {{ Form::label('examType', 'Exam Type', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('examType',
                                [
                                    '' => 'Select Exam type',
                                    $examType['Mid'] => 'Mid',
                                    $examType['Quiz'] => 'Quiz',
                                    $examType['Viva'] => 'Viva',
                                    $examType['Final'] => 'Final'
                                ],
                                isset($exam->exam_type) ? $exam->exam_type : null, [
                                    'class' => 'form-control shadow-lg examType1',
                                    'id' => 'examType1',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                    </div> 
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('marks', 'Marks', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('marks', null, 
                                [
                                'class' => 'form-control shadow-lg marks1',
                                'id' => 'marks1',
                                'placeholder' => 'Enter Total Marks for exam',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('instructorId', 'Instructor Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('instructorId', 
                                ['' => 'Select Department'] + $list['instructor'],
                                Input::old('instructorId', isset($exams->instructor_id) ? $exams->instructor_id : null), [
                                    'class' => 'form-control shadow-lg instructorId1',
                                    'id' => 'instructorId1',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateExam">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.btnEdit', function() {
            var examId = $(this).data('id');
            var courseId = $(this).data('course_id');
            var examTitle = $(this).data('exam_title');
            var departmentId = $(this).data('department_id');
            var semesterId = $(this).data('semester_id');
            var credit = $(this).data('credit');
            var examType = $(this).data('exam_type');
            var marks = $(this).data('marks');
            var instructorId = $(this).data('instructor_id');

            $('#examId').val(examId);
            $('#courseId1').val(courseId);
            $('#examTitle1').val(examTitle);
            $('#departmentId1').val(departmentId);
            $('#semesterId1').val(semesterId);
            $('#credit1').val(credit);
            $('#examType1').val(examType);
            $('#marks1').val(marks);
            $('#instructorId1').val(instructorId);
        });

        $(document).on('click', '#updateExam', function(e) {
            e.preventDefault();
            var examId = $('#examId').val();
            var courseId = $('.courseId1').val();
            var examTitle = $('.examTitle1').val();
            var departmentId = $('.departmentId1').val();
            var semesterId = $('.semesterId1').val();
            var credit = $('.credit1').val();
            var examType = $('.examType1').val();
            var marks = $('.marks1').val();
            var instructorId = $('.instructorId1').val();

            if (!examId && !courseId && !examTitle && !departmentId && !semesterId && !credit && !examType && !marks && !instructorId) {
                
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup all the field first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : `/admin/exams/${examId}`,
                type : 'PUT',
                data : $('#formExam').serialize(),
                success : function (response) {
                    if (response.status === 'success') {
                        $('#examUpdate').load(location.href + ' #examUpdate');
                        $("#updateExamModal").modal('hide');
                        $('#formExam')[0].reset();
                        $('.errorMsgContainer').html('');
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success"
                        });
                    }
                },
                error :function (err)
                {
                    var error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $('.errorMsgContainer').append('<span class="text-danger">'+value+'</span>'+'<br>')
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message
                    });
                }

            });
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $('#examUpdate').load(location.href + ' #examUpdate');
            $('.errorMsgContainer').text("");
        });
    });
</script>