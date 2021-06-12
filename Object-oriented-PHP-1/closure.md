# Closure

[Kembali](readme.md)

## Latar belakang topik

Terkadang kita ingin menulis dan memanggil fungsi dengan mudah, terutama dalam fungsi callable, dimana kita ingin menuliskan fungsi secara langsung dalam parameternya, bukan menuliskan nama fungsi tersebut dalam parameter. Untuk mempermudahkan penulisan tersebut, kita dapat menggunakan closure pada parameter callable. Selain itu, variabel yang dapat digunakan untuk merujuk ke sebuah fungsi juga semakin mendukung penggunaan closure.

## Konsep-konsep

Closure atau anonymous function merupakan fungsi yang tidak memiliki nama. Untuk memanggilnya secara individu, kita dapat menggunakan variabel yang merujuk pada closure tersebut. Seperti fungsi pada umumnya, closure juga dapat memiliki parameter dan me-return sebuah nilai. Pada umumnya, closure digunakan sebagai nilai parameter callable. 

## Langkah-langkah tutorial

### Langkah pertama

Buat variabel yang merujuk pada closure tanpa parameter.

```php
<?php

$print_message1 = function(){
  echo "Hello!\n";
};

?>
```

### Langkah kedua

Panggil closure dengan nama variabel yang dibuat diikuti dengan tanda kurung.

```php
<?php

$print_message1();

?>
```

Pada langkah ini, output yang dihasilkan adalah `Hello!`.

### Langkah ketiga

Buat variabel yang merujuk pada closure dengan parameter dan return value.

```php
<?php

$get_message = function($name){
  return "Hello, " . $name . "!\n";
};

?>
```

### Langkah keempat

Panggil closure dengan nama variabel yang dibuat diikuti dengan tanda kurung dan parameter yang ingin diinputkan.

```php
<?php

echo $get_message("Steven");

?>
```

Pada langkah ini, output yang dihasilkan adalah `Hello, Steven!`.

### Langkah kelima

Buat variabel message dan variabel yang merujuk pada closure tanpa parameter dan dengan keyword use agar closure dapat menggunakan variabel di luar closure, bukan dilewatkan dari parameter.

```php
<?php

$message = "Hello!";
$print_message2 = function() use ($message){
  echo $message;
  echo "\n";
};

?>
```

### Langkah keenam

Panggil closure dengan nama variabel yang dibuat diikuti dengan tanda kurung.

```php
<?php

$print_message2();

?>
```

Pada langkah ini, output yang dihasilkan adalah `Hello!`.

### Langkah ketujuh

Buat associative array (array dengan named key) `fruits` dan panggil fungsi `array_walk` dengan parameter fruits dan closure. Fungsi array_walk merupakan fungsi yang menjalankan setiap elemen array dalam fungsi yang ditentukan pengguna. Value dan key array adalah parameter dalam fungsi dan dalam pengimplementasian array_walk, value dan key tidak dapat diubah urutannya, namun untuk mengganti nama variabelnya diperbolehkan.

```php
<?php

$fruits = ["a" => "Apel", "b" => "Belimbing", "c" => "Cerry"];

array_walk($fruits, function($value, $key) {
 echo $key . ". "  . $value . "\n";
});

?>
```

Pada langkah ini, output yang dihasilkan adalah sebagai berikut.
```
a. Apel
b. Belimbing
c. Cerry
```

### Langkah kedelapan

Walaupun kita dapat menulis closure secara langsung dalam parameter fungsi array_walk, kita juga dapat membuat variabel yang menunjuk pada closure dan memanggil nama variabel tersebut dari parameter fungsi array_walk.

```php
<?php

$fruits = ["a" => "Apel", "b" => "Belimbing", "c" => "Cerry"];

$print_fruits = function($value, $key) {
 echo $key . ". "  . $value . "\n";
};

array_walk($fruits, $print_fruits);

?>
```

Output yang dihasilkan pada langkah ini akan sama dengan output pada langkah ketujuh.

### Langkah kesembilan

Selain value dan key, kita juga dapat passing parameter lain, seperti associative array fruits. Namun, urutannya harus tetap, value diletakkan di awal dan diikuti dengan key.

```php
<?php

$fruits = ["a" => "Apel", "b" => "Belimbing", "c" => "Cerry"];

array_walk($fruits, function($value, $key, $fruits) {
 echo $key . ". "  . $value . "\n";
 var_dump($fruits);
}, $fruits);

?>
```

Pada langkah ini, output yang dihasilkan adalah sebagai berikut.

```
a. Apel
array(3) {
  ["a"]=>
  string(4) "Apel"
  ["b"]=>
  string(9) "Belimbing"
  ["c"]=>
  string(5) "Cerry"
}
b. Belimbing
array(3) {
  ["a"]=>
  string(4) "Apel"
  ["b"]=>
  string(9) "Belimbing"
  ["c"]=>
  string(5) "Cerry"
}
c. Cerry
array(3) {
  ["a"]=>
  string(4) "Apel"
  ["b"]=>
  string(9) "Belimbing"
  ["c"]=>
  string(5) "Cerry"
}
```
