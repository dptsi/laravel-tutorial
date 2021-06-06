# Facade

[Kembali](readme.md)

## Latar belakang topik

Facade adalah hal yang cukup sering kita implementasikan pada Laravel. Pada sebuah package, penggunaan Facade dapat memudahkan user untuk mengakses sebuah class, karena facade membuat interface menjadi lebih mudah dibaca.

## Konsep-konsep

Facade sendiri merupakan sebuah kelas yang menyediakan akses ke objek dari container. Implementasi dari Facade pada composer sendiri tidak begitu berbeda dengan implementasi pada aplikasi Laravel.

## Langkah-langkah tutorial

Pada tutorial ini, kita akan mencoba untuk membuat kelas kalkulator untuk package kita dan kita akan membuat kelas ini sebagai sebuah Facade.

### Langkah pertama

Kita buat kelas `Calculator.php` di direktori `/src` yang memiliki method  `add()`, `subtract()`, dan `clear()`. Semua method nantinya akan mereturn objectnya sendiri agar kita dapat menggunakannya secara *functional* (chaining method seperti `->add()->subtract->result()`)

```php
<?php

namespace maximuse\HelloWorld;

class Calculator
{
    private $result;

    public function __construct()
    {
        $this->result = 0;
    }

    public function add(int $value)
    {
        $this->result += $value;
        return $this;
    }

    public function subtract(int $value)
    {
        $this->result -= $value;
        return $this;
    }

    public function clear()
    {
      $this->result = 0;
      return $this;
    }

    public function getResult()
    {
        return $this->result;
    }
}
```

### Langkah kedua

Langkah kedua adalah kita membuat sebuah kelas facade yang mengextends kelas `Illuminate\Support\Facades\Facade` pada direktori `src/Facades`.

```php
<?php

namespace maximuse\HelloWorld\Facades;

use Illuminate\Support\Facades\Facade;

class Calculator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'calculator';
    }
}
```

### Langkah ketiga

Langkah ketiga adalah kita register binding service container pada service provider kita.

```php
<?php

namespace maximuse\HelloWorld;

use Illuminate\Support\ServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('calculator', function ($app) {
            return new Calculator();
        });
    }

    ...
}
```
Sekarang, kita bisa mengakses facade calculator dengan mengimport namespace `use maximuse\HelloWorld\Facades\Calculator;`. 

### Langkah keempat

Laravel juga memungkinkan kita untuk mendaftarkan alias yang dapat meregister facade di root namespace :O. Kita dapat mendefine alias tersebut pada file `composer.json` kita seperti berikut.

```json
{
    ...

    "extra": {
        "laravel": {
            "providers": [
                "maximuse\\HelloWorld\\CalculatorServiceProvider"
            ],
            "aliases": {
                "Calculator": "maximuse\\HelloWorld\\Facades\\Calculator"
            }
        }
    }
}
```
Dengan begitu, kita tidak perlu mengimport untuk dapat menggunakan facade kita karena sudah bisa diakses dari namespace root.

```php
//  Contoh penggunaan Facade Calculator
Calculator::add(20)->subtract(10)->getResult(); // 10
```