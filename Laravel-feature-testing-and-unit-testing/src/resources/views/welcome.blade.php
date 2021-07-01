@extends('layouts.app')

@section('content')
    <div class="background-image grid grid-cols-1 m-auto">
        <div class="flex text-gray-100 pt-10">
            <div class="m-auto pt-4 pb-16 sm:m-auto w-4/5 block text-center">
                <h1 class="sm:text-white text-5xl uppercase font-bold text-shadow-md pb-14">
                    Do you want to become a developer?
                </h1>
                <a href="/post" class="text-center bg-gray-50 text-gray-700 py-2 px-4 font-bolt text-xl uppercase">
                    Read More
                </a>
            </div>
        </div>
    </div>
    <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-gray-200">
        <div>
            <img src="https://cdn.pixabay.com/photo/2015/01/08/18/25/desk-593327_960_720.jpg" width="700" alt="">
        </div>

        <div class="m-auto sm:m-auto text-left w-4/5 block">
            <h2 class="text-3xl font-extrabold text-gray-600">
                Struggling to be a better developer?
            </h2>

            <p class="py-8 text-gray-500 text-s">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. In officia magni exercitationem
            </p>

            <p class="font-extrabold text-gray-600 text-s pb-9">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Adipisci illum repellat ipsum vitae itaque tenetur
                in soluta, reprehenderit, possimus animi voluptate quo quasi blanditiis! Aperiam, in a. Rerum, quos aut.
            </p>

            <a href="/post" class="uppercase bg-blue-500 text-gray-100 text-s font-extrabold py-3 px-8 rounded-3xl">
                Find out more
            </a>
        </div>
    </div>

    <div class="text-center py-15">
        <span class="uppercase text-s text-gray-400">
            Blog
        </span>

        <h2 class="text-4xl font-bold py-10">
            Recent Posts
        </h2>

        <p class="m-auto w-4/5 text-gray-500">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Expedita incidunt repudiandae iste in distinctio
            laborum ut nam dolorum amet illum ratione magnam at rem explicabo minima, culpa unde. Aut, aperiam!
        </p>
    </div>

    <div class="sm:grid grid-cols-2 w-4/5 m-auto">
        <div class="flex bg-yellow-700 text-gray-100 pt-10">
            <div class="m-auto pt-4 pb-16 sm:m-auto w-4/5 block">
                <span class="uppercase text-xs">
                    PHP
                </span>

                <h3 class="text-xl font-bold py-10">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aperiam necessitatibus molestiae placeat esse,
                    accusamus hic quidem repudiandae nulla. Aliquid vero culpa omnis architecto non iste cumque, quasi
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                </h3>

                <a href=""
                    class="uppercase bg-transparent border-2 border-gray-100 text-gray-1000 text-xs font-extrabold py-3 px-5 rounded-3xl">
                    Find Out More
                </a>
            </div>
        </div>
        <div>
            <div>
                <img src="https://cdn.pixabay.com/photo/2015/01/08/18/25/desk-593327_960_720.jpg" alt="">
            </div>
        </div>
    </div>
@endsection
