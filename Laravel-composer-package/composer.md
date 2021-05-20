# Composer

[Kembali](readme.md)

## Latar belakang topik

Sebelum masuk ke pembahasan membuat package Laravel, ada beberapa konsep yang perlu dipelajari terlebih dahulu, terutama terkait dengan package. Apa itu package, apa arti dari mengembangkan sebuah "Laravel package"?

Kebutuhan untuk didirikannya suatu package management dimulai dari semakin rumit dan besarnya aplikasi yang dibuat oleh software developer. Dengan adanya package yang bisa digunakan sebagai dependency atau prasyarat, maka seorang developer tidak perlu membuat ulang fungsionalitas yang sudah pernah dibuat oleh orang lain secara terstruktur:

> *"Don't reinvent the wheel."*

Dasar-dasar dari metode distribusi package di PHP akan menjadi dasar pemahaman untuk membentuk sebuah package dengan bahasa pemrograman PHP, bahkan di luar bahasan kerangka kerja Laravel.

## Konsep-konsep

### Pengertian Composer

Composer merupakan package manager untuk bahasa pemrograman PHP. Dengan adanya Composer, maka pengembangan dan penggunaan package PHP dapat distandardisasi sehingga mengikuti format yang sama.

Contoh package manager dengan repository paling umum pada bahasa pemrograman lain:

 | Bahasa Pemrograman|Package Manager|Package Repository|
 | -|-|-|
 | PHP|Composer|Packagist|
 | Python|pip|Python Package Index (PyPI)|
 | JavaScript|Node Package Manager (npm)|npm repository|
 | Ruby|Ruby Bundler|RubyGems|

### Struktur umum package Composer

```yaml
- src/ # Subfolder berisi kode program
- tests/ # Subfolder untuk semua kebutuhan testing
- composer.json # Konfigurasi package Composer
```

### Syntax `composer.json`

File `composer.json` berisi semua konfigurasi yang dibutuhkan untuk package yang kita buat, dalam [notasi JSON](https://www.json.org/json-en.html). Secara lengkapnya, opsi pengaturan yang bisa dimasukkan pada file ini dapat dilihat di [dokumentasi resmi Composer](https://getcomposer.org/doc/04-schema.md).

Adapun beberapa elemen yang paling sering digunakan sebagai berikut:

| Nama|Keterangan|
| -|-|
| `name`|Nama package|
| `description`|Deskripsi package|
| `type`|Tipe package, umumnya library atau project.|
| `authors`|Pengembang package|
| `require`|Berisi package-package lain yang dibutuhkan package ini.|
| `require-dev`|Sama seperti `require` namun berlaku untuk lingkungan pengembangan. Umumnya berisi library testing seperti PHPUnit.|
| `autoload`|Untuk [autoloading](https://www.php.net/autoload) kode sumber oleh PHP saat runtime.|
| `autoload-dev`|Sama seperti `autoload` namun berlaku untuk lingkungan pengembangan. Umumnya package testing dilakukan autoloading di bagian ini.|
| `scripts`|Untuk menjalankan perintah tertentu atau memanggil fungsi PHP pada tahap tertentu instalasi/penggunaan.|
| `extra`|Data tambahan yang mungkin dibutuhkan oleh perintah pada `scripts`.|


## Langkah-langkah tutorial

Untuk tutorial kita akan mencoba membuat sebuah package composer dan men-publish package tersebut pada Packagist. Kemudian, kita akan melakukan testing dengan membuat sebuah project dengan dependency package itu.

Hasil akhir kode dapat dilihat pada repository berikut:
- [hello-composer](https://github.com/1Maximuse/hello-composer)
- [test-hello-composer](https://github.com/1Maximuse/test-hello-composer)

### Langkah pertama

Silakan mendaftar terlebih dahulu pada [Packagist](https://packagist.org/). Pastikan juga anda sudah memiliki akun repository git public (mis. [GitHub](https://github.com/)).

Buat sebuah folder sebagai root dari package yang ingin dibuat, dengan struktur direktori seperti [di atas](#Struktur-umum-package-Composer).

### Langkah kedua

Buat sebuah file bernama `composer.json` dan isikan informasi dasar dari package:

```json
{
    "name": "1maximuse/hello-composer",
    "description": "Simple hello world Composer package.",
    "type": "library",
    "authors": [
        {
            "name": "Emmanuel Maximus",
            "email": "geofanny.emmanuel@gmail.com"
        }
    ],
    "license": "MIT",
    "require": {}
}
```

Pastikan bagian `name` sesuai dengan nama akun Packagist kalian.

### Langkah ketiga

Kita dapat memulai membuat aplikasi kita pada subfolder `/src`.

`src/HelloWorld.php`:

```php
<?php

namespace maximuse\HelloWorld;

class HelloWorld {
    public function hello() {
        return "Hello, world!";
    }
}

?>
```

### Langkah keempat

Setelah membuat sebuah kode PHP, agar kode ini dapat diakses oleh project lain yang nantinya akan menggunakan library kita, maka harus didaftarkan untuk autoloading pada `composer.json`:

```json
{
    "name": "1maximuse/hello-composer",
    "description": "Simple hello world Composer package.",
    "type": "library",
    "authors": [
        {
            "name": "Emmanuel Maximus",
            "email": "geofanny.emmanuel@gmail.com"
        }
    ],
    "license": "MIT",
    "require": {},
    "autoload": {
        "psr-4": {
            "maximuse\\HelloWorld\\": "src/"
        }
    }
}
```

Tambahan di atas menandakan bahwa folder `/src` diberikan namespace yaitu `maximuse/HelloWorld`. Sebagai contoh, kelas HelloWorld yang sudah kita buat tadi dapat kita import pada project lain dengan menggunakan baris berikut:

```php
use maximuse\HelloWorld\HelloWorld;
```

Perhatikan bahwa ini boleh beda dengan `name`, karena `name` hanyalah nama package dan tidak berkaitan dengan namespace.

### Langkah kelima

Inisialisasikan project yang sudah kita buat menjadi suatu repository Git, kemudian push ke GitHub:

```
git init
git remote add origin <url>
git add .
git commit -m "initial commit"
git push -u origin master
```

Setelah itu, kita dapat men-publish repository ini menjadi package public pada Packagist. Setelah login ke akun Packagist, masukkan URL repository yang sudah dibuat ke bagian [Submit](https://packagist.org/packages/submit).

Apabila semua langkah sudah diikuti dengan baik, maka package anda akan terpublish dan untuk menggunakan dapat dengan perintah berikut pada project baru, tentunya dengan username sesuai dengan akun masing-masing:

```
composer require 1maximuse/hello-composer
```

### Langkah keenam

Untuk menguji package yang sudah kita publish, kita dapat membuat project Composer baru di folder lain.

Pada folder baru ini, buatlah sebuah file `composer.json` yang menandakan bahwa folder ini adalah suatu project Composer.

`composer.json` seminimalnya hanya merupakan JSON object kosong saja:

```json
{}
```

Namun apabila kita lakukan perintah `composer require 1maximuse/hello-composer` akan muncul error karena kita berusaha memasukkan package yang statusnya `dev` pada project.

Maka, kita perlu menambahkan keterangan bahwa project kita diperbolehkan menggunakan package versi `dev`:

```json
{
    "minimum-stability": "dev"
}
```

Setelah menambahkan opsi di atas maka kita dapat menambahkan dependensi package dengan command berikut, tentunya menyesuaikan dengan username akun Packagist masing-masing:

```
composer require 1maximuse/hello-composer
```

Setelah perintah tersebut dijalankan, dapat dilihat bahwa `composer.json` kita secara otomatis bertambah pada bagian `requires`:

```json
{
	"minimum-stability": "dev",
    "require": {
		"1maximuse/hello-composer": "9999999-dev"
	}
}
```

Selain itu, Composer juga membuat folder baru yaitu `/vendor` yang berisi package kita tadi serta kode bantuan untuk autoloading.

### Langkah ketujuh

Buatlah sebuah file php bebas, semisal `index.php`. Saya akan membuat file ini di dalam folder `/src` agar lebih rapi.

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use maximuse\HelloWorld\HelloWorld;

$hello_world = new HelloWorld();

echo $hello_world->hello();

?>
```

Dapat diperhatikan bahwa sebelum menggunakan semua kelas-kelas pada package dependency, diperlukan melakukan autoloading terlebih dahulu dengan menggunakan:

```php
require_once __DIR__ . '/../vendor/autoload.php';
```

### Langkah kedelapan

Untuk melakukan pengujian, gunakan sebuah terminal untuk menjalankan kode php tersebut:

```
php ./src/index.php
```