<div class="modal fade" id="createMarksModal" tabindex="-1" aria-labelledby="updateModalLabel">
    <form>
        <div class="modal-dialog" id="marksForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="create">Add Marks</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer"></div>
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
                                ], null
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
                    <button type="button" class="btn btn-primary" id="updateMarks">Update</button>
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
        $(document).on('click', '#marksCreate', function() {
            var totalMarks = $(this).data('marks');
            var username = $(this).data('username');
            var courseName = $(this).data('cname');
            var semesterName = $(this).data('semname');
            var examTitle = $(this).data('etitle');
            var studentId = $(this).data('studentid');
            var examId = $(this).data('examid');
            var semesterId = $(this).data('semesterid');
            var courseId = $(this).data('courseid');

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
        });

        $(document).on('click', '#updateMarks', function(e) {
            e.preventDefault();
            var totalMarks = $('#totalMarks').val();
            var username = $('#username').val();
            var courseName = $('#courseName').val();
            var semesterName = $('#semesterName').val();
            var examTitle = $('#examTitle').val();
            var givenMark = $('#givenMark').val();
            var studentId = $('#studentId').val();
            var examId = $('#examId').val();
            var semesterId = $('#semesterId').val();
            var courseId = $('#courseId').val();

            if (!totalMarks && !username && !courseName && !semesterName && !examTitle && !givenMark && !studentId && !examId && !semesterId && !courseId) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");
            
            givenMark = Number(givenMark);
            totalMarks = Number(totalMarks);

            if ((givenMark > totalMarks) && (givenMark > -1)) {
                Swal.fire({
                    icon: "error",
                    title: "Wrong Input...",
                    text: `Given marks should be 0 to less than or equal to ${totalMarks}`
                });
                return;
            }

            $.ajax({
                url : `/instructor/marks/go`,
                type : 'POST',
                data : {totalMarks : totalMarks, username : username, courseName: courseName, givenMark : givenMark, studentId : studentId, examId : examId, semesterId : semesterId, courseId : courseId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('#marksIndex').load(location.href + ' #marksIndex')
                        $('#studentList').load(location.href + ' #studentList')
                        $("#createMarksModal").modal('hide');
                        $("#marksForm").trigger("reset");
                        Swal.fire({
                            title: "Good job!",
                            text: "Marks added successfully!",
                            icon: "success"
                        });
                    }
                },                
                error: function(xhr, status, error) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message
                        });
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
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>