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
                <?php
                    use Illuminate\Support\Facades\Session;
                    
                    $value = Session::get('user_type');
                    if ($value == 3) {
                ?>
                        <th scope="col">Session</th>
                        <th scope="col">Semester</th>
                <?php
                    }
                ?>
                <th scope="col">Department</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    $colValue = 6;
                    if (empty($user->first_name) && empty($user->last_name)) {
                    
            ?>
                <td scope="row" colspan={{ $colValue }}><h3>Add name first</h3>
                    {{ Form::button('Add Profile', [
                        'class' => 'btn btn-success btn-sm',
                        'id' => 'addProfile',
                        'data-id' => Session::get('user_id'),
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
                    <?php                    
                        $value = Session::get('user_type');
                        if ($value == 3) {
                    ?>                        
                            <td scope="row">{{$user->session}}</td>
                            <td scope="row">{{isset($user->semester_name) ? $user->semester_name : null }}</td>
                    <?php
                        }
                    ?>
                    <td scope="row">{{$user->department_name}}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                    'class' => 'btn btn-success btn-sm',
                                    'id' => 'editProfile',
                                    'data-user_id' => $user->user_id,
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '#editProfile', function(e) {
            e.preventDefault();
            let profileId = $(this).data('user_id');
            console.log(profileId);

            $.ajax({
                url: `/profiles/${profileId}/edit`,
                type: 'get',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log(response.records);
                        let data = response.records;
                        $('#updateFirstName').val(data.first_name);
                        $('#updateMiddleName').val(data.middle_name);
                        $('#updateLastName').val(data.last_name);
                        $('#updateProfileModal').modal('show');
                    }
                },
                error: function(response) {
                    if (response.status === 'error') {
                        console.log('error');
                    }
                }
            });
        });
        
        $(document).on('click', '#addProfile', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');
            console.log('Hello! User id is : '+userId);

            $.ajax({
                url: `/profiles/search/${userId}`,
                type: 'get',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#createProfileModal').modal('show');
                    }
                },
                error: function(response) {
                    if (response.status === 'error') {
                        console.log('error');
                    }
                }
            });
        });
    });
</script>