<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    {{ Form::open(['url' => '/courses', 'method' => 'post', 'novalidate' => true, 'id' => 'courseCreate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Course</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                            <div class="col-md-6">
                                {{ Form::label('name', 'Course name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('name', Input::old('name'), 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter Course name',
                                    'required' => true
                                    ]
                                )}}
                                @if($errors->has('name'))
                                <span class="text-danger small d-block mt-1">
                                    {{ $errors->first('name') }}
                                </span>
                                @endif
                            </div>                        
                            <div class="col-md-6">
                                {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('credit', Input::old('credit'), 
                                    [
                                    'class' => 'form-control shadow-lg credit',
                                    'placeholder' => 'Enter Credit',
                                    'required' => true
                                    ]
                                )}}
                                @if($errors->has('email'))
                                <span class="text-danger small d-block mt-1">
                                    {{ $errors->first('credit') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary addCourse">Create</button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.addCourse', function(e) {
            e.preventDefault();
            let name = $('.name').val();
            let credit = $('.credit').val();

            console.log(name, credit);
            $.ajax({
                url : "{{ URL::route('courses.store') }}",
                type : 'post',
                data : {name : name, credit : credit},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#exampleModal").modal('hide');
                        $("#courseCreate").trigger("reset");
                        $('.courseIndex').load(location.href + ' .courseIndex')
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
            $("#exampleModal").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>