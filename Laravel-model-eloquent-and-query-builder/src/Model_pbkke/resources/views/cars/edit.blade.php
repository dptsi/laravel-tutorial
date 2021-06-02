@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h1>edit cars</h1>
        </div>
    </div>
    
    <div>   
        {{-- {{dd($cars)}} --}}
        <form action="/cars/{{ $car->id }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <input type="text" name="name" placeholder="Brand Name" value="{{$car->name}}">
            
                <input type="text" name="founded" placeholder="Founded" value="{{$car->founded}}">

                <input type="text" name="description" placeholder="description" value="{{$car->description}}">
            <button> Submit</button>
            </div>
        </form>

    </div>

    
@endsection