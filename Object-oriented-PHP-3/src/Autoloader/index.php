<?php
/*
Contoh Tanpa Auto Load

include 'Sapi.php';
$sapi = new Sapi();
$sapi->suaraSapi();

include 'Kucing.php';
$kucing = new Kucing();
$kucing->suaraKucing();

include 'Kambing.php';
$kambing = new Kambing();
$kambing->suaraKambing();
*/


// Contoh Dengan Autoload

spl_autoload_register(function ($class) {
    include $class . '.php';
});

$sapi = new Sapi();
$sapi->suaraSapi();

$kucing = new Kucing();
$kucing->suaraKucing();

$kambing = new Kambing();
$kambing->suaraKambing();