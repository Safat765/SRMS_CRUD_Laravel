@extends('layout.main')
@push("title")
<title>User View</title>
@section('main')
<div class="table-responsive">
    <table class="table table-dark table-bordered table-hover" style="font-size: 16px;">
        <thead>
            <tr>
                <th scope="col" style="width: 50%;">Student Name</th>
                <td scope="col" style="width: 50%;">{{ $result['name'] }}</td>
            </tr>
            <tr>
                <th scope="col" style="width: 40%;">Registration Number</th>
                <td scope="col" style="width: 40%;">{{ Session::get('registration_number') }}</td>
            </tr>
            <tr>
                <th scope="col" style="width: 50%;">Session</th>
                <td scope="col" style="width: 50%;">{{ $result['session'] }}</td>
            </tr>
            <tr>
                <th scope="col" style="width: 50%;">Total Credit</th>
                <td scope="col" style="width: 50%;">{{ $result['credit'] }}</td>
            </tr>
            <tr>
                <th scope="col" style="width: 50%;">CGPA</th>
                <td scope="col" style="width: 50%;">{{ $result['CGPA'] }}</td>
            </tr>
        </thead>
    </table>
    <hr>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Semester</th>
                <th scope="col">Credits</th>
                <th scope="col">CGPA</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semesters as $semester)
                <tr>
                    <td>{{ $semester->semester_name }}</td>
                    <td>{{ $totalCredits[$semester->semester_id] }}</td>
                    <td>{{ $GPA[$semester->semester_id] }}</td>
                    <td>
                        <div class="text-center">
                            {{ Form::button(HTML::decode('<i class="las la-eye"></i>'), [
                                'class' => 'btn btn-info btn-sm',
                                'id' => 'courseWiseResult',
                                'data-id' => $semester->semester_id,
                                'data-name' => $semester->semester_name,
                                'data-studentid' => Session::get('user_id'),
                                'type' => 'submit'
                            ])}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('Result.semesterWise', ['result' => $result])
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#courseWiseResult', function(e) {
            e.preventDefault();
            let semesterId = $(this).data('id');
            let studentId = $(this).data('studentid');
            let semesterName = $(this).data('name');
            const $tbody = $(this).find('tbody');
            console.log(semesterId, studentId);

            $.ajax({
                url: `/results/semester/${semesterId}`,
                type: 'GET',
                data: {semesterId : semesterId, studentId : studentId},
                success: function(response) {
                    $('#samName').val(semesterName);
                    console.log(response.status)
                    const records = response.records;
                    const $tbody = $('#modalTableBody');                    
                    $tbody.empty();
                    let text = $('#updateModalLabel').text();
                    let newText = text + semesterName +'"';
                    $('#updateModalLabel').text(newText);

                    records.forEach(record => {
                        $tbody.append(`
                            <tr>
                                <td>${record.course_name}</td>
                                <td>${record.marks}</td>
                                <td>${record.gpa}</td>
                            </tr>
                        `);
                    });
                    $('#semesterWiseCourseModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.status, error);
                }
            });
        });
    });
</script>