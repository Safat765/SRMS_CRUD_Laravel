<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="updateProfileForm">
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
                            {{ Form::hidden('userId', Illuminate\Support\Facades\Session::get('user_id'), ['id' => 'updateUserId']) }}
                            {{ Form::hidden('userType', Illuminate\Support\Facades\Session::get('user_type'), ['id' => 'updateUserType']) }}
                            {{ Form::hidden('adminUserType', App\Models\User::USER_TYPE_ADMIN, ['id' => 'updateAdminUserType']) }}
                            {{ Form::hidden('instructorUserType', App\Models\User::USER_TYPE_INSTRUCTOR, ['id' => 'updateInstructorUserType']) }}
                            {{ Form::hidden('studentUserType', App\Models\User::USER_TYPE_STUDENT, ['id' => 'updateStudentUserType']) }}
                            <div class="col-md-6">
                                {{ Form::label('firstName', 'First name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('firstName', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter First name',
                                    'id' => 'updateFirstName',
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
                                    'id' => 'updateMiddleName',
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
                                    'id' => 'updateLastName',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateProfile">Update</button>
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
        $(document).on('click', '#updateProfile', function(e) {
            e.preventDefault();
            let firstName = $('#updateFirstName').val();
            let middleName = $('#updateMiddleName').val();
            let lastName = $('#updateLastName').val();
            let userId = $('#updateUserId').val();
            let userType = $('#updateUserType').val();
            let addURL = '';
            let admin = $('#updateAdminUserType').val();
            let instructor = $('#updateInstructorUserType').val();
            let student = $('#updateStudentUserType').val();

            if (!firstName && !middleName && !lastName) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");
            
            if (userType == admin) {
                addURL = 'admin';
            } else if (userType == instructor) {
                addURL = 'instructor';
            } else if (userType == student) {
                addURL = 'students';
            }

            $.ajax({
                url : `/${addURL}/profiles/add/${userId}`,
                type : 'GET',
                data : {firstName : firstName, middleName : middleName, lastName : lastName},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#updateProfileModal").modal('hide');
                        $("#updateProfileModal").trigger("reset");
                        $('#editProfileForm').load(location.href + ' #editProfileForm');
                        Swal.fire({                            
                            title: "Good job!",
                            text: "Profile updated successfully",
                            icon: "success"
                        });
                    }
                },
                error :function (err)
                {
                    let error = err.responseJSON;
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
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $('#editProfileForm').load(location.href + ' #editProfileForm');
            $('.errorMsgContainer').text("");
        });
    });
</script>