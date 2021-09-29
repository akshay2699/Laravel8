@extends('layouts.app')

@section('content')
	<div class="alert" id="message" style="display: none;"></div>
	<div class="container">
    	<h1 class="text-center">All Category Details</h1>
    	<a class="btn btn-outline-success font-weight-bold" href="javascript:void(0)" id="createNewCategory">
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
                	<th>Icon</th>
                	<th width="130px">Action</th>
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
					<form id="categoryForm" name="categoryForm" class="" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="category_id" id="category_id" value="">
						<div class="form-group">
							Name : <br/>
							<input type="text" class="form-control" id="name" name="name" placeholder="Category Name" value="" required>
                            <div class="required-name text-danger">
                                
                            </div>
						</div>
						<div class="form-group">
							Icon : <br/>
							<input type="file" class="form-control" id="icon" name="icon" placeholder="Category Icon" value="" >
                            <div class="required-icon text-danger">
                                
                            </div>
                            <img name="icon_id" id="icon_id" width="50" height="50">
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
            ajax: '{{ route("category.index") }}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'icon', "render":function(data, type, row)
                    {
                        return '<img src="public/images/'+data+'" width="60px" height="50px" />';
                    }
                },
                {data: 'action', name: 'action',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
        //Add categories by ajax
        $('#createNewCategory').click(function(){
            $('#category_id').val('');
            $('#categoryForm').trigger("reset");
            $('#modalHeading').html("Add New Category");
            $('#ajaxModal').modal('show');
            $("#icon_id").css("display", "none");
        });
        $('#categoryForm').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('category.store')}}",
                method:"POST",
                data: new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    $('#categoryForm').trigger("reset");
                    $('#ajaxModal').modal('hide');
                    table.draw();
                },
                error:function(data){
                    if($('#name').val() == '' || $('#name').val() != ''){
                        $('.required-name').html('<span class="el-error-msg">Category Name field can not be blank...</span>');
                    }
                    else{
                      $('.required-name').text('');
                    }
                }
            });
        });
        $('body').on('click', '.deleteCategories', function(){
            var category_id = $(this).data("id");
            confirm("Are you sure want to delete! ");
            $.ajax({
                type:"DELETE",
                url: "{{ route('category.index')}}"+'/'+category_id,
                success: function(data){
                    table.draw();
                    // table.ajax.reload();
                },
                error: function(data){
                    console.log('Error', data);
                }
            });
        });

        $('body').on('click', '.editCategories', function(){
            var category_id = $(this).data("id");
            $.get("{{ route('category.index')}}"+"/"+category_id+"/edit", function(data){
                $("#modalHeading").html("Edit Category");
                $("#ajaxModal").modal('show');
                $("#category_id").val(data.id);
                $("#name").val(data.name);

                $("#icon_id").attr("src","{{ url('public/images')}}"+"/"+data.icon);
                $("#icon").attr("value",data.icon);
            });
        });
       
    });
</script>
@endpush