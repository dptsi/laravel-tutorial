@extends('layouts.app')

@section('content')
    <div>
        <div>
            <h1>{{$car->name}}</h1>
        </div>

        {{-- {{ dd($car->productionDate) }} --}}
        
        @if ( ($car->headquarter)!=null ){
            {{-- {{dd($car->headquarter);}} --}}
            {{ $car->headquarter->headquarters }} , {{ $car->headquarter->country }}1
        }
            
        @else
           dont have headquarter
        @endif

        {{-- {{ $car->headquarter->headquarters }} , {{ $car->headquarter->country }} --}}

    </div>
    <span> Founded: 
        {{$car->founded}}
    </span>
    <p>{{$car->description}}</p>

    <hr>

    
    <table class="table">
        <tr>
            <th>
                model
            </th>
            <th>
                engines
            </th>
            <th>
                date
            </th>
        </tr>

      
      
        {{-- {{dd($car->productionDate)}}; --}}
        {{-- {{dd($car->carModel)}};   --}}
        {{-- @foreach ($car->productionDate as $date) --}}
            {{-- <p> --}}
                {{-- {{ dd($date->created_at)}} --}}
                {{-- {{ date('d-m-Y',strtotime($date->created_at)) }} --}}
            {{-- </p> --}}
        {{-- @endforeach --}}


        @forelse ($car->carModel as $model)
            <tr>
                <td>
                    {{ $model->model_name }}
                </td>
                <td>
                    @foreach ($car->engine as $engine)
                        {{-- {{ $engine }}
                        {{ dd($engine) }} --}}
                        @if ($model->id == $engine->model_id){
                            {{ $engine->engine_name }}    
                        }
                        @endif
                    @endforeach
                </td>
                <td>
                    {{-- {{dd($car->productionDate)}}; --}}
                    {{ date('d-m-Y',strtotime(  $model->carProductionDate->created_at)) }}
                </td>
            </tr>
        @empty
            <p>
                no car models found
            </p>
        @endforelse

        <p>
            Product type 
            @forelse ($car->product as $type)
                {{ $type->name }}
            @empty
                <p>
                    dont have type
                </p>
            @endforelse
        </p>
    </table>


    {{-- <ul>
        <p>
            Models  :   
        </p>
        {{dd($car)}}
        {{var_dump($car)}}

        kemungkinan mobil ada yg tidak punya model makanya make forelse
        
        {{dd($car->carModel)}}
        carModel berdasarkan nama dari function yg ada di model


        @forelse ($car->carModel as $model)
            <li>
                {{ $model->model_name }}
                {{ $model['model_name'] }}
            </li>
            
        @empty
            <p>
                No models Found
            </p>
        @endforelse

    </ul> --}}

    
    
@endsection