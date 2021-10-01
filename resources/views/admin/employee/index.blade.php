@extends('layouts.app')

@section('content')
<div class="container">
    <h1 align="center">Employee List</h1>
    
    <a href="javascript:void(0)" id="createNewEmployee" class="btn btn-outline-secondary font-weight-bold">
        Add New 
        <span>
            <i class="fa fa-plus"></i>
        </span>
    </a>
    <hr>
    <table class="table table-bordered data-table">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Companies</th>
                <th width="180px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Bootstrap Model Create Form Start -->
    <div class="modal fade" id="ajaxModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3 needs-validation" novalidate enctype="multipart/form-data" id="employeeForm">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        
                        <input type="hidden" name="employee_id" id="employee_id" value="">
                        
                        <div class="col-md-12">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" name="fname" class="form-control" id="fname" value="" placeholder="Enter First Name" required>
                            
                        </div>
                        <div class="col-md-12">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" name="lname" class="form-control" id="lname" value="" placeholder="Enter Last Name" required>
                        </div>
                       
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email Address" value="" required>
                        </div>

                        <div class="col-md-12">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone Number" value="" required>
                        </div>                       

                        <div class="col-md-12">
                            <label for="company" class="form-label">Companies</label>
                            <select name="company" class="form-control" id="company" placeholder="Enter Company Name" value="" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id}}"

                                        >{{ $company->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
<!-- Bootstrap Model Create Form End -->
@endsection

@push('scripts')
<script type="text/javascript">
    // Example starter JavaScript for disabling form submissions if there are invalid fields

  // Fetch all the forms we want to apply custom Bootstrap validation styles to

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employee.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'fname', name: 'fname'},
                {data: 'lname', name: 'lname'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'company_id', name: 'company_id'},
                {data: 'actions', name: 'actions', orderable: true, searchable: true},
            ]
        });

        // Ajax Create Model
        $('#createNewEmployee').click(function(){
            $('#employee_id' ).val('');
            $('#employeeForm').trigger("reset");
            $('#modalHeading').html("Add New Employee");
            $('#ajaxModal').modal('show');
            $('.print-error-msg').css('display', 'none');
        });

         $('#employeeForm').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('employee.store')}}",
                method:"POST",
                data: new FormData(this),
                dataType:'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){ 
                    if($.isEmptyObject(data.error)){
                        // alert(data.success);
                        $('#employeeForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        $('.print-error-msg').trigger('reset');
                        table.draw();
                    }else{
                        printErrorMsg(data.error);
                    }
                },
            });
        });
        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        } 
        // Edit record by ajax to database
        $('body').on('click', '.editEmployee', function(){
            var employee_id  = $(this).data("id");

            $.get("{{ route('employee.index')}}"+"/"+employee_id+ "/edit", function(data){
                $(".print-error-msg").css("display","none");
                $("#modalHeading").html("Edit Employee Details");
                $("#ajaxModal").modal('show');
                $("#employee_id" ).val(data.id);
                $("#fname").val(data.fname);
                $("#lname").val(data.lname);
                $("#email").val(data.email);
                $("#phone").val(data.phone);
                $("#company").val(data.company_id);
            });
        });


        // Delete record by ajax from database
        $('body').on('click', '.deleteEmployee', function(){
            var employee_id  = $(this).data("id");
            confirm("Are you sure want to delete! ");
            $.ajax({
                type:"DELETE",
                url: "{{ route('employee.index')}}"+'/'+employee_id, 
                success: function(data){
                    table.draw();
                    // table.ajax.reload();
                },
                error: function(data){
                    console.log('Error', data);
                }
            });
        });

    });
</script>
@endpush