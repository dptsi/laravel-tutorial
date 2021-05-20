# Traits

[Kembali](readme.md)



## Latar Belakang Topik

PHP merupakan suatu bahasa yang tidak mendukung multiple inheritance, dengan kata lain hanya setiap kelas hanya diperbolehkan mengextend 1 kelas lain.

## Konsep

Trait merupakan suatu cara untuk melakukan multiple inheritance pada PHP. Trait akan menyimpan berbagai fungsi yang dapat digunakan oleh kelas-kelas yang membutuhkan. Penggunaan trait sendiri tidak terbatas hanya untuk 1 trait saja.

Singkatnya Trait adalah suatu mekanisme dimana suatu class diizinkan untuk menggunakan kembali kode program (code reuse) yang berasal dari hirarki yang berbeda.

Syntax Trait :

``` php
trait myTrait{
    
    ... ;
}
```


Syntax trait hampir sama dengan class, akan tetapi trait tidak dapat menginstansiasi dirinya sendiri, sehingga membutuhkan kelas sebagai pendukungnya.

``` php
trait myTrait{
    function Hello(){
        echo "Hello";
    }
}

$obj = new myTrait();

```

**Output:** : 
```
Error: Cannot instantiate trait myTrait ...
```


Syntax kelas yang menggunakan trait :

``` php
class myClass{
    use myTrait;
}
```


### Implementasi

Misalkan kita ingin mencetak `Perancangan Berbasis Kerangka Kerja`, dimana `Perancangan Berbasis ` dan `Kerangka Kerja` berada di kelas yang berbeda.

#### 1 Menggunakan extend

Di bawah merupakan kode untuk mencetak output dari 2 kelas tanpa menggunakan trait.

``` php
<?php

class ParentsA {
    public function methodA() {
        echo 'Perancangan Berbasis ';
    }
}

class ParentsB {
    public function methodB() {
        echo 'Kerangka Kerja';
    }
}


class childC extends ParentsA {       // Hanya bisa mengextend satu kelas
    
}

$obj = new childC();
$obj->methodA();
//  $obj->methodB();                     // Method B tidak dapat dipanggil

?>
```

**Output:** : 
```
Perancangan Berbasis 
```

Output tidak sesuai dengan keinginan kita


#### 2 Menggunakan Trait

Di bawah merupakan kode untuk mencetak output dari sebuah kelas yang di extend dan menggunakan sebuah trait.


``` php
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
```

**Output:** : 
```
Perancangan Berbasis Kerangka Kerja
```


#### 3 Menggunakan Multiple Trait

Di bawah merupakan kode untuk mencetak output menggunakan lebih dari satu trait.


``` php
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
```

**Output:** :  
```
Perancangan Berbasis Kerangka Kerja
```


#### 4 Menggunakan trait di dalam trait

Di bawah merupakan kode untuk mencetak output menggunakan trait yang digunakan trait lain.

```php
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
```

**Output:** : 
```
Institut Teknologi Sepuluh Nopember
```


#### 5 Urutan Prioritas Trait vs Class

Urutan prioritas method dalam trait memiliki dua aturan:

##### **1. Method turunan akan ditimpa oleh method yang berasal dari trait**

Pada contoh di bawah, terdapat 2 method dengan nama yang sama di dalam masing-masing trait yang digunakan dan class yang diextend.

``` php
<?php

class ParentsA {
    public function methodA() {
        echo 'Kelas Menang !!';
    }
}

trait ParentsB {
    public function methodA() {
        echo 'Trait Menang !!';
    }
}


class childC extends ParentsA {  
    use ParentsB;
}

$obj = new childC();
$obj->methodA();

?>
```

**Output:** : 
```
Trait Menang !!
```

Sesuai Aturan, dengan nama method yang sama, trait akan lebih diprioritaskan daripada kelas parent yang diextend.

##### 2. Current class method akan menimpa method yang berasal dari trait

Pada contoh di bawah, terdapat 2 method dengan nama yang sama di dalam masing-masing trait yang digunakan dan class yang akan diinstansiasi.

``` php
<?php

trait ParentsB {
    public function methodA() {
        echo 'Trait Menang !!';
    }
}


class childC  {
    use ParentsB;
    
    public function methodA() {
        echo 'Current Class Menang !!';
    }
}

$obj = new childC();
$obj->methodA();

?>
```

**Output:** :  
```
Current Class Menang !!
```

Untuk trait dan class yang akan diinstansiasi yang memiliki nama fungsi yang sama, php akan memprioritaskan kelas yang akan diinstansiasi.




## Referensi

https://khoerodin.id/object-oriented-php/trait-dalam-oop-php/
https://www.php.net/manual/en/language.oop5.traits.php
