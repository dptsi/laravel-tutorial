<?php
//myClass.php

namespace Dan\Tools;

use DateTime;

class myClass
{
    public function myFunction() {
        echo "Hello World\n";
        $x = new DateTime();
        echo $x->getTimestamp();
    }
}
