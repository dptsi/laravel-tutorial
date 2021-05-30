# *Type Hinting*

[Kembali](readme.md)

## Latar belakang topik

PHP merupakan bahasa pemrograman yang *loosely typed* dimana *programmer* tidak perlu menentukan tipe data yang akan digunakan dalam mendeklarasikan sebuah variabel.

*Loosely typed* memudahkan *programmer* dalam hal fleksibilitas karena sebuah variabel juga dapat berubah-ubah tipe data pada saat *runtime*. Namun, terdapat kelemahan dalam bahasa pemrograman ini, yaitu kita terkadang tidak tahu harus *passing* variabel dengan tipe data apa ke sebuah fungsi / ke variabel lain.

*Type hinting* merupakan sebuah cara agar kita dapat memberikan petunjuk mengenai tipe data variabel secara statis, sehingga saat membutuhkan informasi mengenai tipe data variabelnya, *programmer* akan mendapatkan petunjuk dari IDE yang digunakan.

## Konsep-konsep

Konsep dari *type hinting* cukup sederhana, programmer hanya perlu untuk menambahkan tipe data dari variabel yang akan dideklarasikan.

## Langkah-langkah tutorial

### Langkah pertama

#### Buat fungsi `tambah()`

```php
<?php

function tambah($a, $b) {
    return $a + $b;
};

var_dump(tambah(5, 9));
```

### Langkah kedua

#### Tambahkan tipe data parameter sebelum setiap parameter dan sebelum buka kurung kurawal yang menandakan tipe data yang dikembalikan oleh fungsi.

```php
<?php

function tambah(int $a, int $b): int {
    return $a + $b;
};

var_dump(tambah(5, 9));
```

### Hasil

Sebelum ditambahkan *type hinting*, teks editor hanya menampilkan informasi sebagai berikut:

![No type hinting](https://cdn.discordapp.com/attachments/798177440425181256/842809315274653706/unknown.png)

Setelah menggunakan *type hinting*, teks editor menampilkan informasi sebagai berikut:

![With type hinting](https://cdn.discordapp.com/attachments/798177440425181256/842810155167121458/unknown.png)

### Kesimpulan

Informasi yang ditampilkan oleh teks editor menjadi lebih jelas dan memberikan programmer penjelasan tentang variabel dengan tipe apa yang harus dipassing ke fungsi tersebut.
