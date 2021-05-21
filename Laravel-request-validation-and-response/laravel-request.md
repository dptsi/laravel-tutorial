# Laravel Request
[Kembali](readme.md)

## Latar Belakang
Dalam Laravel tentunya ada beberapa metode yang dapat digunakan untuk mengirimkan data, baik menggunakan GET, POST, PUT, PATCH, DELETE, ataupun OPTIONS. Untuk memudahkan hal tersebut, diperlukan adanya suatu cara yang dapat melakukan pengaksesan data yang dikirim secara object-oriented. Oleh karena itu, Laravel memiliki class untuk mengakomodasi hal tersebut melalui class Illuminate\Http\Request


## Konsep
Class Illuminate\Http\Request yang dimiliki laravel berfungsi menyediakan interaksi ke request HTTP yang sedang ditangani saat ini secara object-oriented serta mengambil input, cookie, dan files yang dikirimkan bersama request tersebut.

### Interaksi dengan Request
Untuk mendapatkan objek dari request HTTP saat ini melalui dependency injection, kita harus menambahkan <i>type hinting</i> ``Illuminate\Http\Request`` untuk dapat mengakses class tersebut. Pada umumnya hal terebut dilakukan dengan statement use pada bagian awal.
```
use Illuminate\Http\Request;
```
#### Dependency Injection & Route Parameters
Jika kita juga menginginkan menerima input dari route, kita harus mencantumkan parameter rute setelah dependensi lainnya. Sebagai contoh, jika kita memiliki route sebagai berikut
```php
use App\Http\Controllers\UserController;

Route::put('/user/{id}', [UserController::class, 'update']);
```
maka kita dapat mengakses ``id`` dengan cara berikut
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
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
