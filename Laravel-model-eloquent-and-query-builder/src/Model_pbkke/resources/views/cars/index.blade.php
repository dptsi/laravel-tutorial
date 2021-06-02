@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h1>cars</h1>
        </div>
    </div>
        
    <a href="cars/create" class="btn btn-primary">create car</a>

    <hr>
        {{dd($cars)}}
    @foreach ($cars as $car)    
    
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <span> Founded: 
                    {{-- {{$car->founded}} --}}
                </span>
                <h5 class="card-title">
                     
                    <a href="/cars/{{$car->id}}">
                        {{$car->name}}
                        {{-- {{$car['name']}} --}}
                    </a>
                      
                </h5>
                <p class="card-text">
                    {{-- {{$car->description}} --}}
                </p>
            <a href=
            "cars/{{ $car->id }}/edit" 
            class="btn btn-outline-primary">edit</a>
            
            <form action="
            {{-- /cars/{{$car->id}} --}}
            " method="POST">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger">
                    delete
                </button>
            </form>
                
        
        </div>
        </div>

    @endforeach
    

    
    
@endsection