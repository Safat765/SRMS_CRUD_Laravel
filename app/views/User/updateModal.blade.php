<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <form id="courseUpdate">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Course</h5>
                    <button type="button" id="userUpdateModalClose" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::hidden('userId', null, ['class' => 'userId', 'id' => 'userId']) }}
                            {{ Form::hidden('adminUserType', App\Models\User::USER_TYPE_ADMIN, ['id' => 'userUpdateAdminUserType']) }}
                            {{ Form::hidden('instructorUserType', App\Models\User::USER_TYPE_INSTRUCTOR, ['id' => 'userUpdateInstructorUserType']) }}
                            {{ Form::hidden('studentUserType', App\Models\User::USER_TYPE_STUDENT, ['id' => 'userUpdateStudentUserType']) }}
                            {{ Form::label('username', 'Username', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('username', null, 
                                [
                                'class' => 'form-control shadow-lg username',
                                'placeholder' => 'Enter username',
                                'required' => true
                                ]
                            )}}
                        </div>                        
                        <div class="col-md-6">
                            {{ Form::label('email', 'Email', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('email', null,
                                [
                                'class' => 'form-control shadow-lg email',
                                'placeholder' => 'Enter email',
                                'required' => true
                                ]
                            )}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('userType', 'User Type', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('userType',
                                [
                                    '' => 'Select user type',
                                    $info['Admin'] => 'Admin', 
                                    $info['Instructor'] => 'Instructor', 
                                    $info['Student']=> 'Student'
                                ], 
                                isset($user->user_type) ? $user->user_type : null, [
                                    'class' => 'form-control shadow-lg userType',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                        <div class="col-md-4" id="userDepartmentDiv" style="display: none;">
                            {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('departmentId', 
                                ['' => 'Select Department'] + $list['department'],
                                isset($user->department_id) ? $user->department_id : null, [
                                    'class' => 'form-control shadow-lg',
                                    'id' => 'userDepartmentId',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                        <div class="col-md-4" id="userSemesterName" style="display: none;">
                            {{ Form::label('semesterId', 'Semester ', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('semesterId', 
                                ['' => 'Select Semester'] + $list['semester'],
                                isset($user->semester_id) ? $user->semester_id : null, [
                                    'class' => 'form-control shadow-lg',
                                    'id' => 'userSemesterId',
                                    'required' => true
                                ]
                                
                            )}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', null, 
                                [
                                'class' => 'form-control shadow-lg registrationNumber',
                                'placeholder' => 'Enter registration number',
                                'required' => true
                                ]
                            )}}
                        </div>                        
                        <div class="col-md-4">
                            {{ Form::label('phoneNumber', 'Phone Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('phoneNumber', null, 
                                [
                                'class' => 'form-control shadow-lg phoneNumber',
                                'placeholder' => 'Enter phone number',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-4" id = 'userSessionName' style="display: none;">
                            {{ Form::label('session', 'Session', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> (Only for students)</span>
                            {{ Form::text('session', Input::old('session'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Session',
                                'id' => 'userSession',
                                'required' => true
                                ]) 
                            }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" id="close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateUser">Update</button>
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
        $('#userDepartmentDiv').hide();
        $('#userSemesterName').hide();
        $('#userSessionName').hide();

        $(document).on('click', '.btnEdit', function() {
            var userId = $(this).data('id');
            var username = $(this).data('username');
            var email = $(this).data('email');
            var userType = $(this).data('user_type');
            var registrationNumber = $(this).data('registration_number');
            var phoneNumber = $(this).data('phone_number');
            var session = $(this).data('session');
            var semesterId = $(this).data('semester_id');
            var departmentId = $(this).data('department_id');
            var admin = $(this).data('admin_user_type');
            var instructor = $(this).data('instructor_user_type');
            var student = $(this).data('student_user_type');

            if (userType == student) {
                $('#userDepartmentDiv').show();
                $('#userSemesterName').show();
                $('#userSessionName').show();
            } else if (userType == instructor) {
                $('#userDepartmentDiv').show();
            }

            $('.userId').val(userId);
            $('.username').val(username);
            $('.email').val(email);
            $('.userType').val(userType);
            $('.registrationNumber').val(registrationNumber);
            $('.phoneNumber').val(phoneNumber);
            $('#userSession').val(session);
            $('#userSemesterId').val(semesterId);
            $('#userDepartmentId').val(departmentId);
        });

        $(document).on('click', '.updateUser', function(e) {
            e.preventDefault();
            var userId = $('.userId').val();
            var username = $('.username').val();
            var email = $('.email').val();
            var userType = $('.userType').val();
            var registrationNumber = $('.registrationNumber').val();
            var phoneNumber = $('.phoneNumber').val();
            var session = $('#userSession').val();
            var semesterId = $('#userSemesterId').val();
            var departmentId = $('#userDepartmentId').val();
            var admin = $('#userUpdateAdminUserType').val();
            var instructor = $('#userUpdateInstructorUserType').val();
            var student = $('#userUpdateStudentUserType').val();

            if (userType == admin) {
                if (!username && !email && !userType && !registrationNumber && !phoneNumber) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            } else if (userType == instructor) {
                if (!username && !email && !userType && !session && !semesterId && !registrationNumber && !phoneNumber && !departmentId) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            } else {
                if (!username && !email && !userType && !registrationNumber && !phoneNumber && !departmentId) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : `/admin/users/${userId}`,
                type : 'PUT',
                data : {userId : userId, username : username, email : email, userType : userType, registrationNumber : registrationNumber, phoneNumber : phoneNumber, session : session, semesterId : semesterId, departmentId : departmentId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.userIndex').load(location.href + ' .userIndex')
                        $("#updateUserModal").modal('hide');
                        $("#courseUpdate").trigger("reset");
                        $('.courseIndex').load(location.href + ' .courseIndex');
                        Swal.fire({
                            icon: "success",
                            title: "success",
                            text: `'${username}' User updated successfully`
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
        $(document).on('click', '#close, #userUpdateModalClose', function(e) {
            e.preventDefault();
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
            $('#userDepartmentDiv').hide();
            $('#userSemesterName').hide();
            $('#userSessionName').hide();
        });
    });
</script>