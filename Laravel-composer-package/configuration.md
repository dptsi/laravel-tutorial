# Configuration

[Kembali](readme.md)

## Latar belakang topik

Pada pembuatan suatu package untuk Laravel, kita dapat menggunakan configuration untuk menyimpan nilai-nilai yang penting namun membebaskan pengembang yang mengimplementasikan package kita untuk mengubah. Pada topik ini kita akan membahas bagaimana cara membuat sebuah file konfigurasi yang dapat dibaca oleh package. Konfigurasi ini kemudian dapat di-generate secara otomatis dengan bantuan Laravel.

## Konsep-konsep

### Laravel Configuration

Laravel sudah menyediakan sebuah [framework konfigurasi](https://laravel.com/docs/8.x/configuration) untuk mengimplementasikan sebuah file konfigurasi yang dimaksudkan untuk memudahkan pengubahan setingan.

Untuk mengakses nilai-nilai yang sudah kita definiskan pada konfigurasi, kita dapat menggunakan fungsi *helper* yang disediakan oleh Laravel sendiri, yaitu `config()`.

### Struktur Configuration

Sebuah dokumen konfigurasi umumnya diletakkan pada direktori `/config`, yang hanya merupakan sebuah file PHP yang me-*return* sebuah [associative array](https://www.php.net/manual/en/language.types.array.php).

Contoh:

```php
<?php

return [
    'key_one' => 'value_one',
    'key_two' => 2,
    ...
];
```

### Registrasi Configuration

Sebuah dokumen konfigurasi perlu kita registrasikan terlebih dahulu sebelum nilai-nilai pada konfigurasi tersebut dapat digunakan. Untuk itu, registrasi sebuah konfigurasi dapat dilakukan pada method `register()` pada service provider, dengan bantuan method `mergeConfigFrom()` sebagai berikut.

```php
mergeConfigFrom(__DIR__.'/../config/config.php', 'hello_world');
```

Method tersebut akan mendaftarkan semua nilai-nilai konfigurasi di bawah kategori `hello_world` (parameter kedua). Sehingga, untuk mengambil nilai konfigurasi, cukup menggunakan `config()`.

```php
config('hello_world.key_two '); # 2
```

### Export Configuration

Karena dokumen konfigurasi ini terletak di dalam kode sumber package kita (di dalam direktori `/vendor` pemilik project yang menggunakan package kita), maka kita harus melakukan *publish* konfigurasi dengan menggunakan fungsi `publishes()` pada service provider.

```php
publishes([
    __DIR__.'/../config/config.php' => config_path('helloworld.php');
], 'config');
```

Perhatikan bahwa parameter pertama dari method tersebut memiliki value yang menggunakan fungsi *helper* laravel `config_path()`. Method di atas akan memberikan cara bagi pengguna untuk melakukan *publish* pada direktori konfigurasi pengguna, dengan menggunakan *tag* `config` (parameter kedua).

Setelah mendaftarkan *publishing*, konfigurasi dapat diexport dengan command berikut.

```sh
php artisan vendor:publish --provider="Namespace\Project\MyServiceProvider" --tag="config"
```

## Langkah-langkah tutorial

Tutorial ini akan menjelaskan cara untuk membuat sebuah file konfigurasi sederhana, kemudian mendaftarkannya supaya dapat digunakan dan dapat diexport ke project Laravel pengguna.

### Langkah pertama

Buat sebuah kelas konfigurasi `config.php` pada direktori `config`

```php
<?php

return [
    'year' => 2021,
    'name1' => 'Emmanuel Maximus',
    'name2' => 'Bryan Gautama'
];
```

### Langkah kedua

Registrasikan dokumen konfigurasi yang kita buat pada langkah sebelumnya, melalui method `register()` pada service provider.

```php
<?php

class CalculatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        ...

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'helloworld');
    }

    ...
}

```

### Langkah ketiga

Registrasikan *publishing* konfigurasi pada service provider sehingga Artisan dapat melakukan publish dengan menggunakan `vendor:publish`.

```php
<?php

namespace maximuse\HelloWorld;

use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    ...

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('helloworld.php')
            ], 'config');
        }
    }
}
```

Untuk memastikan bahwa konfigurasi hanya dapat diexport ketika *user* menggunakan *command line* saja, kita menerapkan fungsi `app->runningInConsole()`.

Pengguna package ini akan dapat melakukan export dengan menggunakan command berikut.

```sh
php artisan vendor:publish --provider="maximuse\HelloWorld\CalculatorServiceProvider" --tag="config"
```