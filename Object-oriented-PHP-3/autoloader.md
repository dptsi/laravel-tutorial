# Autoloader

[Kembali](readme.md)


## Latar Belakang

Programmer pada umumnya menggunakan satu file php untuk menyimpan satu kelas, sehingga untuk melakukan include, biasanya dilakukan secara manual dan terkesan bertele-tele.

Autoloading merupakan pemanggilan secara otomatis kelas dan atau interface yang belum didefinisikan tanpa harus menggunakan require satu persatu secara manual. Biasanya Autoloading digunakan untuk program-program besar yang memiliki banyak kelas.

## Konsep

Secara umum, pemanggilan kelas/interface lain dapat menggunakan `require_once` atau `include`. Akan tetapi, untuk program besar yang memiliki banyak kelas, kedua cara tersebut tidak akan efektif. Belum lagi untuk menentukan kelas-kelas mana saja yang dibutuhkan dan mana yang tidak.

Oleh karena itu, digunakan `spl_autoload_register()`, dimana untuk setiap kelas yang disimpan dalam suatu file, `spl_autoload_register()` akan otomatis memanggil kelas tersebut apabila dibutuhkan.


## Implementasi

Misalkan kita memiliki 3 kelas, Sapi(), Kucing(), dan Kambing().

File `Sapi.php`
``` php
<?php
class Sapi  {
    
    public function suaraSapi() {
        echo 'mooo';
    }
}
```

File `Kucing.php`
``` php
<?php
class Kucing  {
    
    public function suaraKucing() {
        echo 'meaw';
    }
}
```

File `Kambing.php`
``` php
<?php
class Kambing  {
    
    public function suaraKambing() {
        echo 'mbeek';
    }
}
```

Selanjutnya ketiga buah class tersebut akan dipanggil dalam satu file `index.php`

File `Index.php`
``` php
<?php
include 'Sapi.php';
$sapi = new Sapi();
$sapi->suaraSapi();

include 'Kucing.php';
$kucing = new Kucing();
$kucing->suaraKucing();

include 'Kambing.php';
$kambing = new Kambing();
$kambing->suaraKambing();

```

**Output:** :  
```
mooomeawmbeek
```

File index di atas merupakan file dengan menggunakan include yang dipisah-pisah. Hal tersebut akan menjadi tidak efektif apabila sudah berhadapan dengan program dengan puluhan atau ratusan kelas.

Oleh karenanya, digunakanlah Autoload seperti contoh di bawah :

File `Index.php`
``` php
<?php

spl_autoload_register(function ($class) {
    include $class . '.php';
});

$sapi = new Sapi();
$sapi->suaraSapi();

$kucing = new Kucing();
$kucing->suaraKucing();

$kambing = new Kambing();
$kambing->suaraKambing();

```

**Output:** :  
```
mooomeawmbeek
```

File index di atas tidak lagi memerlukan pemanggilan kelas-kelas lainnya, karena `spl_autoload_register()` telah secara otomatis memanggil kelas-kelas yang dibutuhkan.


spl : standard php library

## Referensi

https://khoerodin.id/object-oriented-php/class-autoloader-dalam-oop-php/
<br>
https://www.php.net/manual/en/language.oop5.autoload.php
