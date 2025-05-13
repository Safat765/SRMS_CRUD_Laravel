<div class="modal fade" id="updateMarksModal" tabindex="-1" aria-labelledby="updateModalLabel">
    <form id="updateMarksForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Marks</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer"></div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::hidden('marksId', null, ['id' => 'updateMarksId']) }}
                            {{ Form::hidden('totalMarks', null, ['id' => 'updateTotalMarks']) }}
                            {{ Form::hidden('username', null, ['id' => 'updateUsername']) }}
                            {{ Form::hidden('semesterId', null, ['id' => 'updateSemesterId']) }}
                            {{ Form::hidden('courseId', null, ['id' => 'updateCourseId']) }}
                            {{ Form::hidden('examId', null, ['id' => 'updateExamId']) }}
                            {{ Form::hidden('studentId', null, ['id' => 'updateStudentId']) }}
                            {{ Form::label('givenMark', 'Mark', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('givenMark', '', 
                                [
                                'class' => 'form-control shadow-lg',
                                'id' => 'updateGivenMark',
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
                                'id' => 'updateCourseName',
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
                                'id' => 'updateSemesterName',
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
                                'id' => 'updateExamTitle',
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
                    <button type="button" class="btn btn-primary" id="updataMarks">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#updataMarks', function(e) {
            e.preventDefault();
            let totalMarks = $('#updateTotalMarks').val();
            let givenMark = $('#updateGivenMark').val();
            let marksId = $('#updateMarksId').val();
            let username = $('#updateUsername').val();
            let courseName = $('#updateCourseName').val();
            let examId = $('#updateExamId').val();

            if (!totalMarks && !courseName && !givenMark && !examId && !marksId && !username) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");
            $.ajax({
                url: `/instructor/marks/${marksId}`,
                method: 'PUT',
                data: {
                    totalMarks: totalMarks,
                    givenMark: givenMark,
                    username : username,
                    courseName : courseName,
                    examId : examId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#studentList').load(location.href + ' #studentList')
                        $('#marksIndex').load(location.href + ' #marksIndex')
                        $("#updateMarksModal").modal('hide');
                        $("#updateMarksModal").trigger("reset");
                        Swal.fire({
                            title: "Good job!",
                            text: "Marks updated successfully!",
                            icon: "success"
                        });
                    }
                },                
                error: function(response) {
                    try {
                        if (response.responseJSON.status === 'error') {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: response.responseJSON.message
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "An unexpected error occurred."
                        });
                    }
                }
            });
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#updateMarksModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>