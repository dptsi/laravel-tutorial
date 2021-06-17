# Unit Testing

[Kembali](readme.md)

## Latar belakang Unit Testing

Dilansir dari [Wikipedia](https://en.wikipedia.org/wiki/Software_testing#Black-box_testing), Testing pada software dilakukan untuk memberikan informasi kepada
stakeholder / developer tentang bagaimana kualitas software yang tengah dikembangkan. Testing dilakukan mencakup proses menjalankan software untuk menemukan kegagalan dan memverifikasi bahwa software itu layak digunakan. Banyak sekali jenis - jenis testing, dilihat dari "Pendekatan Testing" :

- Static, Dynamic, Passive Testing
- Explotary Approach
- White-box Testing
- Black-box Testing
- etc

Jika dilihat dari "Level Testing" :

- Unit Testing
- Integration Testing
- System Testing
- etc

Jika dilihat dari "Teknik Testing" :

- A/B Testing
- Concurrent Testing
- Usability Testing
- etc

## Konsep-konsep

Apa itu Unit Testing ? Unit testing adalah cara pengujian software dimana suatu unit individu pada suatu modul ditest untuk dicek apakah unit tersebut bisa digunakan atau tidak. Unit testing biasanya ditulis oleh Software Developer untuk memastikan unit dari modul tersebut bekerja sesuai dengan yang kita harapkan. Pada paradigma OOP, suatu unit diartikan sebagai suatu `class` atau suatu `method`.

Dengan menulis test terlebih dahulu pada unit terkecil, lalu kita cek `behavior` unit tersebut, kita dapat menulis tes untuk aplikasi yang lebih kompleks lagi.

Pada unit test, kita biasanya menggunakan `mock object`, `method stubs` untuk membantu kita melakukan test pada modul yang terisolasi. Terdapat juga library yang dapat membantu testing, seperti `JUnit` pada Java, internal lib `testing` pada Go, `cypress` pada Javascript, dll.

Terdapat juga suatu proses pengembangan software yang bernama [Test-driven Development](https://en.wikipedia.org/wiki/Test-driven_development) / TDD, dimana kita membuat banyak testcases dan menguji test cases itu ke suatu unit. Jika terdapat test yang `fail`, maka akan dianggap sebagai bug atau potential problem dan kita sebagai developer harus memperbaiki kode kita untuk membuat test tersebut `pass`

## Langkah-langkah tutorial

Pertama - tama, kita akan membuat environment testing dengan nama file `.env.testing`. Untuk tujuan dari file ini akan diberitahu lebih lanjut nantinya.

Untuk membuat Unit Testing di Laravel, dapat menggunakan perintah berikut:

```
php artisan make:test UserTest --unit
```

Lalu masukkan kode dibawah ini didalam file UserTest yang telah dibuat.

```
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_basic_test()
    {
        $this->assertTrue(true);
    }
}
```

Untuk menjalankan test file ini, dapat menjalankan perintah

```
php artisan test
```
