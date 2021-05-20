<?php

trait ParentsA {
    public function methodA() {
        echo 'Perancangan Berbasis ';
    }
}

trait ParentsB {
    public function methodB() {
        echo 'Kerangka Kerja';
    }
}


class childC {                   // tidak perlu melakukan extend
    use ParentsA, ParentsB;
}

$obj = new childC();
$obj->methodA();
$obj->methodB();                     // Method A dan B dapat dipanggil

?>