# Constructor

[Kembali](readme.md)

## Latar belakang topik
Dalam Pemrograman Berbasis Objek, setelah membuat `Class`, maka nantinya kita bisa menghasilkan `Object`. Proses menghasilkan / mencetak `objek` dari `class` ini disebut dengan `Instansiasi (Instantiation)`. Biasanya setelah melakukan instansiasi inilah kita bisa melakukan eksekusi pada object, misalnya dengan menggunakan setter (set_name, etc) lalu getter (get_name, etc). Namun terdapat cara dimana ketika melakukan instansiasi maka setelahnya tidak perlu melakukan setter ataupun getter untuk mengeksekusi, karena saat di instansiasi, object akan langsung dieksekusi caranya ialah dengan menggunakan fungsi `Constructor`. Selain constructor, terdapat yang namanya fungsi `Destructor` yang sifatnya langsung dijalankan jika semua kondisi yang diinginkan telah terpenuhi.

## Konsep-konsep

`Constructor` ialah fungsi khusus dimana dia akan mengeksekusi object pada saat object di instansiasi, sedangkan `Destructor` merupakan kebalikan dari Constructor, fungsi ini akan berjalan ketika semua kondisi yang diinginkan telah berjalan (fungsi yang berjalan terakhir). `Constructor` dan `Destructor` tidak harus ada didalam suatu Class. Method constructor biasanya berisi pemberian nilai default dari masing-masing properties (variabel). Untuk membuat constructor, cukup dengan mendefinisikan suatu fungsi dengan nama `__construct()`. Begitupun dengan destructor, fungsi ini bisa didefinisikan dengan nama `__destruct()`.

## Langkah-langkah tutorial Constructor tanpa parameter

### Langkah pertama

Membuat `Class Employee` dengan atribut name, position

```php
<?php
class Employee {
        public $name;
        public $position;
}
```

### Langkah kedua

Menambahkan method construct di `Class Employee`

```php
<?php
class Employee {
        public $name;
        public $position;
        function __construct(){
            echo "\nSelamat datang di kantor!\n";
        }
}

```

### Langkah ketiga

Menambahkan method setter dan getter yang perlu di `Class Employee`

```php
<?php
class Employee {
        public $name;
        public $position;
        function __construct(){
            echo "\nSelamat datang di kantor!\n";
        }
        function set_name($name){
            $this->name = $name;
        }
        function get_name(){
            return $this->name;
        }
        function set_position($position){
            $this->position = $position;
        }
        function get_position(){
            return $this->position;
        }
    }

```

### Langkah keempat

Menambahkan method destructor di `Class Employee`

```php
<?php
class Employee {
        public $name;
        public $position;
        function __construct(){
            echo "\nSelamat datang di kantor!\n";
        }
        function set_name($name){
            $this->name = $name;
        }
        function get_name(){
            return $this->name;
        }
        function set_position($position){
            $this->position = $position;
        }
        function get_position(){
            return $this->position;
        }
        function __destruct(){
            echo "Sampai jumpa lagi!\n\n";
        }
    }

```

### Langkah kelima

Menginstansiasi object dimana akan menjalankan fungsi constructor. 

```php
$employee1 = new Employee();
```

Hasilnya 

![image](https://user-images.githubusercontent.com/80946219/118929701-0cab6680-b96f-11eb-8f0e-c69ea1772839.png)

### Langkah keenam

Memanggil fungsi set_nama dan get_nama untuk menampilkan hasilnya.

```php
$employee1 = new Employee();
$employee1->set_name("Steven");
$employee1->set_position("Manager");
echo "Karyawan ini bernama ".$employee1->get_name().", dia merupakan seorang ".$employee1->get_position().".\n";
```

Hasilnya 

![image](https://user-images.githubusercontent.com/80946219/118930544-12ee1280-b970-11eb-9c23-150c42338526.png)


## Langkah-langkah tutorial Constructor dengan parameter

### Langkah pertama

Membuat `Class Employee` dengan method construct, ketika menggunakan constructor tidak apa-apa jika tidak mendefinisikan atributnya sebelum fungsi constructnya. Jika menggunakan construct, maka kita tidak perlu lagi menggunakan set_name dan get_name.

```php
<?php
class Employee {
  //method construct didalamnya terdapat parameter name dan position
  function __construct($name, $position) { 
    $this->name = $name;
    $this->position = $position;
  }
}

```

### Langkah kedua

Menambahkan method untuk mencetak.

```php
<?php
class Employee{
  function __construct($name, $position) { 
    $this->name = $name;
    $this->position = $position
  }
  
  function cetak(){
    return "Karyawan ini bernama ".$this->name.", dia merupakan seorang ".$this->position.".";
  }
}
```

### Langkah ketiga

Menambahkan fungsi destructor yang nantinya akan berjalan ketika semua method telah dieksekusi.

```php
<?php
class Employee{
  function __construct($name, $position) { 
    $this->name = $name;
    $this->position = $position
  }
  
  function cetak(){
    return "Karyawan ini bernama ".$this->name.", dia merupakan seorang ".$this->position.".";
  }
  
  function __destruct(){
    echo "\nSampai jumpa lagi!\n\n";
  }
}
```

### Langkah keempat

Menginstansiasi object dengan parameter nama dan posisi, disini akan menjalankan fungsi constructor. Dimana disini akan langsung menyimpan steven sebagai nama dan manager sebagai posisinya. 

```php
$employee1 = new Employee("Steven", "Manager");
```

Maka output yang dihasilkan 

![keempatt](https://user-images.githubusercontent.com/80946219/118925836-95270880-b969-11eb-95ef-7685ae800df9.png)

### Langkah kelima

Memanggil fungsi cetak.

```php
$employee1 = new Employee("Steven", "Manager");
echo $employee1->cetak();
```

Output yang dihasilkan

![limaaaa](https://user-images.githubusercontent.com/80946219/118926013-d61f1d00-b969-11eb-85f5-01b7365391fc.png)
