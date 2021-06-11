@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-center">
        <div class="py-15 border-b border-gray-200">
            <h1 class="text-6xl">
                Blog Post
            </h1>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="w-4/5 m-auto mt-10 pl-2">
            <p class="w-2/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4">
                {{ session()->get('message') }}
            </p>
        </div>
    @endif
    <div class="pt-15 w-4/5 m-auto">
        <a href="/post/create"
            class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
            Create post
        </a>
    </div>
    <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b">
            <div>
                <img src="https://cdn.pixabay.com/photo/2015/03/26/10/25/apple-691323_960_720.jpg" alt="">
            </div>
            <div>
                <h2 class="text-gray-700 font-bold text-5xl pb-4">
                    Template Post
                </h2>

                <span class="text-gray-500">
                    Created on XXX XXX XXX
                </span>

                <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam commodi modi quas ea magnam quia veritatis facere rerum numquam quidem. Odit perspiciatis molestiae tempore mollitia deleniti id quaerat voluptatum eius.
                </p>

                <a href="/post/none"
                    class="uppercase bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl">
                    Keep Reading
                </a>

                <span class="float-right">
                    <a href="/post/none/edit"
                        class="text-gray-700 hover:text-gray-900 pb-1 border-b-2">Edit</a>
                </span>
                <span class="float-right">
                    <form action="/post/none" method="POST">
                        @csrf
                        @method('delete')
                        <button class="text-red-500 pr-3" type="submit">
                            Delete
                        </button>
                    </form>
                </span>
            </div>
        </div>
    </div>
    @foreach ($posts ?? [] as $post)
        <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b">
            <div>
                <img src="{{ asset('images/' . $post->image_path) }}" alt="">
            </div>
            <div>
                <h2 class="text-gray-700 font-bold text-5xl pb-4">
                    {{ $post->title }}
                </h2>

                <span class="text-gray-500">
                    Created on {{ date('jS M Y', strtotime($post->updated_at)) }}
                </span>

                <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
                    {{ $post->description }}
                </p>

                <a href="/post/{{ $post->slug }}"
                    class="uppercase bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl">
                    Keep Reading
                </a>

                <span class="float-right">
                    <a href="/post/{{ $post->slug }}/edit"
                        class="text-gray-700 hover:text-gray-900 pb-1 border-b-2">Edit</a>
                </span>
                <span class="float-right">
                    <form action="/post/{{ $post->slug }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="text-red-500 pr-3" type="submit">
                            Delete
                        </button>
                    </form>
                </span>
            </div>
        </div>
    @endforeach
@endsection
