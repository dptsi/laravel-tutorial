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

## Controller Middleware

Middleware dapat ditetapkan ke controller’s route pada route :
```php
Route::get('profile', [UserController::class, 'show'])->middleware('auth');
```
Middleware juga bisa di dalam controller’s constructor. Menggunakan Middleware method dalam controller’s constructor, Middleware dapat ditetapkan ke dalam controller’s actionnya :
```
class UserController extends Controller
{
    /**
     * Inisiasi controller baru
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('log')->only('index');
        $this->middleware('subscribed')->except('store');
    }
}
```

## Resource Controller

Di dalam aplikasi yang dibuat memiliki beberapa resource memiliki set aksi/metode yang sama seperti CRUD. Misal dalam sebuah aplikasi terdapat **photo** model **video** model. Resource routing Laravel menentapkan create, read, update, and delete ("CRUD") route ke sebuah controller dengan sebuah single line code.
Membuat Controller dapat dilakukan dengan menggunakan perintah PHP Artisan yang disediakan Laravel atau dengan membuat secara manual di dalam folder `app/Http/Controllers`. Berikut adalah perintah PHP Artisan untuk membuat sebuah Controller melalui bash:
```php
php artisan make:controller PhotoController --resource
```
Perintah ini akan generate controller di `app/Http/Controllers/PhotoController.php`. Controller akan mengandung method untuk setiap resource yang tersedia. Langkah selanjutnya register resource route ke dalam controller :
```php
use App\Http\Controllers\PhotoController;
Route::resource('photos', PhotoController::class);
```
Register beberapa resource controller juga dapat dilakukan dengan passing array ke dalam `resource` method :
``` php
Route::resources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);
```
**Aksi/method yang dapat dilakukan oleh resouce controller** :
** TOLONG TABELNYA WIS**

Penetapan resource model dapat dilakukan saat generating controller dengan php artisan berikut :
```php
php artisan make:controller PhotoController --resource --model=Photo
```

### Partial Resource Routes

Saat deklarasi sebuah resource route, bisa menetapkan subset aksi/method yang harus ditangani controller sesuai yang diinginkan, bukan melalui aksi default yang ada.
```php
use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
```
Pada syntax pertama mengandung aksi index dan show saja. Sedangkan pada syntax kedua mengandung aksi selain create, store, update, dan destroy. Kedua syntax tersebut memiliki tujuan yang sama.

**API RESOURCE ROUTES**
Saat mendeklarasikan route resource yang akan digunakan oleh API, kita biasanya ingin mengecualikan rute yang menyajikan template HTML seperti `create` dan `edit`. Maka, kita dapat menggunakan api Resource metode ini untuk mengecualikan dua rute ini secara otomatis:
```php
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;

Route::apiResources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);
```
Atau dapat melalui perintah berikut :
```php
php artisan make:controller PhotoController --api
```

### Nested Resource

Terkadang kita mungkin perlu menentukan route ke nested resource. Misalnya, resource route photo mungkin memiliki beberapa comment yang mungkin dilampirkan ke foto. Untuk nest resouce controler, kita dapat menggunakan "titik" di deklarasi route :

```php
Route::resource('photos.comments', PhotoCommentController::class);

```
Route ini akan diregister saat kita mengakses URI dengan :
```php
/photos/{photo}/comments/{comment}
```

### Penamaan Parameter Resource Routes

Secara default, Route:resource akan membuat route parameter untuk resource routes kita berdasarkan versi "singularized" dari resource name. Kita dapat dengan mudah membaut dengan nama parameter sesuai yang kita inginkan dengan passing array ke `parameter` method yang terdiri atas asosiatif array resource names and parameter names:
```php
use App\Http\Controllers\AdminUserController;

Route::resource('users', AdminUserController::class)->parameters([
    'users' => 'admin_user'
]);
```
Dan dapat digenerate melalui URI untuk resource's show route:
```php
/users/{admin_user}
```

### Scoping Resources Routes

Fitur scoping model `implisit binding` Laravel dapat secara otomatis menjangkau nested binding sedemikian rupa sehingga child model  yang diselesaikan dikonfirmasi sebagai milik model parentnya. Dengan menggunakan `scoped` metode saat menentukan nested resource, kita dapat mengaktifkan scoping otomatis serta menginstruksikan Laravel mana child resource yang harus diambil:
```php
use App\Http\Controllers\PhotoCommentController;

Route::resource('photos.comments', PhotoCommentController::class)->scoped([
    'comment' => 'slug',
]);
```
Route ini akan meregister scoped nested resource dengan mengakses URIs seperti berikut:
```php
/photos/{photo}/comments/{comment:slug}
```

### Localizing Resource URl

Secara default, `Route::resource` akan membuat resource URl menggunakan verb dengan bahasa Inggris. Jika ingin lokalisasi/custom aksi verb misal create dan edit, menggunakan `Route::resourceVerbs` method. Ini dapat dilakukan dalam metode `boot AppServiceProvider`, dalam aplikasi `App\Providers\RouteServiceProvider`, berikut merupkan penerapannya:
```php
/**
 * Definisi route model bindings, pattern filters, dll.
 *
 * @return void
 */
public function boot()
{
    Route::resourceVerbs([
        'create' => 'crear',
        'edit' => 'editar',
    ]);

    // ...
}
```
Setelah kata kerja dikustomisasi, pendaftaran route sumber daya seperti `Route::resource ('fotos', 'PhotoController')` akan menghasilkan URI berikut:
```php
/fotos/crear

/fotos/{foto}/editar
```

### Supplementing Resource Controllers

Jika kita perlu menambahkan route tambahan ke resource controller di luar rangkaian route resource default, kita harus definisikan route tersebut sebelum memanggil `Route::resource ` method; jika tidak, route yang didefinisikan oleh resource method mungkin secara tidak sengaja lebih diutamakan daripada route tambahan kita :
```php
use App\Http\Controller\PhotoController;

Route::get('/photos/popular', [PhotoController::class, 'popular']);
Route::resource('photos', PhotoController::class);
```

## Dependency Injection & Controllers

### Apa Itu Dependency Injection?

[Dependency Injection](https://stackoverflow.com/questions/130794/what-is-dependency-injection)

### Constructor Injection

Service container laravel digunakan untuk menyelesaikan semua Laravel controllers. Sebagai hasilnya, kita dapat type-hint setiap dependensi yang mungkin diperlukan controller dalam konstruktornya. Ketergantungan yang dideklarasikan akan secara otomatis diselesaikan dan inject ke instance controller: 
```php
<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * user repository instance.
     */
    protected $users;

    /**
     * Membuat controller instance baru.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
}
```

### Method Injection

Selain contructor injection, kita juga dapat mengetik dependensi-hint pada controller's method. Penggunaan umum untuk controller's method adalah inject `Illuminate\Http\Request` instance ke dalam method controller:: 
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store user baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;

        //
    }
}
```
Jika controller method kita juga mengharapkan input dari parameter route, register argumen route kita setelah dependensi kita yang lain. Misalnya, jika route kita didefinisikan seperti :
```php
use App\Http\Controllers\UserController;

Route::put('/user/{id}', [UserController::class, 'update']);
```
kita masih dapat mengetikkan hint `Illuminate\Http\Request` dan mengakses parameter dengan mendefinisikan controller method sebagai berikut: 
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update user yang diberikan
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

