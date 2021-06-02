@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            Private page, only admin can access this page.
            <br> <a href="{{ url('/home') }}">Back</a>
        </div>  
    </div>
</div>
@endsection