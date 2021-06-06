<?php

namespace maximuse\HelloWorld\Listeners;

use maximuse\HelloWorld\Events\ShowHelloWasCalled;

class UpdateName
{
    public function handle(ShowHelloWasCalled $event)
    {
        config(['helloworld.name1' => "Nama baru dari Listener"]);
    }
}