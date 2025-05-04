@extends('layout.main')
@push("title")
<title>User View</title>
@section('main')
<div class="table-responsive userIndex" id="userIndex">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">
                <div class="d-flex justify-content-start mt-2 mb-3">
                    <button class="btn btn-success" id="createUser">Create User</button>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/users')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>
        </div>        
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('users.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Username or Email',
                    'required' => true
                    ])}}
                </div>                                            
                <div class="p-1">
                    {{ Form::submit('Search', [
                    'class' => 'btn btn-primary btn-block'
                    ]) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>     
    </div>
    <div id="createForm" style="display: none;">
        @include('user.slideCreate', ['info' => $info])
    </div>
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total User : {{ $totalUsers }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" id="userTable" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Email</th>                
                <th scope="col">User Type</th>
                <th scope="col">Registration Number</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr @if($user->status == 0) class="disabled-row" style="opacity: 0.3;" @endif>
                <td scope="row" class="p-3">{{$user->username}}</td>
                <td scope="row" class="p-3">{{$user->email}}</td>
                <td scope="row" class="p-3"> 
                    {{ $user->user_type == $info['Admin'] ? 'Admin' : ($user->user_type == $info['Instructor'] ? 'Instructor' : 'Student') }}</td>
                </td>
                <td scope="row" class="p-3">{{$user->registration_number}}</td>
                <td scope="row" class="p-3">{{$user->phone_number}}</td>
                <td scope="row" class="p-3">
                    @if ($user->status == $info['Active'])
                    <a href="" data-id="{{ $user->user_id }}">
                        <span class="badge bg-success" id="userStatusBtn" data-id="{{ $user->user_id }}">Active</span>
                    </a>
                    @else
                    <a href="" data-id="{{ $user->user_id }}">
                        <span class="badge bg-danger" id="userStatusBtn" data-id="{{ $user->user_id }}">Inactive</span>
                    </a>
                    @endif
                </td>
                <td class="p-3">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                'class' => 'btn btn-success btn-sm btnEdit',
                                'type' => 'submit',
                                'id' => 'btnEdit',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#updateUserModal',
                                'data-id' => $user->user_id,
                                'data-username' => $user->username,
                                'data-email' => $user->email,
                                'data-user_type' => $user->user_type,
                                'data-registration_number' => $user->registration_number,
                                'data-phone_number' => $user->phone_number,
                            ])}}
                        </div>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                'class' => 'btn btn-danger btn-sm',
                                'id' => 'deleteBtn',
                                'data-id' => $user->user_id,
                                'data-username' => $user->username,
                                'type' => 'button'
                            ])}}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="text-center">
        {{ $users->links() }}
    </div>

    @include('user.updateModal', ['info' => $info])
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", "#deleteBtn", function(e) {
            e.preventDefault();
            let userId = $(this).data('id');
            let username = $(this).data('username');
            let row = $(this).closest("tr");
                
            if (confirm("Are you sure you want to delete '" + username + "' ?")) {
                $.ajax({
                    url: `/users/${userId}`,
                    type: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('.userIndex').load(location.href + ' .userIndex')
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert("Error deleting user. Please try again.");
                        $('.userIndex').load(location.href + ' .userIndex')
                    }
                });
            } else {
                console.log("Cenceled deleting '"+ username +"'");
            }
        });
        $(document).on('click', '#userStatusBtn', function(e) {
            e.preventDefault();
            let userId = $(this).data('id');
            let status = $("#userStatusBtn").text().trim();
            console.log(userId, status);
            $.ajax({
                url : `/users/status/${userId}`,
                type : 'get',
                data : {id : userId},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $('.userIndex').load(location.href + ' .userIndex')
                        $('#userTable').load(location.href + ' #userTable')
                        if (status === 'Active') {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "User Inactivated successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "User Activated successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Error changing status. Please try again.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error :function (err)
                {
                    if (err.status === 'error') {
                        console.log(err.status);
                    }
                }
            });
        });
        $(document).on("click", "#createUser", function(e) {
            e.preventDefault();
            $("#createForm").load('slideCreate.blade.php', function() {
                $(this).slideToggle(500);
            });
        });
    })
</script>

@endsection