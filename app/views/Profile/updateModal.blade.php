<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            {{ Form::hidden('userId', Session::get('user_id'), ['id' => 'updateUserId']) }}
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
        $(document).on('click', '#updateProfile', function(e) {
            e.preventDefault();
            let firstName = $('#updateFirstName').val();
            let middleName = $('#updateMiddleName').val();
            let lastName = $('#updateLastName').val();
            let userId = $('#updateUserId').val();

            if (!firstName && !middleName && !lastName) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            console.log(firstName, middleName, lastName);
            $.ajax({
                url : `/profiles/add/${userId}`,
                type : 'get',
                data : {firstName : firstName, middleName : middleName, lastName : lastName},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#updateProfileModal").modal('hide');
                        $("#updateProfileModal").trigger("reset");
                        $('#editProfileForm').load(location.href + ' #editProfileForm');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "User Updated successfully",
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