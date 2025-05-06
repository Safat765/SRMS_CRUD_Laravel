<div class="container mt-5 userForm" id="userForm">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">Create User</h4>
                </div>
                <div class="errorMsgContainer p-2 text-center"></div>
                <div class="card-body bg-light">
                    {{ Form::open(['url' => '/users', 'method' => 'post', 'novalidate' => true, 'id' => 'userCreateForm']) }}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            {{ Form::label('username', 'Username', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('username', Input::old('username'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter username',
                                'id' => 'username',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-3">
                            {{ Form::label('email', 'Email', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('email', Input::old('email'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter email',
                                'id' => 'email',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-3">
                            {{ Form::label('password', 'Password', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::password('password', 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter password',
                                'id' => 'password',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-3">
                            <span id="matchPassword"></span>
                            {{ Form::label('confirmPassword', 'Confirm Password', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::password('confirmPassword', 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Confirm Password',
                                'id' => 'confirmPassword',
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
                                Input::old('userType'), [
                                    'class' => 'form-control shadow-lg',
                                    'id' => 'userType',
                                    'required' => true
                                ],
                                [
                                    '' => ['disabled' => 'disabled', 'selected' => 'selected', 'hidden' => 'hidden']
                                ]
                            )}}
                        </div>
                        <div class="col-md-4" id = 'sessionName' style="display: none;">
                            {{ Form::label('session', 'Session', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> (Only for students)</span>
                            {{ Form::text('session', Input::old('session'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter Session',
                                'id' => 'session',
                                'required' => true
                                ]) 
                            }}
                        </div>
                        <div class="col-md-4" id="semesterName" style="display: none;">
                            {{ Form::label('semesterId', 'Semester ', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('semesterId', 
                                ['' => 'Select Semester'] + $list['semester'],
                                Input::old('semesterId', ''), [
                                    'class' => 'form-control shadow-lg',
                                    'id' => 'semesterId',
                                    'required' => true
                                ]
                                
                            )}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', Input::old('registrationNumber'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter registration number',
                                'id' => 'registrationNumber',
                                'required' => true
                                ]) 
                            }}
                        </div>                        
                        <div class="col-md-4">
                            {{ Form::label('phoneNumber', 'Phone Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('phoneNumber', Input::old('phoneNumber'), 
                                [
                                'class' => 'form-control shadow-lg',
                                'placeholder' => 'Enter phone number',
                                'id' => 'phoneNumber',
                                'required' => true
                                ]
                            )}}
                        </div>
                        <div class="col-md-4" id="departmentDiv" style="display: none;">
                            {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('departmentId', 
                                ['' => 'Select Department'] + $list['department'],
                                Input::old('departmentId', ''), [
                                    'class' => 'form-control shadow-lg',
                                    'id' => 'departmentId',
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
                            'class' => 'btn btn-primary btn-block submitCreate',
                            'id' => 'submitCreate'
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#password, #confirmPassword').on('keyup', function() {
            var password = $('#password').val();
            var confirmPassword = $('#confirmPassword').val();
            var message = $('#matchPassword');

            if (password === "" && confirmPassword === "") {
                message.removeClass('las la-times las la-check');
            } else if (password === confirmPassword) {
                message.removeClass('las la-times').addClass('las la-check').css({'font-size': '30px', 'color': 'green'});
            } else {
                message.removeClass('las la-check').addClass('las la-times').css({'font-size': '30px', 'color': 'red'});
            }
        });

        $('#sessionName').hide();
        $('#semesterName').hide();
        $('#semesterName').hide();
        $('#departmentDiv').hide();

        $('#userType').click(function() {            
            let userType = $('#userType').val();
            let semesterId = $('#semesterId').val();
            let session = $('#session').val();

            if (userType == 3) {
                if (session == "" && semesterId == "") {
                    $('#sessionName').show();
                    $('#semesterName').show();
                    $('#departmentDiv').show();
                }
            } else if (userType == 2) {
                $('#submitCreate').prop('disabled', false);
                $('#sessionName').hide();
                $('#semesterName').hide();
                $('#departmentDiv').show();
            } else {
                $('#submitCreate').prop('disabled', false);
                $('#sessionName').hide();
                $('#semesterName').hide();
                $('#departmentDiv').hide();
            }
        });
        $(document).on('click', '.submitCreate', function(e) {
            e.preventDefault();
            var username = $("#username").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var confirmPassword = $('#confirmPassword').val();
            var userType = $("#userType").val();
            var session = $("#session").val();
            var semesterId = $("#semesterId").val();
            var registrationNumber = $("#registrationNumber").val();
            var phoneNumber = $("#phoneNumber").val();
            var departmentId = $("#departmentId").val();

            if (userType == 1) {
                if (!username && !email && !password && !confirmPassword && !userType && !registrationNumber && !phoneNumber) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            } else if (userType == 3) {
                if (!username && !email && !password && !confirmPassword && !userType && !session && !semesterId && !registrationNumber && !phoneNumber && !departmentId) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            } else {
                if (!username && !email && !password && !confirmPassword && !userType && !registrationNumber && !phoneNumber && !departmentId) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Fillup the form first!"
                    });
                    return;
                }
            }
            if (password != confirmPassword) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Password and Confirm Password doesn't match!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url: "{{ route('admin.users.store') }}",
                type: 'POST',
                data: $('#userCreateForm').serialize(),
                dataType: 'json',
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('#userForm').load(location.href + ' #userForm', function() {
                            $(this).slideUp();
                        });
                        $('#userIndex').load(location.href + ' #userIndex');
                        $('.errorMsgContainer').text("");
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "User created successfully",
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
        })
    });
</script>