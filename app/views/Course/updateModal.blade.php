<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    {{ Form::open(['url' => '/courses', 'method' => 'post', 'novalidate' => true, 'id' => 'courseUpdate']) }}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Course</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errorMsgContainer">

                    </div>
                    <div class="row mb-3">
                            <div class="col-md-6">
                                {{ Form::hidden('courseId', null, ['class' => 'courseId', 'id' => 'courseId']) }}
                                {{ Form::label('name', 'Course name', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('name', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'id' => 'name1',
                                    'placeholder' => 'Enter Course name',
                                    'required' => true
                                    ]
                                )}}
                            </div>                        
                            <div class="col-md-6">
                                {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}<span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('credit', null, 
                                    [
                                    'class' => 'form-control shadow-lg credit',
                                    'id' => 'credit1',
                                    'placeholder' => 'Enter Credit',
                                    'required' => true
                                    ]
                                )}}
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary updateCourse">Update</button>
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
        $(document).on('click', '.btnEdit', function() {
            let courseId = $(this).data('id');
            let name1 = $(this).data('name');
            let credit1 = $(this).data('credit');

            $('.courseId').val(courseId);
            $('.name').val(name1);
            $('.credit').val(credit1);

            console.log(courseId, name1, credit1);
        });

        $(document).on('click', '.updateCourse', function(e) {
            e.preventDefault();
            let id = $('#courseId').val();
            let name1 = $('#name1').val();
            let credit1 = $('#credit1').val();

            console.log(id, name1, credit1);
            $.ajax({
                url : `/courses/${id}`,
                type : 'PUT',
                data : {id : id ,name : name1, credit : credit1},
                success : function (response)
                {
                    if (response.status === 'success') {
                        $("#updateModal").modal('hide');
                        $("#courseUpdate").trigger("reset");
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