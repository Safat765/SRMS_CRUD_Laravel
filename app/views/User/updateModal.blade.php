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
                                    $data['info']['Admin'] => 'Admin', 
                                    $data['info']['Instructor'] => 'Instructor', 
                                    $data['info']['Student']=> 'Student'
                                ], 
                                isset($data['user']->user_type) ? $data['user']->user_type : null, [
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
                                ['' => 'Select Department'] + $data['list']['department'],
                                isset($data['user']->department_id) ? $data['user']->department_id : null, [
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
                                ['' => 'Select Semester'] + $data['list']['semester'],
                                isset($data['user']->semester_id) ? $data['user']->semester_id : null, [
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
            let userId = $(this).data('id');
            let username = $(this).data('username');
            let email = $(this).data('email');
            let userType = $(this).data('user_type');
            let registrationNumber = $(this).data('registration_number');
            let phoneNumber = $(this).data('phone_number');
            let session = $(this).data('session');
            let semesterId = $(this).data('semester_id');
            let departmentId = $(this).data('department_id');

            if (userType == 3) {
                $('#userDepartmentDiv').show();
                $('#userSemesterName').show();
                $('#userSessionName').show();
            } else if (userType == 2) {
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
            let userId = $('.userId').val();
            let username = $('.username').val();
            let email = $('.email').val();
            let userType = $('.userType').val();
            let registrationNumber = $('.registrationNumber').val();
            let phoneNumber = $('.phoneNumber').val();
            let session = $('#userSession').val();
            let semesterId = $('#userSemesterId').val();
            let departmentId = $('#userDepartmentId').val();

            if (userType == 1) {
                if (!username && !email && !userType && !registrationNumber && !phoneNumber) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            } else if (userType == 3) {
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
                    console.log(response.status);
                    if (response.status === 'success') {
                        $('.userIndex').load(location.href + ' .userIndex')
                        $("#updateUserModal").modal('hide');
                        $("#courseUpdate").trigger("reset");
                        $('.courseIndex').load(location.href + ' .courseIndex');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "User update successfully",
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