<?php
//main.php
require 'myClass.php';

use \Dan\Tools\myClass as theClass;

// buat objek dari class myClass
$myObject = new theClass();

// coba fungsi myFunction()
echo $myObject->myFunction();
