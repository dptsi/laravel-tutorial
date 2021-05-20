<?php

class ParentsA {
    public function methodA() {
        echo 'Perancangan Berbasis ';
    }
}

trait ParentsB {
    public function methodB() {
        echo 'Kerangka Kerja';
    }
}


class childC extends ParentsA {       // Hanya bisa mengextend satu kelas
    use ParentsB;
}

$obj = new childC();
$obj->methodA();
$obj->methodB();                     // Method B dapat dipanggil

?>