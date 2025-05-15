<div class="container mt-5 userForm" id="examForm">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">Create Exam</h4>
                </div>
                <div class="errorMsgContainer p-2 text-center"></div>
                <div class="card-body bg-light">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                {{ Form::label('courseId', 'Course Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::select('courseId', 
                                    ['' => 'Select course'] + $list['courses'],
                                    null, [
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
                                    ['' => 'Select Department'] + $list['department'],
                                    null, [
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
                                    ['' => 'Select Semester'] + $list['semester'],
                                    null, [
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
                                    null, [
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
                                    ['' => 'Select Instrutor'] + $list['instructor'],
                                    null, [
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
                    </form>
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
            var courseId = $('.courseId').val();
            var examTitle = $('.examTitle').val();
            var departmentId = $('.departmentId').val();
            var semesterId = $('.semesterId').val();
            var credit = $('.credit').val();
            var examType = $('.examType').val();
            var marks = $('.marks').val();
            var instructorId = $('.instructorId').val();

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
                url : "{{ URL::route('admin.exams.store') }}",
                type : 'POST',
                data : {courseId : courseId, examTitle : examTitle, departmentId : departmentId, semesterId : semesterId, credit : credit, examType: examType, marks : marks, instructorId : instructorId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#examForm").modal('hide');
                        $("#examForm").trigger("reset");
                        $('.examUpdate').load(location.href + ' .examUpdate');
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
    });
</script>