<?php

namespace maximuse\HelloWorld\Http\Controllers;

use Illuminate\Routing\Controller;
use maximuse\HelloWorld\Events\ShowHelloWasCalled;

class HelloController extends Controller
{
    public function show_hello()
    {
        event(new ShowHelloWasCalled("Nama baru dari event"));

        $name = config('helloworld.name1');

        return view('helloworld::helloworld', compact('name'));
    }
}