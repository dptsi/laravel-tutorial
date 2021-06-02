@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ url('post/create') }}">Create</a>
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>User ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->name }}</td>
                            <td>{{ $post->user_id }}</td>
                            <td> 
                                {{-- @canany(['update', 'delete'], $post) --}}
                                <a href="{{ url('post/edit/'.$post->id) }}">Edit</a> <a href="{{ url('post/delete/'.$post->id) }}">Delete</a>
                                {{-- @endcanany --}}
                            </td> 
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br> <a href="{{ url('/') }}">Back</a>
        </div>
    </div>
</div>
@endsection