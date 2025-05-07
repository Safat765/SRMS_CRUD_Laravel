<div class="modal fade" id="createProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    {{ Form::open(['url' => '/courses', 'method' => 'post', 'novalidate' => true, 'id' => 'courseCreate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Profile</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                            {{ Form::hidden('userId', Session::get('user_id'), ['id' => 'profileUserId']) }}
                            {{ Form::hidden('userType', Session::get('user_type'), ['id' => 'profileUserType']) }}
                            <div class="col-md-6">
                                {{ Form::label('firstName', 'First name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('firstName', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter First name',
                                    'id' => 'firstName',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('middleName', 'Middle name', ['class' => 'form-label']) }}
                                {{ Form::text('middleName', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter Middle name',
                                    'id' => 'middleName',
                                    'required' => true
                                    ]
                                )}}
                                <br>
                            </div>
                            <div class="col-md-6">
                                {{ Form::label('lastName', 'Last name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('lastName', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter Last name',
                                    'id' => 'lastName',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="createProfile">Create</button>
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
        $(document).on('click', '#createProfile', function(e) {
            e.preventDefault();
            let firstName = $('#firstName').val();
            let middleName = $('#middleName').val();
            let lastName = $('#lastName').val();
            let userId = $('#profileUserId').val();
            let userType = $('#profileUserType').val();
            let addURL = '';

            if (!firstName && !middleName && !lastName) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");
            
            if (userType == 1) {
                addURL = 'admin';
            } else if (userType == 2) {
                addURL = 'instructor';
            } else if (userType == 3) {
                addURL = 'students';
            }

            $.ajax({
                url : `/${addURL}/profiles/add/${userId}`,
                type : 'get',
                data : {firstName : firstName, middleName : middleName, lastName : lastName},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#createProfileModal").modal('hide');
                        $("#createProfileModal").trigger("reset");
                        $('#editProfileForm').load(location.href + ' #editProfileForm');
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
        });
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $('#editProfileForm').load(location.href + ' #editProfileForm');
            $('.errorMsgContainer').text("");
        });
    });
</script>