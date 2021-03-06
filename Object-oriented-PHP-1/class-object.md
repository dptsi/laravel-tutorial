# Class dan Object

[Kembali](readme.md)

## Latar belakang topik

Dalam pemograman berbasis objek, kita akan memetakan masalah kedalam class, serta memecah masalah kedalam bagian class – class, sehingga program akan terbagi menjadi bagian – bagian yang lebih kecil, didalam class akan terdiri method atau fungsi, serta terdapat property atau  attribute, nah dari class nanti kita bisa membuat object dari class yang telah dibuat.

Misalkan pada karyawan yang ada di suatu kantor, terdapat *Steven*, *Rudolf*, *Smith*, dan *Kevin* yang merupakan karyawan yang ada di kantor tersebut. Mereka antara satu sama lain memiliki kemiripan berupa sama-sama karyawan namun memiliki karakteristik atau ciri yang berbeda antara satu sama lain. Sehingga, mereka bisa disebut sebagai `Objek`. Salah satu karakteristik berbeda yang mereka miliki ialah `nama`. Dimana antar karyawan yang ada memiliki nama yang berbeda, nama ini bisa disebut dengan `Atribute` atau `Property`.

Kemiripan yang ada pada objek *Steven* dan objek *Rudolf* ialah sama-sama merupakan entitas karyawan maka bisa dibuat `Class` yang disebut dengan **Class Employee**. Sehingga pada Class Employee ini, terdapat 4 objek, yaitu employee1 yaitu *Steven*, employee2 yaitu *Rudolf*, employee3 yaitu *Smith*, dan employee4 yaitu *Kevin*. Maka bisa dibilang kalau, *Class* lah yang mencetak objek-objek.

## Konsep-konsep
`Class` adalah template, cetakan yang mewakili entitas dunia nyata, dimana pada **Class** dibutuhkan `Atribute` dan `Method` agar dapat menghasilkan suatu `Object`. Maka, **Class** adalah kerangka dasar yang harus dibuat terlebih dahulu sebelum membuat suatu **Object**. Object merupakan _reference types_, sehingga apabila object di-passing ke sebuah fungsi, maka value dari attribute nya dapat berubah.
 
## Langkah-langkah tutorial

### Langkah pertama

Membuat class 'Employee'

```php
<?php

class Employee{
    ...
}
```

### Langkah kedua

Menambahkan atribut nama

```php
<?php

class Employee{
    public $name;
}
```

### Langkah ketiga

Menambahkan method set_name dan get_name

```php
<?php

class Employee{
    public $name;
    
    function set_name($name) {
        $this->name = $name;
    }

    function get_name() {
        return $this->name;
    }
}
```

### Langkah keempat

Instansiasi object employee1 dan menampilkan hasilnya dengan `echo`

Cara1
```php
$employee1 = new Employee();
$employee1->set_name("Steven");
echo $employee1->get_name();
echo "\n";
```

Hasil yang didapatkan

![steven](https://user-images.githubusercontent.com/80946219/118923215-69098880-b965-11eb-879e-2075d18cd308.png)

Terdapat cara lain dalam menampilkan nama dari employee

Cara 2
```php
$employee2 = new Employee();
$employee2->set_name("Rudolf");
echo $employee2->{"get_name"}();
echo "\n";
```

Hasil yang didapatkan

![Rudolf](https://user-images.githubusercontent.com/80946219/118923320-8fc7bf00-b965-11eb-93db-b8eb0d52e63c.png)

Cara 3
```php
$employee3 = new Employee();
$employee3->set_name("Smith");
$funcGetNama = "get_name";
echo $employee3->{$funcGetNama}();
echo "\n";
```

Hasil yang didapatkan

![Smith](https://user-images.githubusercontent.com/80946219/118923417-b7b72280-b965-11eb-82e8-9ad724712b66.png)

Cara 4
Pada cara ini, method set_name dan get_name tidak diperlukan

```php
class Employee{
    public $name;
}

$employee4 = new Employee();
$employee4->name = "Kevin"; //set nama
echo $employee4->name; //print nama
echo "\n";
```

Hasil yang didapatkan

![Kevin](https://user-images.githubusercontent.com/80946219/118923519-dae1d200-b965-11eb-9070-226290d3b775.png)

### Langkah keempat

Passing object employee1 ke dalam fungsi change_name.

```php
function change_name($emp){
  $emp->name = "Smith";
}

echo $employee1->get_name();
echo "\n";

change_name($employee1);

echo $employee1->get_name();
echo "\n";
```

Hasil yang didapatkan

![change_name](https://user-images.githubusercontent.com/76677130/121764170-0ffbd180-cb6c-11eb-8570-124b84a802a8.png)

