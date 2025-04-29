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
                        <div class="col-md-4">
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
                        
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            {{ Form::label('password', 'Password', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::password('password', 
                                [
                                'class' => 'form-control',
                                'placeholder' => 'Enter password',
                                'id' => 'password',
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
                        <div class="col-md-4" id = 'sessionName'>
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
                        <div class="col-md-4" id="semesterName">
                            {{ Form::label('semesterId', 'Semester ', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('semesterId', 
                                ['' => 'Select Semester'] + Semester::lists('name', 'semester_id'),
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
                        <div class="col-md-4">
                            {{ Form::label('departmentId', 'Department Name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::select('departmentId', 
                                ['' => 'Select Department'] + Department::lists('name', 'department_id'),
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
        $('#sessionName').hide();
        $('#semesterName').hide();
        
        $('#userType').click(function() {            
            let userType = $('#userType').val();
            let semesterId = $('#semesterId').val();
            let session = $('#session').val();

            if (userType == 3) {
                if (session == "" && semesterId == "") {
                    $('#sessionName').show();
                    $('#semesterName').show();
                }
            } else {
                $('#submitCreate').prop('disabled', false);
                $('#sessionName').hide();
                $('#semesterName').hide();
            }
        });
        $(document).on('click', '.submitCreate', function(e) {
            e.preventDefault();

            var username = $("#username").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var userType = $("#userType").val();
            var session = $("#session").val();
            var semesterId = $("#semesterId").val();
            var registrationNumber = $("#registrationNumber").val();
            var phoneNumber = $("#phoneNumber").val();
            var departmentId = $("#departmentId").val();
            // console.log(values);
            if (!username && !email && !password && !userType && !session && !semesterId && !registrationNumber && !phoneNumber && !departmentId) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url: "{{ route('users.store') }}",
                type: 'POST',
                data: $('#userCreateForm').serialize(), // This properly serializes all form data
                dataType: 'json',
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.userForm').load(location.href + ' .userForm', function() {
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