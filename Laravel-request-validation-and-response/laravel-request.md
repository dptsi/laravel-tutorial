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
### Request Path & Method
Objek dari Illuminate\Http\Request menyediakan berbagai macam fungsi untuk memeriksa request HTTP yang masuk dan meng-extends Symfony\Component\HttpFoundation\Request. Berikut ini adalah beberapa fungsi penting yang sering digunakan:
#### Mengambil path dari request
Method ``path`` akan mengembalikan informasi mengenai path dari request. Jika request yang masuk menargetkan pada ``http://example.com/foo/bar`` fungsi ini akan mengembalikan ``/foo/bar``
```php
$uri = $request->path();
```

#### Memeriksa Path/Route dari request
Method ``is`` akan memverifikasi apakah path request yang masuk sesuai dengan pattern yang diberikan.
```php
if ($request->is('admin/*')) {
    //
}
```

Metod ``routeIs`` akan memverifikasi apakah request yang masuk memiliki nama route sesuai dengan pattern yang diberikan
```php
if ($request->routeIs('admin.*')) {
    //
}
```

#### Mengambil URL dari request
Dapat menggunakan method ``url`` atau ``fullUrl``. Method  ``url`` akan mengembalikan URL tanpa query string sedangkan method ``fullUrl`` akan mengembalikan URL dengan query string.
```php
$url = $request->url();

$urlWithQueryString = $request->fullUrl();
```

#### Mengambil method dari request
Dapat menggunakan method ``method`` yang akan mengembalikan HTTP verbs (HTTP request method) dan juga dapat menggunakan method ``isMethod`` yang akan memverifikasi apakah method dari request sesuai dengan string yang diberikan.
```php
$method = $request->method();

if ($request->isMethod('post')) {
    //
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
