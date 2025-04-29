<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateModalLabel">
    {{ Form::open(['url' => '/users', 'method' => 'post', 'novalidate' => true, 'id' => 'courseUpdate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Add Marks</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::hidden('totalMarks', null, ['id' => 'totalMarks']) }}
                            {{ Form::hidden('username', null, ['id' => 'username']) }}
                            {{ Form::hidden('semesterId', null, ['id' => 'semesterId']) }}
                            {{ Form::hidden('courseId', null, ['id' => 'courseId']) }}
                            {{ Form::hidden('examId', null, ['id' => 'examId']) }}
                            {{ Form::hidden('studentId', null, ['id' => 'studentId']) }}
                            
                            {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('givenMark', null, 
                                [
                                'class' => 'form-control shadow-lg',
                                'id' => 'givenMark',
                                'required' => true
                                ], Input::old('givenMark')
                            )}}
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('courseName', 'Course', ['class' => 'form-label']) }}
                            <span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('courseName', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'courseName',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('semesterName', 'Semester', ['class' => 'form-label']) }}
                            <span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('semesterName', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'semesterName',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                            <br>
                        </div>
                        <div class="col-md-6">
                            {{ Form::label('examTitle', 'Exam Title', ['class' => 'form-label']) }}
                            <span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('examTitle', null,
                                [
                                'class' => 'form-control shadow-lg fw-bold',
                                'id' => 'examTitle',
                                'required' => true,
                                'readonly' => true
                                ]
                            )}}
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateUser">Update</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '#marksCreate', function() {
            let totalMarks = $(this).data('marks');
            let username = $(this).data('username');
            let courseName = $(this).data('cname');
            let semesterName = $(this).data('semname');
            let examTitle = $(this).data('etitle');
            let studentId = $(this).data('studentid');
            let examId = $(this).data('examid');
            let semesterId = $(this).data('semesterid');
            let courseId = $(this).data('courseid');

            $('#totalMarks').val(totalMarks);
            $('#username').val(username);
            $('#courseName').val(courseName);
            $('#semesterName').val(semesterName);
            $('#examTitle').val(examTitle);
            $('#studentId').val(studentId);
            $('#examId').val(examId);
            $('#semesterId').val(semesterId);
            $('#courseId').val(courseId);
            $('#givenMark').attr('placeholder', 'Enter mark out of ' + totalMarks);

            // console.log(totalMarks, username, courseName, semesterName, examTitle);
        });

        $(document).on('click', '.updateUser', function(e) {
            e.preventDefault();
            let totalMarks = $('#totalMarks').val();
            let username = $('#username').val();
            let courseName = $('#courseName').val();
            let semesterName = $('#semesterName').val();
            let examTitle = $('#examTitle').val();
            let givenMark = $('#givenMark').val();
            let studentId = $('#studentId').val();
            let examId = $('#examId').val();
            let semesterId = $('#semesterId').val();
            let courseId = $('#courseId').val();

            if (!totalMarks && !username && !courseName && !semesterName && !examTitle && !givenMark && !studentId && !examId && !semesterId && !courseId) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : `/marks/go`,
                type : 'post',
                data : {totalMarks : totalMarks, username : username, courseName: courseName, givenMark : givenMark, studentId : studentId, examId : examId, semesterId : semesterId, courseId : courseId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('#marksIndex').load(location.href + ' #marksIndex')
                        $('#studentList').load(location.href + ' #studentList')
                        $("#updateUserModal").modal('hide');
                        $("#courseUpdate").trigger("reset");
                        Swal.fire({
                            title: "Marks added successfully!",
                            icon: "success",
                            draggable: true
                        });
                    }
                },                
                error: function(xhr, status, error) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.message); // Show the error message
                    } catch (e) {
                        alert("An unexpected error occurred."); // In case JSON parsing fails
                    }
                }
            });
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>