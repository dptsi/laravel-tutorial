# Inheritance

[Kembali](readme.md)

## Latar belakang topik

Sering kali dalam pembuatan beberapa class terdapat kemiripan atribut dan method yang dimiliki, dimana suatu class memiliki beberapa atribut dan method yang sama dengan class yang lain. Misalnya seperti class HourlyEmployee yang memiliki atribut dan method yang sama dengan class Employee, seperti name, salary, dan method setter-getter nya. Namun, yang membedakan adalah class HourlyEmployee memiliki atribut lainnya yang berbeda dengan class Employee, yaitu hours dan salaryperhours. Cara perhitungan salary mereka pun berbeda walaupun nama method untuk perhitungan salary mereka sama, dimana class Employee menerima salary yang tetap sedangkan class Employee menerima salary berdasarkan hasil perkalian jumlah jam mereka bekerja dengan salaryperhours.

Biasanya, kita akan menulis code untuk atribut dan method yang sama berulang kali pada class-class yang mirip tersebut. Namun, kita dapat mencegah penulisan atribut dan method yang sama berulang-ulang kali dengan menggunakan inheritance dan memanfaatkan override untuk mengubah isi method yang berbeda, seperi set_salary dari class HourlyEmployee. Sehingga, untuk mencegah pengulangan penulisan code yang sama pada class Employee dan class HourlyEmployee, kita dapat meng-inharitance class HourlyEmployee dari class Employee.

## Konsep-konsep

Inheritance merupakan sebuah konsep dimana sebuah class dapat menurunkan semua atribut dan method yang memiliki access modifier protected dan public kepada class lainnya, guna menghindari terjadinya duplikasi kode program. Class yang menurunkan biasa disebut sebagai parent class, super class, atau base class dan class yang mewarisi biasa disebut sebagai child class, sub class, atau derived class. Selain memiliki atribut dan method yang diturunkan dari parent class, child class juga dapat memiliki atribut dan method tersendiri. Child class juga dapat mengubah isi dari method yang diturunkan parent nya (override), namun ia tidak dapat mengubah jumlah parameter yang diterima method tersebut. Child class dapat mewarisi parent class dengan menambahkan keyword extends setelah nama class nya, diikuti dengan nama parent class nya.

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

Tambahkan method `set_hours` dan `get_hours` pada child class `HourlyEmployee`. 

```php
<?php

class HourlyEmployee extends Employee{
  private $hours;
  private $salaryperhours;

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

Tambahkan method `set_salary` yang sudah di-override pada child class `HourlyEmployee`. 

```php
<?php

class HourlyEmployee extends Employee{
  private $hours;
  private $salaryperhours;

  function set_hours($hours) {
    $this->hours = $hours;
  }

  function get_hours() {
    return $this->hours;
  }

  // override
  function set_salary($salaryperhours) {
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

Coba gunakan method `set_name` dan atribut `name` yang diturunkan parent class.

```php
<?php

$employee->set_name("Steven");
echo $employee->name;
echo "\n";

?>
```

Pada langkah ini, object dari child class dapat mengakses atribut dan method public dari parent class.

### Langkah kedelapan

Coba gunakan method `set_hours` dan `get_hours` serta atribut `hours` melalui kedua method yang dimiliki child class tersebut.

```php
<?php

$employee->set_hours(16);
echo $employee->get_hours();
echo "\n";

?>
```

Pada langkah ini, object dari child class dapat mengakses method yang dimiliki child class sendiri beserta atributnya secara tidak langsung.

### Langkah kesembilan

Coba gunakan method parent class yang telah di-override oleh child class.

```php
<?php

$employee->set_salary(0.5);

?>
```

Pada langkah ini, object dari child class dapat mengakses method yang telah di-override dari parent class dan method protected get_salary dari parent class secara tidak langsung. Output yang diperoleh dari langkah ini adalah `8`, yang merupakan hasil perkalian salaryperhours yang diinputkan dan hours yang telah di-set sebelumnya. Hal ini menunjukkan bahwa object dengan child class menggunakan method yang telah di-override, bukan method asli dari parent class tersebut.
