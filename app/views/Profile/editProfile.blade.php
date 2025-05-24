@extends('layout.main')
@push("title")
    <title>Semester View</title>
@section('main')
<div class="table-responsive" id="editProfileForm">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">First Name</th>
                <th scope="col">Middel Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Registration Number</th>
                @if (Session::get('user_type') == App\Models\User::USER_TYPE_STUDENT)
                    <th scope="col">Session</th>
                    <th scope="col">Semester</th>
                @endif
                @if (in_array(Session::get('user_type'), [App\Models\User::USER_TYPE_INSTRUCTOR, App\Models\User::USER_TYPE_STUDENT]))
                    <th scope="col">Department</th>
                @endif
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    if (empty($user->first_name) && empty($user->last_name)) {
                    
            ?>
                <td scope="row" colspan="6"><h3>Add name first</h3>
                    {{ Form::button('Add Profile', [
                        'class' => 'btn btn-success btn-sm',
                        'id' => 'addProfile',
                        'data-id' => Session::get('user_id'),
                        'data-user_type' => Session::get('user_type'),
                        'data-admin_user_type' => App\Models\User::USER_TYPE_ADMIN,
                        'data-instructor_user_type' => App\Models\User::USER_TYPE_INSTRUCTOR,
                        'data-student_user_type' => App\Models\User::USER_TYPE_STUDENT,
                        'type' => 'button'
                    ])}}
                </td>
            <?php
                } else {
            ?>
                <tr scope="row">
                    <td scope="row">{{$user->first_name}}</td>
                    <td scope="row">{{$user->middle_name}}</td>
                    <td scope="row">{{$user->last_name}}</td>
                    <td scope="row">{{$user->registration_number}}</td>

                   @if (Session::get('user_type') == App\Models\User::USER_TYPE_STUDENT)
                        <td scope="row">{{ $user->session }}</td>
                        <td scope="row">{{ $user->semester_name }}</td>
                    @endif

                    @if (in_array(Session::get('user_type'), [App\Models\User::USER_TYPE_INSTRUCTOR, App\Models\User::USER_TYPE_STUDENT]))
                        <td scope="row">{{ $user->department_name }}</td>
                    @endif
                    <td>
                        <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                    'class' => 'btn btn-success btn-sm',
                                    'id' => 'editProfile',
                                    'data-user_id' => $user->user_id,
                                    'data-user_type' => Session::get('user_type'),
                                    'data-admin_user_type' => App\Models\User::USER_TYPE_ADMIN,
                                    'data-instructor_user_type' => App\Models\User::USER_TYPE_INSTRUCTOR,
                                    'data-student_user_type' => App\Models\User::USER_TYPE_STUDENT,
                                    'type' => 'submit'
                                ])}}
                            </div>
                        </div>
                    </td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
@include('profile.addNameModal')
@include('profile.updateModal')
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '#editProfile', function(e) {
            e.preventDefault();
            var profileId = $(this).data('user_id');
            var userType = $(this).data('user_type');
            var admin = $(this).data('admin_user_type');
            var instructor = $(this).data('instructor_user_type');
            var student = $(this).data('student_user_type');
            var addURL = null;
            
            if (userType == admin) {
                addURL = 'admin';
            } else if (userType == instructor) {
                addURL = 'instructor';
            } else if (userType == student) {
                addURL = 'students';
            }

            $.ajax({
                url: `/${addURL}/profiles/${profileId}/edit`,
                type: 'get',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.records;
                        $('#updateFirstName').val(data.first_name);
                        $('#updateMiddleName').val(data.middle_name);
                        $('#updateLastName').val(data.last_name);
                        $('#updateProfileModal').modal('show');
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    })
                }
            });
        });
        
        $(document).on('click', '#addProfile', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            var userType = $(this).data('user_type');
            var admin = $(this).data('admin_user_type');
            var instructor = $(this).data('instructor_user_type');
            var student = $(this).data('student_user_type');
            var addURL = null;
            
            if (userType == admin) {
                addURL = 'admin';
            } else if (userType == instructor) {
                addURL = 'instructor';
            } else if (userType == student) {
                addURL = 'students';
            }

            $.ajax({
                url: `/${addURL}/profiles/search/${userId}`,
                type: 'get',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#createProfileModal').modal('show');
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    })
                }
            });
        });
    });
</script>