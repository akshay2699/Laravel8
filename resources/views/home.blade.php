@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <div>
                    @if (\Auth::user()->role_id == 1)
                        <center><h1>Welcome Admin</h1></center><br>
                        <a class="btn btn-secondary bg-dark ml-2 mb-2" href="{{ route('category.index') }}">    
                            Category Details
                        </a>
                        <a class="btn btn-secondary bg-dark ml-2 mb-2" href="{{ route('product.index') }}">   
                            Product Details
                        </a>
                    @endif
                    @if (\Auth::user()->role_id == 2)
                        <center><h1>Welcome User</h1></center><br>
                        <a class="btn btn-secondary bg-dark ml-2 mb-2" href="{{ route('category.index') }}">
                            Category Details
                        </a>
                        <a class="btn btn-secondary bg-dark ml-2 mb-2" href="{{ route('product.index') }}">   
                            Product Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
