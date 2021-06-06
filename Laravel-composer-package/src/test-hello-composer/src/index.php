<?php

require_once __DIR__ . '/../vendor/autoload.php';

use maximuse\HelloWorld\HelloWorld;

$hello_world = new HelloWorld();

echo $hello_world->hello();

?>