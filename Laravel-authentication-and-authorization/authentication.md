# Authentication

[Kembali](readme.md)

## Latar belakang topik

Banyak aplikasi web yang menyediakan cara untuk mengautentikasi penggunanya melalui *login*. Fitur ini cukup kompleks dan beresiko untuk diimplementasikan ke dalam aplikasi web. Maka dari itu, Laravel menyediakan *tools* yang dibutuhkanm untuk mengimplementasikan autentikasi dengan cepat, aman, dan mudah.

## Konsep-konsep

Pada dasarnya, fasilitas autentikasi pada Laravel terdiri dari `guards` dan `providers`. `Guards` menentukan bagaimana pengguna diautentikasi untuk tiap request. Contohnya adalah session guard yang menjaga state menggunakan session storage dan cookies.

`Providers` menentukan bagaimana pengguna diambil dari penyimpanan persisten. Laravel mendukung pengambilan data pengguna menggunakan *Eloquent* dan database query builder. Namun kita bisa menentukan provider tambahan yang dibutuhkan dalam aplikasi yang dibangun.

Konfigurasi autentikasi pada aplikasi web yang dibangun terletak pada `config/auth.php`. File ini berisi beberapa opsi untuk mengubah perilaku autentikasi dari Laravel.

## Langkah-langkah tutorial

### Langkah pertama

Misal: Buat class `Contoh`

```php
<?php


namespace DummyNamespace;


class Contoh
{
    public function fungsi($request)
    {
        ...
    }

}
```

### Langkah kedua
