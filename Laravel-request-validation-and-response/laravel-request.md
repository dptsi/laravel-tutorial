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

### Request Headers
Dengan menggunakan method ``header`` kita dapat mendapatkan request header. Jika request tidak memiliki header, method ini akan mengembalikan nilai ``null``.  Namun method ini juga memiliki parameter opsional yang akan di-return ketika request tidak memiliki header.
```php
$value = $request->header('X-Header-Name');

$value = $request->header('X-Header-Name', 'default');
```
Selain itu, juga ada method ``hasHeader`` untuk mengecek apakah request memiliki header yang sesuai dengan parameter yang diberikan.
```php
if ($request->hasHeader('X-Header-Name')) {
    //
}
```
Terdapat juga method ``berarerToken`` yang akan mengembalikan <i>bearer token</i> dari header Authorization. Jika tidak ada akan mereturn ``null``.
```php
$token = $request->bearerToken();
```

### Request IP
Method ``ip`` akan mengembalikan <i>ip address</i> dari client yang membuat request pada aplikasi kita.
```php
$ipAddress = $request->ip();
```

### Input
#### Mengambil input
Berikut ini merupakan teknik pengambilan data input dari request yang sering digunakan 
- Mengambil semua data input</br>
Method ``all`` akan mengembalikan semua data input yang masuk dari request dalam array
```php
$input = $request->all();
```
- Mengambil sebuah data input</br>
Method ``input`` dapat digunakan untuk mengakses semua user input satu per satu. Pada method tersebut, kita juga dapat menambahkan default value pada argumen kedua yang akan di-return ketika nilai input yang diminta tidak ada pada request.
```php
$name = $request->input('name');

$name = $request->input('name', 'Sally');
```
Jika pada form terapat input berupa array, maka kita dapat meggunakan notasi ``dot`` untuk mengakses array 
```php
$name = $request->input('products.0.name');

$names = $request->input('products.*.name');
```
- Mengambil input dari query string</br>
Method ``query`` akan mengembalikan nilai dari query string.
Jika tidak diberikan parameter maka method ini akan mengembalikan seluruh nilai query string dalam bentuk associative array
```php
$query = $request->query();
```
Jika diberikan sebuah parameter maka akan mengembalikan nilai dari query string yang dimasukkan.
```php
$name = $request->query('name');
```
Jika nilai dari query string tidak ada, maka parameter kedua akan di-return
```php
$name = $request->query('name', 'Helen');
```
- Mengambil nilai input dari JSON
Dapat dilakukan dengan menggunakan method ``input``
```php
$name = $request->input('user.name');
```

- Mengambil input dari dynamic properties
Jika pada form  memiliki field ``name``, kita juga dapat mengaksesnya dengan cara berikut
```php
$name = $request->name;
```

#### Old Input
Laravel memungkinkan kita menyimpan input yang lama untuk digunakan pada request berikutnya dengan menggunakan. Biasanya ini digunakan bersamaan dengan validation input.
- Flash input ke session
```php
$request->flash();
```
Jika hanya ingin melakukan flash pada sebagian request saja dapat menggunakan method ``flashOnly`` dan ``flashExcept``
```php
$request->flashOnly(['username', 'email']);

$request->flashExcept('password');
```
- Flash input dengan redirecting
Untuk melakukan flash input yang diikuti dengan redirect, dapat dilakukan dengan menggunakan method ``withInput``
```php
return redirect('form')->withInput();

return redirect()->route('user.create')->withInput();

return redirect('form')->withInput(
    $request->except('password')
);
```
- Mengambil old input
Method ``old`` akan mengambil data input yang sebelumnya telah di-flash dari session
```php
$username = $request->old('username');
```
Laravel menyediakan ``old`` helper global. Jadi ketika pada blade template, kita ingin menampilkan old input akan lebih mudah ketika menggunakan ``old`` helper tersebut.
```php
<input type="text" name="username" value="{{ old('username') }}">
```


## Langkah-langkah tutorial

### Langkah pertama
Membuat 
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
