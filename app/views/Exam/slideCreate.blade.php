<div class="container mt-5 userForm" id="examForm">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">Create Exam</h4>
                </div>
                
                <div class="errorMsgContainer p-2 text-center"></div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => '/departments', 'method' => 'post', 'novalidate' => true]) }}   
                        <div class="row mb-3">
                            <div class="col-md-4">
                                {{ Form::label('courseId', 'Course Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::select('courseId', 
                                    ['' => 'Select course'] + Course::lists('name', 'course_id'),
                                    Input::old('courseId', ''), [
                                        'class' => 'form-control shadow-lg courseId',
                                        'required' => true
                                    ],
                                    [
                                        '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                    ]
                                )}}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('examTitle', null, 
                                    [
                                    'class' => 'form-control shadow-lg examTitle',
                                    'placeholder' => 'Enter exam Title',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::select('departmentId', 
                                    ['' => 'Select Department'] + Department::lists('name', 'department_id'),
                                    Input::old('departmentId', ''), [
                                        'class' => 'form-control shadow-lg departmentId',
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
                                {{ Form::label('semesterId', 'Semester Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::select('semesterId', 
                                    ['' => 'Select Semester'] + Semester::lists('name', 'semester_id'),
                                    Input::old('semesterId', ''), [
                                        'class' => 'form-control shadow-lg semesterId',
                                        'required' => true
                                    ],
                                    [
                                        '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                    ]
                                )}}
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('credit', null, 
                                    [
                                    'class' => 'form-control shadow-lg credit',
                                    'placeholder' => 'Enter Credit',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                        </div> 
                        <div class="row mb-3">
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
                                    Input::old('examType'), [
                                        'class' => 'form-control shadow-lg examType',
                                        'required' => true
                                    ],
                                    [
                                        '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                    ]
                                )}}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('marks', 'Marks', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('marks', null, 
                                    [
                                    'class' => 'form-control shadow-lg marks',
                                    'placeholder' => 'Enter Total Marks for exam',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('instructorId', 'Instructor Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::select('instructorId', 
                                    ['' => 'Select Instrutor'] + User::where('user_type', 2)->lists('username', 'user_id'),
                                    Input::old('instructorId', ''), [
                                        'class' => 'form-control shadow-lg instructorId',
                                        'required' => true
                                    ],
                                    [
                                        '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                    ]
                                )}}
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            {{ Form::submit('Create', 
                                [
                                'class' => 'btn btn-primary btn-block addExam'
                                ]
                            )}}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
<br><hr><br>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.addExam', function(e) {
            e.preventDefault();
            let courseId = $('.courseId').val();
            let examTitle = $('.examTitle').val();
            let departmentId = $('.departmentId').val();
            let semesterId = $('.semesterId').val();
            let credit = $('.credit').val();
            let examType = $('.examType').val();
            let marks = $('.marks').val();
            let instructorId = $('.instructorId').val();

            if (!courseId && !examTitle && !departmentId && !semesterId && !credit && !examType && !marks && !instructorId) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : "{{ URL::route('exams.store') }}",
                type : 'post',
                data : {courseId : courseId, examTitle : examTitle, departmentId : departmentId, semesterId : semesterId, credit : credit, examType: examType, marks : marks, instructorId : instructorId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#examForm").modal('hide');
                        $("#examForm").trigger("reset");
                        $('.examUpdate').load(location.href + ' .examUpdate');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Exam created successfully",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error :function (err)
                {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $('.errorMsgContainer').append('<span class="text-danger">'+value+'</span>'+'<br>')
                    });
                }
            });
        });
    });
</script>