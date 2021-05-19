# Inheritance

[Kembali](readme.md)

## Latar belakang topik

Sering kali dalam pembuatan beberapa class terdapat kemiripan atribut dan fungsi yang dimiliki, dimana suatu class memiliki beberapa atribut dan fungsi yang sama dengan class yang lain. Misalnya seperti class HourlyEmployee yang memiliki atribut dan fungsi yang sama dengan class Employee, seperti name, salary, dan fungsi setter-getter nya. Namun, yang membedakan adalah class HourlyEmployee memiliki atribut lainnya yang berbeda dengan class Employee, yaitu hours dan salaryperhours. Cara perhitungan salary mereka pun berbeda, dimana class Employee menerima salary yang tetap, sedangkan class Employee menerima salary berdasarkan hasil perkalian jumlah jam mereka bekerja dengan salaryperhours.

Biasanya kita akan menulis code untuk atribut dan fungsi yang sama berulang kali pada class-class yang mirip tersebut. Namun, kita dapat mencegah penulisan atribut dan fungsi yang sama berulang-ulang kali dengan menggunakan inheritance dan memanfaatkan override untuk mengubah isi fungsi yang berbeda, seperi set_salary dari class HourlyEmployee. Sehingga, untuk mencegah pengulangan penulisan code yang sama pada class Employee dan class HourlyEmployee, kita dapat meng-inharitance class HourlyEmployee dari class Employee.

## Konsep-konsep

Inheritance merupakan sebuah konsep dimana sebuah class dapat menurunkan semua atribut dan fungsi yang memiliki access modifier protected dan public kepada class lainnya, guna menghindari terjadinya duplikasi kode program. Class yang menurunkan biasa disebut sebagai parent class, super class, atau base class dan class yang mewarisi biasa disebut sebagai child class, sub class, atau derived class. Selain memiliki atribut dan fungsi yang diturunkan dari parent class, child class juga dapat memiliki atribut dan fungsi tersendiri. Child class juga dapat mengubah isi dari fungsi yang diturunkan parent nya (override), namun ia tidak dapat mengubah jumlah parameter yang diterima fungsi tersebut. Child class dapat mewarisi parent class dengan menambahkan keyword extends setelah nama class nya, diikuti dengan nama parent class nya.

## Langkah-langkah tutorial

### Langkah pertama

Buat parent class `Employee`. 

```php
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

?>
```

### Langkah kedua

Buat child class `HourlyEmployee` dengan menambahkan extends dan nama parent class di samping nama class nya.

```php
<?php

class HourlyEmployee extends Employee{
  
  ...
  
}

?>
```

### Langkah ketiga

Tambahkan atribut `hours` dan `salaryperhours` pada child class `HourlyEmployee`. 

```php
<?php

class HourlyEmployee extends Employee{
  private $hours;
  private $salaryperhours;

  ...
  
}

?>
```

### Langkah keempat

Tambahkan fungsi `set_hours` dan `get_hours` pada child class `HourlyEmployee`. 

```php
<?php

class HourlyEmployee extends Employee{
  private $hours;

  function set_hours($hours) {
    $this->hours = $hours;
  }

  function get_hours() {
    return $this->hours;
  }

  ...
  
}

?>
```

### Langkah kelima

Tambahkan fungsi `set_salary` yang sudah di-override pada child class `HourlyEmployee`. 

```php
<?php

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
    $this->salaryperhours = $salaryperhours;
    $this->salary = $this->hours * $this->salaryperhours;
    echo $this->get_salary();
    echo "\n";
  }
  
}

?>
```

### Langkah keenam

Inisialisasi object `employee` dengan child class `HourlyEmployee`.

```php
<?php

$employee = new HourlyEmployee();

?>
```

### Langkah ketujuh

Coba gunakan fungsi `set_name` dan atribut `name` yang diturunkan parent class.

```php
<?php

$employee->set_name("Steven");
echo $employee->name;
echo "\n";

?>
```

Pada langkah ini, object dari child class dapat mengakses atribut dan fungsi public dari parent class.

### Langkah kedelapan

Coba gunakan fungsi `set_hours` dan `get_hours` serta atribut `hours` melalui kedua fungsi yang dimiliki child class tersebut.

```php
<?php

$employee->set_hours(16);
echo $employee->get_hours();
echo "\n";

?>
```

Pada langkah ini, object dari child class dapat mengakses fungsi yang dimiliki child class sendiri beserta atributnya secara tidak langsung.

### Langkah keenam

Coba gunakan fungsi parent class yang telah di-override oleh child class.

```php
<?php

$employee->set_salary(0.5);

?>
```

Pada langkah ini, object dari child class dapat mengakses fungsi yang telah di-override dari parent class dan fungsi protected get_salary dari parent class secara tidak langsung. Output yang diperoleh dari langkah ini adalah `16`, yang merupakan hasil perkalian salaryperhours yang diinputkan dan hours yang telah di-set sebelumnya. Hal ini menunjukkan bahwa object dengan child class menggunakan fungsi yang telah di-override, bukan fungsi asli dari parent class tersebut.
