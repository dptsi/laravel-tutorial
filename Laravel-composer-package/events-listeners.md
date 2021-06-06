# Events & Listeners

[Kembali](readme.md)

## Latar belakang topik

Events dan Listeners merupakan hal yang cukup sering diimplementasikan oleh developer pada projeknya. Penggunaan event sangatlah membantu di berbagai aspek pada aplikasi kita. Sebuah event bisa mempunyai beberapa listener yang independen terhadap satu sama lain.

## Konsep-konsep

Event pada Laravel menyediakan cara untuk menghubungkan sebuah aktivitas tertentu pada aplikasi kita ke sebuah fungsi yang kita inginkan. Event bisa dikirim dengan menggunakan helper `event()` yang memiliki satu parameter yaitu `Event` class. Setelah Event dikirim, method `handle()` pada setiap listener akan triggered. Listener untuk event tertentu didefinisikan pada `Event Service Provider`.

Package yang mengirimkan event ketika melakukan aktivitas tertentu adalah hal yang biasa. User bisa memilih untuk membuat listenernya sendiri untuk sebuah event yang telah kita definisikan di package. 

Kita dapat membuat kelas `Event` kita di direktori `src/Events`. Untuk kelas 'Listener', kita dapat buat di direktori `src/Listeners`. Setelah membuat event dan listener, kita buat kelas event service provider yang mengextend `ServiceProvider` pada direktori `src/Providers` untuk meregister event dan listener yang telah kita buat. Setelah itu jangan lupa untuk meregister event service provider kita ke `ServiceProvider` utama kita.

## Langkah-langkah tutorial

Pada tutorial ini, kita akan membuat sebuah event sederhana yaitu event ketika kita memanggil method `show_hello()` pada controller yang telah kita buat di sesi sebelumnya, kemudian kita buat listenernya, dan kita registrasikan ke event service provider.

### Langkah pertama

Buat kelas `ShowHelloWasCalled.php` pada direktori `src/Events`, yang mana kelas ini menerima sebuah string dan akan mengubah isi dari config file yang dipanggil oleh controller menjadi string yang dimasukkan pada constructor.

```php
// 'src/Events/ShowHelloWasCalled.php'
<?php

namespace maximuse\HelloWorld\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ShowHelloWasCalled
{
    use Dispatchable, SerializesModels;

    public function __construct($newName)
    {
        config(['helloworld.name1' => $newName]);
    }
}
```

### Langkah kedua

Kemudian tambahkan `event()` pada controller yang kita inginkan.
```php
<?php

namespace maximuse\HelloWorld\Http\Controllers;

use Illuminate\Routing\Controller;
use maximuse\HelloWorld\Events\ShowHelloWasCalled;

class HelloController extends Controller
{
    public function show_hello()
    {
        event(new ShowHelloWasCalled("Nama baru dari event"))

        $name = config('helloworld.name1');

        return view('helloworld::helloworld', compact('name'));
    }
}
```

### Langkah ketiga

Setelah event `ShowHelloWasCalled` dipanggil, kita akan mencoba untuk membuat listener yang memanipulasi lagi nama pada file config. Buat file `UpdateName.php` pada direktori `src/Listeners`.

```php
// 'src/Listeners/UpdateName.php'
<?php

namespace maximuse\HelloWorld\Listeners;

use maximuse\HelloWorld\Events\ShowHelloWasCalled;

class UpdateName
{
    public function handle(ShowHelloWasCalled $event)
    {
        config(['helloworld.name1' => "Nama baru dari listener"]);
    }
}
```

### Langkah keempat

Setelah membuat event dan listener, berikutnya kita buat kelas `EventServiceProvider.php` pada direktori `src/Providers` dan kita registrasikan event dan listener yang telah kita buat.

```php
// 'src/Providers/EventServiceProvider.php'
<?php

namespace maximuse\HelloWorld\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use maximuse\HelloWorld\Events\ShowHelloWasCalled;
use maximuse\HelloWorld\Listeners\UpdateName;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ShowHelloWasCalled::class => [
            UpdateName::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
```

### Langkah kelima

Langkah terakhir adalah meregister `EventServiceProvider` kita ke `CalculatorServiceProvider` kita di method `register()`.

```php
// 'CalculatorServiceProvider.php'
use maximuse\HelloWorld\Providers\EventServiceProvider;

public function register()
{
    $this->app->register(EventServiceProvider::class);
}
```