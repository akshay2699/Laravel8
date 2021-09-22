@extends('layouts.app')

@section('content')

	<div class="container">
    	<h1 class="text-center">All Product Details</h1>
    	<a class="btn btn-outline-primary font-weight-bold" href="{{ route('product.index')}}">
    	Go back
    	<span>
    		<i class=" fa fa-plus"></i>
    	</span>
    	</a>
  		<hr />
    	<table class="table table-bordered data-table">
        	<thead>
        		@foreach($products as $product)
            	<tr>
                	<th>Id</th>
                	<td>{{ $product->id }}</td>
                	
                </tr>
                <tr>
                	<th>Product Name</th>
                	<td>{{ $product->name }}</td>
                </tr>
                <tr>
                	<th>Description</th>
                	<td>{{ $product->description }}</td>
                </tr>
                <tr>
                	<th>Price</th>
                	<td>{{ $product->price }}</td>
                </tr>
                <tr>
                	<th>Image</th>
                	<td>
						<img src="{{ url('images/',$product->image) }}" width="60" height="50">
					</td>
                </tr>
                @endforeach
        	</thead>
    	</table>
	</div>
@endsection