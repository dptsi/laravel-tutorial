# Orchestra Testbench & Testing

[Kembali](readme.md)

## Latar belakang topik

Dkalam pembuatan sebuah package Laravel kita akan membutuhkan akses ke komponen-komponen bawaan dari Laravel (Illuminate). Kita tidak menggunakan project Laravel secara langsung sebagai dependency, oleh karena itu kita dapat mengggunakan sebuah package untuk membantu, [Orchestra Testbench](https://github.com/orchestral/testbench).

## Konsep-konsep

### Orchestra Testbench

Testbench adalah sebuah package Composer dengan tujuan utama untuk mendukung proses testing dari pengembangan package Laravel.

Pada Testbench terdapat dua direktori yang penting untuk diketahui, `/laravel` dan `/src`. Apabila diperhatikan, direktori `/laravel` memuat struktur yang sama dengan sebuah project Laravel. Ini yang akan kita gunakan untuk membantu mengembangkan package Laravel. Direktori `/src` berisi semua modul dan peralatan yang dapat digunakan untuk melakukan testing.

Untuk keperluan testing, kita akan *extend* kelas `TestCase` milik Testbench, yang akan dibaca oleh Testbench. Untuk setiap kelas `TestCase`, Testbench akan menjalankan sebuah aplikasi Laravel dengan menggunakan namespace yang diarahkan ke direktori `/laravel` tadi.

### Kompatibilitas

Setiap versi Testbench dibuat untuk versi Laravel yang berbeda, seperti tabel berikut.

| Laravel | Orchestra Testbench |
| -|-|
| 8.x | 6.x |
| 7.x | 5.x |
| 6.x | 4.x |

Selengkapnya dapat dilihat di [dokumentasi Orchestra Testbench](https://packages.tools/testbench/getting-started/introduction.html#version-compatibility).

### Testing

Untuk unit/feature testing sendiri akan dibantu dengan package `PHPUnit`, yang akan didemonstrasikan pada tutorial. Struktur direktori untuk testing umumnya sebagai berikut:

```yaml
- src/
  - HelloWorld.php # kode sumber package
- tests/
  - Feature/ # feature test
  - Unit/ # unit test
  - TestCase.php # berisi pengaturan tambahan dari testing seperti setting up
```

`TestCase.php` akan mengimplementasikan beberapa fungsi berikut dari `\Orchestra\Testbench\Testcase`:
* `getPackageProviders()`: untuk mempersiapkan service provider yang kita punya
* `getEnvironmentSetup()`: dijalankan di tahap awal jalannya aplikasi
* `setUp()`: fungsi yang dipanggil sebelum setiap test

## Langkah-langkah tutorial

Untuk tutorial kita akan membuat *development environment* untuk membuat sebuah package Laravel 8.x. Jadi, kita akan menggunakan Testbench 6.x.

Kita akan langsung meneruskan implementasi dari tutorial topik sebelumnya.

### Langkah pertama

Tambahkan Testbench dan PHPUnit sebagai kebutuhan pada project `hello-composer` kita:

```sh
composer require --dev "orchestra/testbench=^6.0"
composer require --dev "phpunit/phpunit"
```

`--dev` digunakan supaya package Testbench hanya dijadikan kebutuhan pada lingkungan pengembangan saja (bagian `require-dev` pada `composer.json`). `"orchestra/testbench=^6.0"` menandakan bahwa kita ingin menggunakan Testbench dengan *major version* versi 6.

Composer akan mengubah isi dari `composer.json` dan menambahkan Testbench pada bagian `require-rev`:

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
    },
    "require-dev": {
        "orchestra/testbench": "6.0",
        "phpunit/phpunit": "^9.5"
    }
}
```

### Langkah kedua

Buatlah sebuah file konfigurasi PHPUnit, bernama `phpunit.xml` pada root folder.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    bootstrap="vendor/autoload.php"
    backupGlobals="false"
    backupStaticAttributes="false"
    colors="true"
    verbose="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true"
    processIsolation="false"
    stopOnFailure="false"
    xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd"
>
    <coverage>
        <include>
            <directory suffix=".php">src/</directory>
        </include>
    </coverage>
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="DB_CONNECTION" value="testing"/>
        <env name="APP_KEY" value="base64:2fl+Ktvkfl+Fuz4Qp/A75G2RTiWVA/ZoKZvp6fiiM10="/>
    </php>
</phpunit>
```

Perhatikan bahwa terdapat dua *environment variable* yang akan dibuat oleh PHPUnit:
* DB_CONNECTION, untuk mengatur database yang digunakan pada saat testing
* APP_KEY, untuk semua keperluan [enkripsi Laravel](https://laravel.com/docs/8.x/encryption). Dapat dilewati apabila tidak ada testing yang terkait dengan fitur ini.

### Langkah ketiga

Kita akan mengimplementasikan `TestCase.php`.

```php
<?php

namespace maximuse\HelloWorld\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        # Set-up tambahan seperti inisialisasi Model.
    }

    protected function getPackageProviders($app)
    {
        return [
            # CustomServiceProvider.class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        # Implementasi setting-up environment
    }
}

?>
```

### Langkah keempat

Setelah membuat kerangka untuk testing, pastikan untuk menambahkannya pada `composer.json` agar dapat dilakukan *autoloading*.

```json
{
    ...

    "autoload-dev": {
        "psr-4": {
            "maximuse\\HelloWorld\\Tests\\": "tests/"
        }
    }
}
```

### Langkah kelima

Lakukan command berikut untuk men-*generate* ulang *autoloading*:

```sh
composer dump-autoload
```