# Access Modifier

[Kembali](readme.md)

## Latar belakang topik

Terkadang dalam suatu class, terdapat atribut atau method yang ingin kita batasi akses penggunaannya. Kita ingin mengakses atribut atau method tersebut hanya untuk class itu saja, class itu dan turunanannya, atau bahkan ingin kita gunakan secara bebas. Perbedaan akses ini dapat terjadi karena kita ingin menjaga keamanan sebuah atribut atau method, dimana atribut atau method tersebut memiliki data atau perintah yang bersifat rahasia dan tidak boleh dijalankan secara sembarangan. Sehingga, untuk mengatur dan menentukan hak akses suatu atribut atau method pada class, kita dapat menggunakan access modifier.

## Konsep-konsep

Access modifier merupakan keyword pada PHP yang berfungsi untuk mengatur dan menentukan hak akses atribut dan method pada class. Ada 3 access modifier yang dapat digunakan, yaitu:
* Public

Public dapat digunakan apabila ingin mengakses atribut dan method dari mana saja, bahkan dari luar class (pada saat menjadi object). Public merupakan default, sehingga apabila kita tidak menuliskan access modifier pada atribut dan method, maka atribut dan method akan menjadi public.

* Protected

Protected dapat digunakan apabila ingin memberikan akses atribut dan method kepada class tersebut dan turunannya, tetapi tidak kepada object.

* Private

Private dapat digunakan apabila hanya ingin mengakses atribut dan method dari dalam class itu saja.

## Langkah-langkah tutorial

### Langkah pertama

Buat class `Employee`.

```php
<?php

class Employee {

  ...
  
}

?>
```

### Langkah kedua

Tambahan atribut dengan berbagai access modifier ke dalam class `Employee`.

```php
<?php

class Employee {
  public $name;
  protected $position;
  private $salary;
  
  ...
  
}

?>
```

### Langkah ketiga

Tambahan method `set_name` dan `get_name` dengan access modifier public ke dalam class `Employee`, dimana method set_name mengakses method public get_name dari dalam class dan keduanya mengakses atribut public dari dalam class.

```php
<?php

class Employee {
  public $name;
  protected $position;
  private $salary;
  
  function set_name($name) {
    $this->name = $name;
    echo $this->get_name();
    echo "\n";
  }

  public function get_name() {
    return $this->name;
  }
  
  ...
  
}

?>
```

### Langkah keempat

Tambahan method `set_position` dan `get_position` dengan access modifier public dan protected ke dalam class `Employee`, dimana method set_position mengakses method protected get_position dari dalam class dan keduanya mengakses atribut protected dari dalam class.

```php
<?php

class Employee {
  public $name;
  protected $position;
  private $salary;
  
  function set_name($name) {
    $this->name = $name;
    echo $this->get_name();
    echo "\n";
  }

  public function get_name() {
    return $this->name;
  }
  
  public function set_position($position) {
    $this->position = $position;
    echo $this->get_position();
    echo "\n";
  }

  protected function get_position() {
    return $this->position;
  }
  
  ...
  
}

?>
```

### Langkah kelima

Tambahan method `set_salary` dan `get_salary` dengan access modifier public dan private ke dalam class `Employee`, dimana method set_salary mengakses method private get_salary dari dalam class dan keduanya mengakses atribut private dari dalam class.

```php
<?php

class Employee {
  public $name;
  protected $position;
  private $salary;
  
  function set_name($name) {
    $this->name = $name;
    echo $this->get_name();
    echo "\n";
  }

  public function get_name() {
    return $this->name;
  }
  
  public function set_position($position) {
    $this->position = $position;
    echo $this->get_position();
    echo "\n";
  }

  protected function get_position() {
    return $this->position;
  }
  
  public function set_salary($salary) {
    $this->salary = $salary;
    echo $this->get_salary();
    echo "\n";
  }

  private function get_salary() {
    return $this->salary;
  }
  
}

?>
```

### Langkah keenam

Inisialisasi object `employee` dengan class `Employee`.

```php
<?php

$employee = new Employee();

?>
```

### Langkah ketujuh

Coba akses atribut-atribut `employee` dari object.

```php
<?php

$employee->name = "Steven";
echo $employee->name;
echo "\n";

// akan muncul error
$employee->position = "Manager";
echo $employee->position;
echo "\n";

// akan muncul error
$employee->salary = 100.95;
echo $employee->salary;
echo "\n";

?>
```

Pada langkah ini, hanya atribut dengan access modifier public yang dapat diakses dari object, sedangkan lainnya tidak dapat diakses dan akan menampilkan pesan error ketika dijalankan.

### Langkah kedelapan

Coba akses method public `set_name`, yang mengakses method public lainnya dari dalam class, beserta method public `get_name` dari object.

```php
<?php

$employee->set_name("Steve");
echo $employee->get_name();
echo "\n";

?>
```

Pada langkah ini, kedua method dengan access modifier public berhasil dijalankan.

### Langkah kesembilan

Coba akses method public `set_position`, yang mengakses method protected dari dalam class, beserta method protected `get_position` dari object.

```php
<?php

$employee->set_position("Programmer");
// akan muncul error
echo $employee->get_position();
echo "\n";

?>
```

Pada langkah ini, method public `set_position` dapat dijalankan dengan baik, sedangkan method protected `get_position` akan memunculkan pesan error karena kita berusaha mengakses method dengan access modifier protected dari object.

### Langkah kesepuluh

Coba akses method public `set_salary`, yang mengakses method private dari dalam class, beserta method private `get_salary` dari object.

```php
<?php

$employee->set_salary(100.45);
// akan muncul error
echo $employee->get_salary();
echo "\n";

?>
```

Pada langkah ini, method public `set_salary` dapat dijalankan dengan baik, sedangkan method private `get_salary` akan memunculkan pesan error karena kita berusaha mengakses method dengan access modifier private dari luar class (object).
