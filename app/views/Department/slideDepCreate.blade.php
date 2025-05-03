<div class="container mt-5 userForm" id="departmentForm">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center fw-bold text-info">Create User</h4>
                </div>
                
                <div class="errorMsgContainer p-2 text-center"></div>
                <div class="card-body bg-light">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                {{ Form::label('name', 'Department Name', ['class' => 'form-label']) }}
                                <span style="color: red; font-weight: bold;"> *</span>
                                {{ Form::text('name', null, 
                                    [
                                    'class' => 'form-control shadow-lg name',
                                    'placeholder' => 'Enter Department Name',
                                    'required' => true
                                    ]
                                )}}
                                <br>
                            </div>
                            <div class="col-md-4" style="padding-top: 32px;">    
                                <div class="col-md-12 text-center">
                                    {{ Form::submit('Create', 
                                        [
                                        'class' => 'btn btn-primary btn-block w-50 addDepartment'
                                        ]
                                    )}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><hr><br>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $(document).on('click', '.addDepartment', function(e) {
            e.preventDefault();
            let name = $('.name').val();

            if (!name) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fillup the form first!"
                });
                return;
            }
            $('.errorMsgContainer').html("");

            $.ajax({
                url : "{{ URL::route('departments.store') }}",
                type : 'post',
                data : {name : name},
                success : function (response) {
                    if (response.status === 'success') {
                        $("#departmentForm").modal('hide');
                        $("#departmentForm").trigger("reset");
                        $('.departmentUpdate').load(location.href + ' .departmentUpdate')
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: `${name} Department added successfully`,
                            showConfirmButton: false,
                            timer: 1500
                        });
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
            $("#departmentForm").trigger("reset");
            $('.errorMsgContainer').text("");
        });
    });
</script>