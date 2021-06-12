@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-left">
        <div class="py-15">
            <h1 class="text-6xl">
                {{ $post->title }}
            </h1>
        </div>
    </div>
    <div class="w-4/5 m-auto mb-5 pt-20 border-b border-gray-200">
        <span class="text-gray-500">
            By <span class="font-bold-italic text-gray-800">{{ $post->user->name }}</span>,
            Created on {{ date('jS M Y', strtotime($post->updated_at)) }}
        </span>

        <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
            {{ $post->description }}
        </p>
    </div>
    <div class="sm:grid grid-cols-5 gap-5 w-4/5 mx-auto py-5">
        <div class="col-span-4">
            <textarea name="description" placeholder="Write a comment..."
                class="py-3 px-5 bg-transparent block border w-full text-xl outline-none rounded-3xl"></textarea>
        </div>

        <div class="m-auto">
            <a href="/post/comment"
                class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
                Comment
            </a>
        </div>
    </div>
    <div class="w-4/5 py-5 mx-auto text-left border border-gray-200 rounded-3xl">
        <div class="px-10">
            <span class="text-xs text-gray-400">XXX XXX XXX</span>
            <h4 class="text-1xl">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. In eos deleniti fugit eius amet blanditiis iure illo a dolores alias minima optio fugiat quod id, beatae sunt culpa architecto atque?
            </h4>
        </div>
    </div>
@endsection
