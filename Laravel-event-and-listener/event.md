# Topik 1

[Kembali](readme.md)

## Latar belakang topik

Dalam melakukan pemrograman, sebaiknya sebuah class hanya melakukan sebuah tugas spesifik. Namun terkadang kita sering memasukkan banyak sekali fungsi ke dalam class sehingga apabila nantinya ada bagian class yang ingin diubah, banyak bagian lain yang ikut berubah. Menggunakan event dan listener mempermudah kita mengatur agar class kita menjalankan satu tugas.

## Konsep-konsep

Event adalah sebuah peristiwa/aktivitas yang terjadi pada aplikasi kita. Event merupakan sebuah cara untuk mengetahui apabila sebuah peristiwa terjadi (sebagai trigger). Listener adalah sebuah class yang akan menunggu perintah untuk dijalankan dari event yang menugaskan listener tersebut. Sebuah event dapat memiliki lebih dari 1 listener.

## Membuat event dan listener

### Langkah pertama

Untuk menambahkan event baru beserta listener yang dimilikinya, dapat menuliskannya pada `App\Providers\EventServiceProvider`. Properti `listen` merupakan array yang berisi berbagai event (sebagai key) dan listen yang dimilikinya (sebagai value).

Misal ingin membuat event `LoginHistory` dengan listener `StoreUserLoginHistory`

```php
<?php

use App\Events\LoginHistory;
use App\Listeners\StoreUserLoginHistory;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
       
        LoginHistory::class => [
            StoreUserLoginHistory::class,
        ]
    ];
}
```

### Langkah kedua

Setelah itu generate event dan listener yang baru dibuat menggunakan perintah `php artisan event:generate` pada command line. Event akan tersedia di `app\Events` dan listener akan tersedia di `app\Listeners`

## Langkah alternatif

Terdapat cara lain untuk membuat event dan listener tanpa perlu menuliskannya di `App\Providers\EventServiceProvider` yaitu menggunakan perintah `php artisan make`

```
php artisan make:event LoginHistory
php artisan make:listener StoreUserLoginHistory --event=LoginHistory
```

## Langkah-langkah tutorial

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