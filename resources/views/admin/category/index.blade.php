@extends('layouts.app')

@section('content')

	<div class="container">
    	<h1 class="text-center">All Category Details</h1>
    	<a class="btn btn-outline-primary font-weight-bold" href="{{ route('category.create')}}">
    	Add New
    	<span>
    		<i class=" fa fa-plus"></i>
    	</span>
    	</a>
  		<hr />
    	<table class="table table-bordered data-table">
        	<thead>
            	<tr>
                	<th>Id</th>
                	<th>Name</th>
                	<th>Icon</th>
                	<th width="130px">Action</th>
            	</tr>
        	</thead>
        	<tbody>
        		@foreach($categories as $category)
	        			<tr>
    						<td>{{ $category->id }}</td>
	        				<td>{{ $category->name }}</td>
	        				<td>
	        					<img src="{{ url('images/',$category->icon) }}" width="60" height="50">
	        				</td>
    					@if (\Auth::user()->role_id == 2)
    						@if(Auth::user()->id == $category->user_id)
	        				<td>
	        					<a href="{{ route('category.edit', $category->id)}}" class="btn btn-warning font-weight-bold">Edit</a>
	        					<a href="{{ route('category.destroy', $category->id)}}" class="btn btn-danger font-weight-bold">Delete</a>
	        				</td>
	        				@endif
    					@endif
    				@if (\Auth::user()->role_id == 1)
	    				
	 	       				<td>
	        					<a href="{{ route('category.edit', $category->id)}}" class="btn btn-warning font-weight-bold btn-sm">Edit</a>
	        					<a href="{{ route('category.destroy', $category->id)}}" class="btn btn-danger font-weight-bold btn-sm">Delete</a>
	        				</td>
	        			</tr>
        			@endif
        		@endforeach
        	</tbody>
    	</table>
	</div>
@endsection