# Constructor

[Kembali](readme.md)

## Latar belakang topik
Dalam Pemrograman Berbasis Objek, setelah membuat `Class`, maka nantinya kita bisa menghasilkan `Object`. Proses menghasilkan / mencetak `objek` dari `class` ini disebut dengan `Instansiasi (Instantiation)`. Biasanya setelah melakukan instansiasi inilah kita bisa melakukan eksekusi pada object, misalnya dengan menggunakan setter (set_name, etc) lalu getter (get_name, etc). Namun terdapat cara dimana ketika melakukan instansiasi maka setelahnya tidak perlu melakukan setter ataupun getter untuk mengeksekusi, karena saat di instansiasi, object akan langsung dieksekusi caranya ialah dengan menggunakan fungsi Constructor.

## Konsep-konsep

Constructor ialah fungsi khusus dimana dia akan mengeksekusi object pada saat object di instansiasi. Konstruktor tidak harus ada, namun dalam satu class hanya boleh ada satu konstruktor. Method konstruktor biasanya berisi pemberian nilai default dari masing-masing properties (variabel). Untuk membuat konstruktor, cukup dengan mendefinisikan suatu fungsi dengan nama __construct(). 

## Langkah-langkah tutorial

### Langkah pertama

Membuat `Class Employee` dengan atribut dan juga method construct 

```php
<?php
class Employee {
  public $name;
  public $position;

  //method construct didalamnya terdapat argumen name dan position
  function __construct($name, $position) { 
    $this->name = $name;
    $this->position = $position;
  }
}

```

### Langkah kedua

Menginstansiasi object dengan argumen nama dan posisi 
```php
$employee1 = new Employee("Steven", "Manager");
```

### Langkah ketiga

Memanggil hasil nya dengan `echo`
```php
echo "Nama Karyawan: ".$employee1->name;
echo "\n";
echo "Posisi: ".$employee1->position;
echo "\n";
```

## Hasil
Output yang dihasilkan

![hasilnya](https://user-images.githubusercontent.com/80946219/118687345-17f77880-b82f-11eb-9d96-6bd715ef0378.png)

