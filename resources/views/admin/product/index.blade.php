@extends('layouts.app')

@section('content')

	<div class="container">
    	<h1 class="text-center">All Product Details</h1>
    	<a class="btn btn-outline-primary font-weight-bold" href="{{ route('product.create')}}">
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
                	<th>Description</th>
                	<th>Price</th>
                	<th>Image</th>
                	<th width="160px">Action</th>
            	</tr>
        	</thead>
        	<tbody>
        		@foreach($products as $product)
        			<tr>
        				<td>{{ $product->id }}</td>
        				<td>{{ $product->name }}</td>
        				<td>{{ $product->description }}</td>
        				<td>{{ $product->price }}</td>
        				<td>
        					<img src="{{ url('images/',$product->image) }}" width="60" height="50">
        				</td>
        				@if (Auth::user()->role_id == 2)
	        				@if(Auth::user()->id == $product->user_id)
	        				<td>
	        					<a href="{{ route('product.edit', $product->id)}}" class="btn btn-warning font-weight-bold">Edit</a>
	        					<a href="{{ route('product.destroy', $product->id)}}" class="btn btn-danger font-weight-bold">Delete</a>
	        				</td>
        					@endif
        				
        				@else (Auth::user()->role_id == 1)
        				<td>
        					<a href="{{ route('product.edit', $product->id)}}" class="btn btn-warning font-weight-bold">Edit</a>
        					<a href="{{ route('product.destroy', $product->id)}}" class="btn btn-danger font-weight-bold">Delete</a>
        				</td>
        				@endif
        			</tr>
        		@endforeach
        	</tbody>
    	</table>
	</div>
@endsection