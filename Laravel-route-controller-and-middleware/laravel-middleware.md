# Laravel Middleware

[Kembali](readme.md)

## Introduction

Middleware menyediakan mekanisme yang mudah untuk memeriksa dan memfilter permintaan HTTP yang memasuki aplikasi kita. Misalnya, Laravel menyertakan middleware yang memverifikasi bahwa pengguna aplikasi kita telah diautentikasi. Jika pengguna tidak diautentikasi, middleware akan mengarahkan pengguna ke layar login aplikasi. Namun, jika pengguna diautentikasi, middleware akan mengizinkan permintaan untuk melanjutkan lebih jauh ke dalam aplikasi. Sesuai namanya ‘middle’ yang berarti tengah, maka letak Middleware adalah berada di tengah antara controller dan router. Ada pula yang mengartikan Middleware adalah software yang menengahi sebuah aplikasi dengan yang lain. Dengan begini, proses integrasi antar aplikasi dapat berjalan dengan lebih mudah. Semua middleware ini terletak di app/Http/Middlewaredirektori.


## Konsep-konsep

Misal: jelaskan mengenai pengertian, konsep, alur, dll.

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
