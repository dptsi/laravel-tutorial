# PHP Strict Type

[Kembali](readme.md)

## Latar belakang topik

Secara default, bahasa PHP adalah bahasa pemrograman yang bersifat weakly typed. Weakly typed disini bermakna bahasa PHP akan mengkonversi tipe data secara implisit selama dapat dikonversi.

Sebagai contoh :

```php
<?php
    function tambah(int $a, int $b) {
        return $a + $b;
    };
    var_dump(tambah(9, '5.3'));
?>
```

Meskipun telah menggunakan type hinting, namun potongan kode tersebut akan mengeluarkan output berupa `int` dengan nilai `14` tanpa mengeluarkan error apapun. Hal ini tentu menimbulkan masalah apabila programmer tidak menyadari ini. Bisa saja programmer berekspektasi output berupa 14.3 (`float`) namun yang keluar tidak seperti itu. Efek samping dari weakly typed ini membuat software yang dibuat berpotensi menimbulkan bug yang tidak disadari.

Oleh karena itu, PHP strict type akan membuat programmer lebih disiplin dan meminimalisir hasil yang tidak diekspektasikan karena konsep ini akan memberikan `TypeError` ketika tipe data dari variabel yang dimasukkan tidak sesuai dengan ketentuan.

## Konsep-konsep

Strict typing pada PHP akan membuat setiap variabel yang dipassing ke fungsi, di assign ke variabel lain, dan sejenisnya selalu dicek tipe datanya. Apabila berbeda tipe data, maka PHP akan throw `TypeError` saat program akan dijalankan.

```php
<?php
    function tambah(int $a, int $b) {
        return $a + $b;
    };
    var_dump(tambah(9, '5.3'));
?>
```

Pada potongan kode diatas, fungsi `tambah()` akan melakukan casting `'5.3'` menjadi `int` bernilai `5`. Sehingga ketika kode dijalankan, `var_dump()` akan menampilkan nilai `14` berupa `int`. Namun, apabila strict typing dinyalakan. Potongan kode tersebut akan menampilkan error sehingga tidak dapat dieksekusi.

## Langkah-langkah tutorial

### Langkah pertama

#### Buat fungsi `Tambah()`

```php
<?php
    function tambah(int $a, int $b) {
        return $a + $b;
    };
    var_dump(tambah(9, '5.3'));
?>
```

### Langkah kedua

#### Tambahkan `declare(strict_types = 1);` di awal kode program

Kemudian, program akan menjadi seperti berikut : 

```php
<?php
    declare(strict_types = 1);
    function tambah(int $a, int $b) {
        return $a + $b;
    };
    var_dump(tambah(9.99, '5'));
?>
```

### Hasil

Apabila berhasil, maka kode editor ataupun IDE akan menampilkan error seperti berikut:

![Contoh TypeError](https://media.discordapp.net/attachments/798177440425181256/842801651341721670/unknown.png)

Jika halaman web dibuka, maka pesan error seperti ini akan ditampilkan:
![TypeError di browser](https://media.discordapp.net/attachments/798177440425181256/842802061381074944/unknown.png)

### Kesimpulan

Walaupun cenderung lebih susah dalam penulisan, namun strict typing akan mempermudah programmer dalam melacak hasil yang tidak sesuai ekspektasi dan memudahkan untuk membuat self-documenting code (Kode yang tidak perlu dijelaskan alurnya dalam dokumentasi, namun bisa langsung dipahami hanya dengan melihat kodenya).
