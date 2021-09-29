@extends('layouts.app')

@section('content')

	<div class="container">
    	<h1 class="text-center">All Product Details</h1>
    	<a class="btn btn-outline-success font-weight-bold" href="javascript:void(0)" id="createNewProduct">
    	Add New
    	<span>
    		<i class=" fa fa-plus"></i>
    	</span>
    	</a>
  		<hr />
    	<table class="table table-bordered data-table" id="myTable">
        	<thead>
            	<tr>
                	<th>Id</th>
                	<th>Name</th>
                	<th>Description</th>
                	<th>Price</th>
                	<th>Image</th>
                	<th width="160px">Action</th>
            	</tr>
        	</thead>
        	<tbody>
        	</tbody>
    	</table>
	</div>
	<div class="modal fade" id="ajaxModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalHeading"></h4>
				</div>
				<div class="modal-body">
					<form id="productForm" name="productForm" class="" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="product_id" id="product_id" value="">
						<div class="form-group">
							Name : <br/>
							<input type="text" class="form-control" id="name" name="name" placeholder="Product Name" value="">
                            <div class="required-name text-danger">
                                
                            </div>
						</div>
						<div class="form-group">
							Description : <br/>
							<textarea type="text" class="form-control" id="description" name="description" placeholder="Product Description" value=""></textarea>
                            <div class="required-description text-danger">
                                
                            </div>
						</div>
						<div class="form-group">
							Price : <br/>
							<input type="text" class="form-control" id="price" name="price" placeholder="Product Price" value="">
                            <div class="required-price text-danger">
                                
                            </div>
						</div>
						<div class="form-group">
							Image : <br/>
							<input type="file" class="form-control" id="image" name="image" placeholder="Select Image" value="">
                            <img name="image_id" id="image_id" width="50" height="50">
						</div>
						<button type="submit" class="btn btn-primary" id="saveBtn" value="create">
							Submit
						</button>
					</form>
				</div>
			</div>
		</div>	
	</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready( function () {
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 5,
            // scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: '{{ route("product.index") }}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'price', name: 'price'},
                {data: 'image', "render":function(data, type, row)
                    {
                        return '<img src="public/images/'+data+'" width="60px" height="50px" />';
                    }
                },
                {data: 'action', name: 'action',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });

        $('#createNewProduct').click(function(){
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#modalHeading').html("Add New Product");
            $('#ajaxModal').modal('show');
            $("#image_id").css("display", "none");
        });

        $('#productForm').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('product.store')}}",
                method:"POST",
                data: new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    $('#productForm').trigger("reset");
                    $('#ajaxModal').modal('hide');
                    table.draw();
                },
                error:function(data){
                    if($('#name').val() == ''){
                        $('.required-name').html('<span class="el-error-msg">Product Name can not be blank...</span>');
                    }
                    else if($('#description').val() == ''){
                        $('.required-description').html('<span class="el-error-msg">Description field can not be blank...</span>');
                    }
                    else if($('#price').val() == ''){
                        $('.required-price').html('<span class="el-error-msg">Price field can not be blank...</span>');
                    }
                    else{
                      $('.required-name').text('');
                      $('.required-description').text('');
                      $('.required-price').text('');
                    }
                }
            });
        });

        $('body').on('click', '.deleteProducts', function(){
            var product_id = $(this).data("id");
            confirm("Are you sure want to delete! ");
            $.ajax({
                type:"DELETE",
                url: "{{ route('product.index')}}"+'/'+product_id,
                success: function(data){
                    table.draw();
                    // table.ajax.reload();
                },
                error: function(data){
                    console.log('Error', data);
                }
            });
        });

        $('body').on('click', '.editProducts', function(){

            var product_id = $(this).data("id");

            $.get("{{ route('product.index')}}"+"/"+product_id+"/edit", function(data){
                $("#modalHeading").html("Edit Product");
                $("#ajaxModal").modal('show');
                $("#product_id").val(data.id);
                $("#name").val(data.name);
                $("#description").val(data.description);
                $("#price").val(data.price);
                // $("#old").val(data.image);
                $("#image_id").attr("src","{{ url('public/images')}}"+"/"+data.image);
                $("#image").attr("value",data.image);
            });
        });
    });
</script>


@endpush