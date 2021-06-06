# Controller, Route, & View

[Kembali](readme.md)

## Latar belakang topik

Sebagian besar project Laravel maupun package Laravel akan menambahkan *endpoint-endpoint* baru sesuai dengan fitur yang diberikan. Pada topik ini kita akan belajar cara untuk mengimplementasikan sebuah *endpoint* sederhana, yang terdiri dari *route*, *controller*, dan *view*.

## Konsep-konsep

### Controller

Pada pembuatan sebuah package, penting untuk menggunakan struktur direktori yang mirip dengan project Laravel sendiri untuk memudahkan pengembangan bagi pembuat package maupun pengguna package. Oleh karena itu, controller yang dibuat dapat diletakkan pada lokasi `src/Http/Controllers` seperti halnya controller sebuah project Laravel.

### Route

Sama halnya dengan controller, *route* yang kita buat pada package diletakkan pada direktori `routes/` dengan nama `web.php` (untuk web).

Untuk memastikan Laravel membaca *route* yang sudah kita buat, jangan lupa untuk melakukan registrasi dokumen `web.php` pada service provider dengan menggunakan fungsi `loadRoutesFrom()`.

```php
loadRoutesFrom(__DIR__.'/../routes/web.php');
```

### View

*View* yang kita buat untuk package Laravel dapat disimpan pada direktori `resources/views` dengan struktur selayaknya project Laravel umumnya.

Kita juga harus mendaftarkan *view* pada service provider dengan menggunakan fungsi `loadViewsFrom()`.

```php
loadRoutesFrom(__DIR__.'/../resources/views', 'mytag');
```

Fungsi di atas akan mendaftarkan *view* package kita dengan kategori `mytag`, yang akan digunakan pada controller untuk menampilkan view sebagai berikut.

```php
return view('mytag::directory.name.exampleview');
```

Sama halnya dengan konfigurasi, *view* dapat dilakukan *publish* dengan memasukkannya pada fungsi `publishes()` pada service provider bagian `boot()` menggunakan fungsi *helper* `resource_path()`.

```php
publishes([
    __DIR__.'/../resources/views' => resource_path('views/vendor/helloworld'),
], 'views');
```

*View* dapat kemudian diexport dengan perintah berikut.

```sh
php artisan vendor:publish --provider="Namespace\Project\MyServiceProvider" --tag="views"
```

## Langkah-langkah tutorial

Pada tutorial ini kita akan membuat sebuah *endpoint* sederhana yang menampilkan tulisan "Hello World".

### Langkah pertama

Buat sebuah *view* sederhana pada direktori `resources/views` dengan nama `helloworld.blade.php`.

```php
<h1>Hello, world!</h1>

<p>My name is {{ $name }}.</p>
```

### Langkah kedua

Buat sebuah kelas *controller* pada direktori `src/Http/Controllers` bernama `HelloController.php`

```php
<?php

namespace maximuse\HelloWorld\Http\Controllers;

use Illuminate\Routing\Controller;

class HelloController extends Controller
{
    public function show_hello()
    {
        $name = config('helloworld.name1');

        return view('helloworld::helloworld', compact('name'));
    }
}
```

*Controller* akan menampilkan nama sesuai dengan dokumen konfigurasi yang sudah dibuat pada topik sebelumnya.

### Langkah ketiga

Buat sebuah dokumen *route* pada direktori `routes` dengan nama `web.php`.

```php
<?php

use Illuminate\Support\Facades\Route;
use maximuse\HelloWorld\Http\Controllers\HelloController;

Route::get('/', [HelloController::class, 'show_hello'])->name('hello');
```

### Langkah keempat

Daftarkan *view* dan *route* yang sudah dibuat pada service provider. Untuk *route* pada package ini, kita akan membungkusnya dengan sebuah prefix yang akan disimpan pada konfigurasi dan dapat diubah pengguna.

```php
<?php

namespace maximuse\HelloWorld;

use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    ...

    public function boot()
    {
        ...

        Route::group($this->routeConfiguration(), function() {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'helloworld');
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('helloworld.prefix'),
            'middleware' => config('helloworld.middleware')
        ];
    }
}
```

Dapat diperhatikan bahwa kita mengurung fungsi bawaan `loadRoutesFrom()` dengan sebuah `Route::group()`, di mana grup tersebut memiliki prefix dan middleware sesuai dengan konfigurasi, yang akan kita buat pada langkah berikutnya.

Dapat dilihat juga bahwa *view* akan diregistrasi dengan prefix `helloworld` sehingga pada *controller* dapat dipanggil dengan prefix `helloworld::`.

### Langkah kelima

Tambahkan opsi konfigurasi `prefix` dan `middleware`.

```php
<?php

return [
    'year' => 2021,
    'name1' => 'Emmanuel Maximus',
    'name2' => 'Bryan Gautama',
    'prefix' => 'helloworld',
    'middleware' => ['web']
];
```