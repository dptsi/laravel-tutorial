<?php

trait traitA {
    public function methodA() {
        echo 'Institut ';
    }
}

trait traitB {
    public function methodB() {
        echo 'Teknologi ';
    }
}

trait traitC {
    
    use traitA, traitB;

    public function methodC() {
        echo 'Sepuluh Nopember';
    }
}

class myClass {
    use traitC;
}

$obj = new myClass;
$obj->methodA();
$obj->methodB();
$obj->methodC();