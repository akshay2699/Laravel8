@extends('layouts.app')

@section('content')
<div class="container">
    <h1 align="center">Company List</h1>
    
    <a href="javascript:void(0)" id="createNewCompany" class="btn btn-outline-secondary font-weight-bold">
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
                <th>Name</th>
                <th>Email</th>
                <th>Logo</th>
                <th>Website</th>
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
                    <form method="POST" class="row g-3 needs-validation" novalidate enctype="multipart/form-data" id="companyForm">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <input type="hidden" name="company_id" id="company_id" value="">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="" placeholder="Enter Company Name" required>
                            <div class="valid-feedback">
                              
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email Name" value="" required>
                            <div class="valid-feedback">
                              
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" id="logo" value="" required>
                            <img name="logo_id" id="logo_id" width="50" height="50">
                            <div class="valid-feedback">
                              
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" name="website" class="form-control" id="website" placeholder="Enter Website Link" value="" required>
                            <div class="valid-feedback">
                              
                            </div>
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
            ajax: "{{ route('company.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'logo', "render":function(data, type, row)
                    {
                        return '<img src="images/'+data+'" width="60px" height="50px" />';
                    }
                },
                {data: 'website', name: 'website'},
                {data: 'actions', name: 'actions', orderable: true, searchable: true},
            ]
        });

        // Ajax Create Model
        $('#createNewCompany').click(function(){
            $('#company_id' ).val('');
            $('#companyForm').trigger("reset");
            $('#modalHeading').html("Add New Company");
            $('#ajaxModal').modal('show');
            $("#logo_id").css("display", "none");
        });

    // While clicking on submit button
         $('#companyForm').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('company.store')}}",
                method:"POST",
                data: new FormData(this),
                dataType:'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){ 
                    if($.isEmptyObject(data.error)){
                        // alert(data.success);
                        $('#companyForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
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
        $('body').on('click', '.editCompany', function(){
            var company_id  = $(this).data("id");

            $.get("{{ route('company.index')}}"+"/"+company_id+ "/edit", function(data){
                console.log(data);
                $("#modalHeading").html("Edit Company Details");
                $("#ajaxModal").modal('show');
                $("#company_id" ).val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#website").val(data.website);
                $("#logo_id").attr("src","{{ url('/images')}}"+"/"+data.logo);
                $("#logo").attr("value",data.logo);
            });
        });


    // Delete record by ajax from database
        $('body').on('click', '.deleteCompany', function(){
            var company_id  = $(this).data("id");
            confirm("Are you sure want to delete! ");
            $.ajax({
                type:"DELETE",
                url: "{{ route('company.index')}}"+'/'+company_id, 
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