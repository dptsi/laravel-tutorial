# Laravel Controller

[Kembali](readme.md)

## Introduction

Laravel Controller merupakan salah satu bagian dimana seluruh fungsional web dibuat. Pada Controller dilakukan pengaturan untuk mengakses Model terkait dengan Database dan juga bagaimana mengirimkan datanya ke View dalam bentuk response. 

[![mvc-laravel.png](https://i.postimg.cc/0jqb9f5V/mvc-laravel.png)](https://postimg.cc/ZBVTc6dN)

Salah satu contoh aktivitas pada controller adalah aktivitas CRUD (Create, Read, Update, Delete).

## Penulisan Controller

### Dasar Controller

**Catatan** : Controller extend ke kelas base controller yang include dengan Laravel.
Direktori : `App\Http\Controllers\Controller`


```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Menampilkan profil user yang diberikan sesuai id
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}
```
Kita bisa mendefiniskan route ke dalam metode controller seperti berikut :
```php
use App\Http\Controllers\UserController;
Route::get('/user/{id}', [UserController::class, 'show']);
```
Saat datang request baru yang sesuai dengan route URl tertentu, metode `show` pada kelas `App\Http\Controllers\UserController` akan di invoke dan parameter route akan di passing ke dalam metode.
**Nb** : Controllers tidak perlu extend ke base class. Namun tidak akan bisa memiliki akses ke fitur praktis seperti **middleware** and **authorize** methods.


### Single Action Controllers

**Single Action Controller** adalah sebuah controller yang hanya memiliki satu aksi atau method. Dalam Single Action Controller ini kita akan menggunakan method `__invoke()`. Dalam controller.
```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProvisionServer extends Controller
{
    /**
     * Penyediaan server web baru
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        // ...
    }
}
```
Semua kode dapat dimasukan dalam metode `__invoke` tersebut. 
Saat register route untuk single action controllers ini tidak perlu mendefinisikan nama method controllernya. Sebagai gantinya, pass nama controller ke dalam routenya :
```php
use App\Http\Controllers\ProvisionServer;
Route::post('/server', ProvisionServer::class);
```
Untuk generate invokable controller tersebut menggunakan `--invokable` pada perintah `make :controller` Artisan :
```php
php artisan make:controller ProvisionServer --invokable
```

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
