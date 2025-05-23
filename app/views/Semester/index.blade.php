@extends('layout.main')
@push("title")
<title>Semester View</title>
@section('main')
<div class="table-responsive semesterUpdate" id="semesterIndex">
    <div class="form-group d-flex justify-content-between align-items-start">
        <div class="d-flex">
            <div class="p-1">
                <div class="d-flex justify-content-start mt-2 mb-3">
                    <button class="btn btn-success" id="createSemester">Create Semester</button>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/admin/semesters')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>  
        </div>
        <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('admin.semesters.index'), 'method' => 'GET']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Semester name',
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
        @include('semester.slideCreate')
    </div>
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Semester : {{ $totalSemester }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Semester Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semester as $semesters)
            <tr>
                <td scope="row" class="p-3">{{ $semesters->name }}</td>
                <td class="d-flex justify-content-center gap-2 p-3">
                    <div class="d-flex gap-2" style="display: inline-block;">
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                'class' => 'btn btn-success btn-sm btnEdit',
                                'type' => 'submit',
                                'id' => 'btnEdit',
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => '#updateSemesterModal',
                                'data-id' => $semesters->semester_id,
                                'data-name' => $semesters->name
                            ])}}
                        </div>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                'class' => 'btn btn-danger btn-sm',
                                'id' => 'semesterDeleteBtn',
                                'data-id' => $semesters->semester_id,
                                'data-name' => $semesters->name,
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
        {{ $semester->links() }}
    </div>
    @include('semester.updateModal')
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", "#semesterDeleteBtn", function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            if (confirm("Are you sure you want to delete '" + name + "' ?")) {
                $.ajax({
                    url: `/admin/semesters/${id}`,
                    type: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#semesterIndex').load(location.href + ' #semesterIndex');
                            Swal.fire({
                                icon: "success",
                                title: "success",
                                text: `'${name}' Semester deleted successfully`
                            });
                        }
                    },
                    error: function (xhr) {
                        alert("Error deleting user. Please try again.");
                        $('#semesterIndex').load(location.href + ' #semesterIndex');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: xhr.responseJSON.message
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Cenceled deleting '" + name + "'."
                });
            }
        });
        $(document).on("click", "#createSemester", function(e) {
            e.preventDefault();
            $("#createForm").load('slideCreate', function() {
                $(this).slideToggle(500);
            });
        });
    })
</script>