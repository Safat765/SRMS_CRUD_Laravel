@extends('layout.main')
@push("title")
<title>Students Result</title>
@section('main')
<div class="table-responsive pt-5" id="marksIndex">
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <td scope="row" colspan="5" class="fw-bold fs-3 text-info">Students Result ({{ $totalStudents }})</td>
            </tr>
            <tr>
                <th scope="col">Student Name</th>
                <th scope="col">Registration Number</th>
                <th scope="col">Session</th>
                <th scope="col">CGPA</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($getResults as $semesterName => $students)
            <tr>
                <th colspan="5" class="bg-light text-warning fs-5 p-2 text-center">{{ $semesterName }}</th>
            </tr>
            @foreach ($students as $result)
                <tr>
                    <td class="p-2">{{ $result->username }}</td>
                    <td class="p-2">{{ $result->registration_number }}</td>
                    <td class="p-2">{{ $result->session }}</td>
                    <td class="p-2">{{ $result->cgpa }}</td>
                    <td class="p-3">
                        <div class="d-flex justify-content-center gap-2" style="display: inline-block;">
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                    'class' => 'btn btn-info btn-sm btnEdit',
                                    'id' => 'resultBtnView',
                                    'data-id' => $result->user_id,
                                    'data-name' => ucwords($result->username),
                                ])}}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
@include('user.semesterWise')
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#resultBtnView', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            var name = $(this).data('name');

            $.ajax({
                url: `/admin/results/semester/${userId}`,
                type: 'GET',
                success: function(response) {
                    const records = response.records;
                    const $tbody = $('#resultModalTableBody');
                    $tbody.empty();
                    $('#updateModalLabel').text('Result of -- ' + name);

                    $.each(records, function(semesterName, students) {
                        $tbody.append(`
                            <tr>
                                <th colspan="3" class="bg-light text-warning fs-5 p-2 text-center">${semesterName}</th>
                            </tr>
                        `);
                        
                        $.each(students, function(index, result) {
                            $tbody.append(`
                                <tr>
                                    <td>${result.course_name}</td>
                                    <td>${result.marks}</td>
                                    <td>${result.gpa}</td>
                                </tr>
                            `);
                        });
                    });
                    $('#resultUserModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error loading results:', xhr.responseText);
                }
            });
        });
    });
</script>