@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $msg }}
            <br> <a href="{{ url('/post') }}">Back</a>
        </div>
    </div>
</div>
@endsection