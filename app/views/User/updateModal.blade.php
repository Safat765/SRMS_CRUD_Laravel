<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    {{ Form::open(['url' => '/users', 'method' => 'post', 'novalidate' => true, 'id' => 'courseUpdate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Course</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="col-md-6">
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{ Form::label('registrationNumber', 'Registration Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('registrationNumber', null, 
                                [
                                'class' => 'form-control shadow-lg registrationNumber',
                                'placeholder' => 'Enter registration number',
                                'required' => true
                                ]
                            )}}
                        </div>                        
                        <div class="col-md-6">
                            {{ Form::label('phoneNumber', 'Phone Number', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                            {{ Form::text('phoneNumber', null, 
                                [
                                'class' => 'form-control shadow-lg phoneNumber',
                                'placeholder' => 'Enter phone number',
                                'required' => true
                                ]
                            )}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.btnEdit', function() {
            let userId = $(this).data('id');
            let username = $(this).data('username');
            let email = $(this).data('email');
            let userType = $(this).data('user_type');
            let registrationNumber = $(this).data('registration_number');
            let phoneNumber = $(this).data('phone_number');

            $('.userId').val(userId);
            $('.username').val(username);
            $('.email').val(email);
            $('.userType').val(userType);
            $('.registrationNumber').val(registrationNumber);
            $('.phoneNumber').val(phoneNumber);

            // console.log(userId, username, email, userType, registrationNumber, phoneNumber);
        });

        $(document).on('click', '.updateUser', function(e) {
            e.preventDefault();
            let userId = $('.userId').val();
            let username = $('.username').val();
            let email = $('.email').val();
            let userType = $('.userType').val();
            let registrationNumber = $('.registrationNumber').val();
            let phoneNumber = $('.phoneNumber').val();

            if (!userId && !username && !email && !userType && !registrationNumber && !phoneNumber) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup all the field first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            // console.log(userId, username, email, userType, registrationNumber, phoneNumber);
            $.ajax({
                url : `/users/${userId}`,
                type : 'PUT',
                data : {userId : userId, username : username, email : email, userType : userType, registrationNumber : registrationNumber, phoneNumber : phoneNumber},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.userIndex').load(location.href + ' .userIndex')
                        $("#updateUserModal").modal('hide');
                        $("#courseUpdate").trigger("reset");
                        $('.courseIndex').load(location.href + ' .courseIndex');
                        Swal.fire({
                            title: "User updated successfully!",
                            icon: "success",
                            draggable: true
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
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>