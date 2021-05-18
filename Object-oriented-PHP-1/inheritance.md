# Inheritance

[Kembali](readme.md)

## Latar belakang topik

Sering kali dalam pembuatan beberapa class terdapat kemiripan atribut dan fungsi yang dimiliki, dimana suatu class memiliki beberapa atribut dan fungsi yang sama dengan class yang lain. Biasanya kita akan menulis code untu atribut dan fungsi yang sama berulang kali pada class-class yang mirip tersebut. Namun, kita dapat mencegah penulisan atribut dan fungsi yang sama berulang-ulang kali dengan menggunakan inheritance.  

## Konsep-konsep

Inheritance merupakan sebuah konsep dimana sebuah class dapat menurunkan semua atribut dan fungsi yang memiliki access modifier protected dan public kepada class lainnya, guna menghindari terjadinya duplikasi kode program. Class yang menurunkan biasa disebut sebagai parent class, super class, atau base class dan class yang mewarisi biasa disebut sebagai child class, sub class, atau derived class. Selain memiliki atribut dan fungsi yang diturunkan dari parent class, child class juga dapat memiliki atribut dan fungsi tersendiri. Child class juga dapat mengubah isi dari fungsi yang diturunkan parent nya (override), namun ia tidak dapat mengubah jumlah parameter yang diterima fungsi tersebut. Child class dapat mewarisi parent class dengan menambahkan keyword extends setelah nama class nya, diikuti dengan nama parent class nya.

## Langkah-langkah tutorial

### Langkah pertama

Buat parent class `Employee`. 

```
<?php
class Employee {
  public $name;
  protected $salary;

  function set_name($name) {
    $this->name = $name;
  }

  function get_name() {
    return $this->name;
  }

  function set_salary($salary) {
    $this->salary = $salary;
    echo $this->get_salary();
    echo "\n";
  }

  protected function get_salary() {
    return $this->salary;
  }
}

...

?>
```

### Langkah kedua

Buat child class `HourlyEmployee`. 

```
<?php

...

class HourlyEmployee extends Employee{
  private $hours;

  function set_hours($hours) {
    $this->hours = $hours;
  }

  function get_hours() {
    return $this->hours;
  }

  // override
  function set_salary($salary) {
    $this->salary = $this->hours * $salary;
    echo $this->get_salary();
    echo "\n";
  }
}

...

?>
```

### Langkah ketiga

Buat object `employee1` dari child class `HourlyEmployee`.

```
<?php

...

$employee1 = new HourlyEmployee();

...

?>
```

### Langkah keempat

Coba menggunakan fungsi dan atribut yang diturunkan parent class.

```
<?php

...

$employee1->set_name("Steven");
echo $employee1->name;
echo "\n";

...

?>
```

Output yang dihasilkan dari langkah ini adalah `Steven`, sehingga dapat terlihat bahwa object dengan child class dapat mengakses atribut dan fungsi yang memiliki access modifier public dari parent class nya.

### Langkah kelima

Coba menggunakan fungsi dan atribut yang dimiliki child class sendiri.

```
<?php

...

$employee1->set_hours(16);

...

?>
```

Hasil yang diperoleh dari langkah ini adalah tidak munculnya error. Hal ini menandakan bahwa object dengan child class dapat membuat dan mengakses atribut dan fungsi yang dimilikinya sendiri.

### Langkah keenam

Coba menggunakan fungsi parent class yang telah di-override oleh child class.

```
<?php

...

$employee1->set_salary(0.5);

...

?>
```

Output yang diperoleh dari langkah ini adalah `16`, yang merupakan hasil perkalian salary yang diinputkan dan hours yang telah di-set sebelumnya. Hal ini membuktikan bahwa object dengan child class dapat menggunakan fungsi dari parent class yang telah di-override, bukan fungsi asli dari parent class tersebut.
