@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h1>create cars</h1>
        </div>
    </div>
    
    <div>   
        {{-- {{dd($cars)}} --}}
        <form action="/cars" method="POST">
            @csrf
            <div>
                <input type="text" name="name" placeholder="Brand Name">
            
                <input type="text" name="founded" placeholder="Founded">

                <input type="text" name="description" placeholder="description">
            <button> Submit</button>
            </div>
        </form>

    </div>

    
@endsection