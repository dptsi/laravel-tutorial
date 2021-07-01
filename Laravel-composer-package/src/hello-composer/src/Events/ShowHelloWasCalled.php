<?php

namespace maximuse\HelloWorld\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ShowHelloWasCalled
{
    use Dispatchable, SerializesModels;

    public function __construct($newName)
    {
        config(['helloworld.name1' => $newName]);
    }
}