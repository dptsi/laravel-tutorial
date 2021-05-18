# Access Modifier

[Kembali](readme.md)

## Latar belakang topik

Terkadang dalam suatu class terdapat atribut atau fungsi yang ingin kita batasi akses penggunaannya. Kita ingin mengakses atribut atau fungsi tersebut hanya untuk class itu saja, class itu dan turunanannya, atau bahkan ingin kita gunakan secara bebas. Perbedaan akses ini dapat terjadi karena kita ingin menjaga keamanan sebuah atribut atau fungsi, dimana atribut atau fungsi tersebut memiliki data atau perintah yang bersifat rahasia dan tidak boleh dijalankan secara sembarangan. Sehingga, untuk mengatur dan menentukan hak akses suatu atribut atau fungsi pada class, kita dapat menggunakan access modifier.

## Konsep-konsep

Access modifier merupakan keyword pada PHP yang berfungsi untuk mengatur dan menentukan hak akses atribut dan fungsi pada class. Ada 3 access modifier yang dapat digunakan, yaitu:
* Public

Public dapat digunakan apabila ingin mengakses atribut dan fungsi dari mana saja, bahkan dari luar class (pada saat menjadi object). Public merupakan default, sehingga apabila kita tidak menuliskan access modifier pada atribut dan fungsi, maka atribut dan fungsi akan menjadi public.

* Protected

Protected dapat digunakan apabila ingin memberikan akses atribut dan fungsi kepada class tersebut dan turunannya, tetapi tidak kepada object.

* Private

Private dapat digunakan apabila ingin mengakses atribut dan fungsi dari class tersebut saja.

## Langkah-langkah tutorial

### Langkah pertama

Buat class `employee` dengan atribut dan fungi yang memiliki access modifier yang berbeda-beda.

```
<?php
class Employee {
  public $name;
  protected $position;
  private $salary;

  function set_name($name) {
    $this->name = $name;
  }

  public function get_name() {
    return $this->name;
  }

  protected function set_salary() {
    $this->salary = $this->salary;
  }

  private function get_salary() {
    return $this->salary;
  }
}

...

?>
```

### Langkah kedua

Membuat object `employee1` dari class `employee`.

```
<?php

...

$employee1 = new Employee();

...

?>
```

### Langkah ketiga

Mencoba mengakses atribut public dari object.

```
<?php

...

$employee1->name = "Steven";
echo $employee1->name;
echo "\n";

...

?>
```

Output dari langkah ini adalah `Steven`, sehingga dapat diketahui bahwa name dari employee1 dapat diubah dan diakses dari object.

### Langkah keempat

Mencoba mengakses atribut protected dari object.

```
<?php

...

// akan muncul error
$employee1->position = "Manager";
echo $employee1->position;
echo "\n";

...

?>
```

Hasil dari langkah ini adalah error, karena position merupakan protected sehingga tidak dapat diubah dan diakses dari object.

### Langkah kelima

Mencoba mengakses atribut private dari object.

```
<?php

...

// akan muncul error
$employee1->salary = 100.95;
echo $employee1->salary;
echo "\n";

...

?>
```

Hasil dari langkah ini adalah error, karena salary memiliki access modifier private yang tidak dapat diubah dan diakses dari object.

### Langkah keenam

Mencoba mengakses fungsi public dari object.

```
<?php

...

$employee1->set_name("Steve");
echo $employee1->get_name();
echo "\n";

...

?>
```

Output dari langkah ini adalah `Steve`, sehingga dapat dibuktikan bahwa fungsi public dapat diakses dari object. Langkah ini juga dapat membuktikan bahwa atribut public dapat diakses dari dalam class.

### Langkah ketujuh

Mencoba mengakses fungsi protected dari object.

```
<?php

...

// akan muncul error
$employee1->set_salary(100.45);

...

?>
```

Hasil dari langkah ini adalah error, karena fungsi set_salary merupakan protected dan tidak dapat diakses dari object.

### Langkah kedelapan

Mencoba mengakses fungsi private dari object.

```
<?php

...

// akan muncul error
echo $employee1->get_salary();
echo "\n";

...

?>
```

Hasil dari langkah ini adalah error, karena fungsi get_salary memiliki access modifier private yang tidak dapat diakses dari object.
