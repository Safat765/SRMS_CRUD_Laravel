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
                <th scope="col">courses</th>
                <th scope="col">GPA</th>
                <th scope="col">CGPA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->semester_name }}</td>
                    <td>{{ $record->course_name }}</td>
                    <td>{{ $record->gpa }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $result['CGPA'] }}</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection