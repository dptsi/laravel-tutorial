# Namespace

[Kembali](readme.md)

## Latar belakang topik

Secara umum, namespace adalah salah satu cara enkapsulasi item. Sebagai contoh, dalam sistem operasi terdapat directory yang berfungsi untuk mengelompokkan file-file dan directory tersebut berfungsi sebagai namespace dari file-file yang ada di dalamnya. Misal terdapat file `note.txt`, file tersebut dapat berada dalam directory `/home/dan` dan `/home/dan/Downloads` secara bersamaan, tetapi dua file `note.txt` tidak dapat berada dalam directory yang sama. Untuk mengakses file `note.txt` di luar directory `/home/dan`, maka nama directory harus digabungkan dengan nama file menjadi `/home/dan/note.txt`. Sama halnya dengan namespace dalam PHP.

## Konsep-konsep

Namespace adalah area deklaratif yang menyediakan scope dari identifier (nama fungsi, variabel, class, dll) yang ada di dalamnya.

Dalam PHP, namespace digunakan untuk:
1. mencegah name collision dari class, functions, constants, third-party clasess, third-party functions, atau third-party constants
2. menyingkat nama yang panjang dan meningkatkan readability dari kode

## Langkah-langkah tutorial

Berikut adalah langkah-langkah menggunakan `namespace` di PHP.

### Langkah 1

Dalam file `myClass.php`, buat sebuah kelas bernama `myClass`.

```php
<?php
//myClass.php
class myClass
{
    
}
```

### Langkah 2

Tambahkan fungsi `myFunction()` dalam class `myClass` yang bertujuan untuk mencetak "Hello World".

```php
<?php
//myClass.php
class myClass
{
    public function myFunction() {
        echo "Hello World";
    }
}
```

### Langkah 3

Tambahkan `namespace Dan\Tools` di atas class `myClass`. Pada tahap ini, kelas `myClass` diletakkan pada namespace `Dan\Tools`.

```php
<?php
//myClass.php

namespace Dan\Tools;

class myClass
{
    public function myFunction() {
        echo "Hello World";
    }
}
```

### Langkah 4

Dalam file `main.php`, untuk membuat objek dari class `myClass`, tambahkan namespace `\Dan\Tools` sebelum nama class. Hal ini sama seperti menggunakan absolute path untuk mengakses file dalam file system, contoh: `ls /home/dan`.

```php
<?php
//main.php
require 'myClass.php';

// buat objek dari class myClass
$myObject = new \Dan\Tools\myClass();

// coba fungsi myFunction()
echo $myObject->myFunction();
```

### Langkah 5

Untuk menyingkat namespace, gunakan statement `use` di atas file. Tambahkan `use \Dan\Tools\myClass as theClass`

```php
<?php
//main.php
require 'myClass.php';

use \Dan\Tools\myClass as theClass;

...
```

### Langkah 6

Selanjutnya, `theClass` akan merefer ke `\Dan\Tools\myClass`.

```php
<?php
//main.php
require 'myClass.php';

use \Dan\Tools\myClass as theClass;

// buat objek dari class myClass
$myObject = new theClass();

// coba fungsi myFunction()
echo $myObject->myFunction();
```

**Output:**
```
Hello World
```

### Langkah 7

Semua kelas dalam PHP modern berada dalam suatu namespace, kecuali core class PHP. Jika class sudah berada dalam suatu namespace, untuk mengakses core class PHP dalam class tersebut tambahkan `\` sebelum nama dari core class PHP. Pada contoh ini, digunakan class `DateTime()` yang merupakan core class PHP.

```php
<?php
//myClass.php

namespace Dan\Tools;

class myClass
{
    public function myFunction() {
        echo "Hello World\n";
        $x = new \DateTime();
        echo $x->getTimestamp();
    }
}
```

```php
<?php
//main.php
require 'myClass.php';

use \Dan\Tools\myClass as theClass;

// buat objek dari class myClass
$myObject = new theClass();

// coba fungsi myFunction()
echo $myObject->myFunction();
```

**Output:**
```
Hello World
1621489149
```

Jika tidak ingin menambahkan `\`, maka dapat menggunakan keyword `use`. Tambahkan `use DateTime`.

```php
<?php
//myClass.php

namespace Dan\Tools;

use DateTime;

class myClass
{
    public function myFunction() {
        echo "Hello World\n";
        $x = new DateTime();
        echo $x->getTimestamp();
    }
}
```

```php
<?php
//main.php
require 'myClass.php';

use \Dan\Tools\myClass as theClass;

// buat objek dari class myClass
$myObject = new theClass();

// coba fungsi myFunction()
echo $myObject->myFunction();
```

**Output:**
```
Hello World
1621489149
```

## Referensi
1. https://www.php.net/manual/en/language.namespaces.rationale.php
2. https://symfonycasts.com/screencast/php-namespaces/namespaces
