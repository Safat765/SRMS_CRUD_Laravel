@extends('layout.main')
@push("title")
<title>Exam View</title>
@section('main')
<div class="table-responsive">
    <!-- <div class="form-group d-flex justify-content-between align-items-start"> -->
        <div class="d-flex">
            <div class="p-1">            
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/exams/create')}}" class="btn btn-primary m-2">
                        Create exam
                    </a>
                </div>
            </div>
            <div class="p-1">                       
                <div class="d-flex justify-content-start mb-3">
                    <a href="{{url('/exams')}}" class="btn btn-secondary m-2">
                        Reset
                    </a>
                </div>
            </div>
        </div>
        
        <!-- <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
            {{ Form::open([URL::route('exams.index'), 'method' => 'get']) }}
            <div class="form-group d-flex">
                <div class="form-group p-1 col-10">
                    {{ Form::text('search', $search, [
                    'class' => 'form-control',
                    'placeholder' => 'Search by Exam Title or credit',
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
    <div class="bg-warning  text-black text-center mx-5">
        <h5>Total Exam : {{ $totalExams }}</h5>
    </div>
    <table class="table table-striped table-bordered table-hover text-center" style="font-size: 15px;">
        <thead>
            <tr>
                <th scope="col">Course</th>
                <th scope="col">Exam Title</th>                
                <th scope="col">Department</th>
                <th scope="col">Semester</th>
                <th scope="col">Credit</th>
                <th scope="col">Exam Type</th>
                <th scope="col">Marks</th>
                <th scope="col">Assigned Instructor</th>
                <th scope="col">Action</th>
            </tr>
        </thead> -->
        <!-- <tbody>
            @foreach ($exams as $exam)
            <tr>
                <td scope="row">{{$exam->course_id}}</td>
                <td scope="row">{{$exam->exam_title}}</td>
                <td>
                    <div class="text-center">
                        <div>
                            {{ Form::open(['url' => 'exams/' .$exam->exam_id.'/edit', 'method' => 'get']) }}    
                                <div class="text-center">
                                    {{ Form::button(HTML::decode('<i class="las la-edit"></i>'), [
                                        'class' => 'btn btn-success btn-sm',
                                        'type' => 'submit'
                                    ])}}
                                </div>
                            {{ Form::close() }}
                        </div>
                        <div>
                            {{ Form::open(['url' => 'exams/' .$exam->exam_id, 'method' => 'delete']) }}                            
                            <div class="text-center">
                                {{ Form::button(HTML::decode('<i class="las la-trash-alt"></i>'), [
                                    'class' => 'btn btn-danger btn-sm',
                                    'type' => 'submit',
                                    'disabled' => 'disabled'
                                ])}}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody> -->
    <!-- </table> -->
    
    <!-- <div class="text-center">
        {{ $exams->links() }}
    </div> -->
</div>

@endsection