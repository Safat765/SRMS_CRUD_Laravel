@extends('layout.main')
@push("title")
<title>Course List</title>
@section('main')
<div class="table-responsive pt-5">
        <div class="bg-warning  text-black text-center mx-5">
        </div>
        <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
            <thead>
                <tr>
                    <td scope="row" colspan="4" class="fw-bold fs-3 text-info">View Marks according to Course</td>
                </tr>
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">Students</th>
                    <th scope="col">Marks</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                        <tr>
                            <td scope="row">{{$result->course_name}}</td>
                            <td scope="row">{{$result->username}}</td>
                            <td scope="row">{{$result->marks}}</td>
                            <td scope="row">
                                @if (isset($result->username) && isset($result->marks))

                                @else

                                @endif
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
</div>

@endsection