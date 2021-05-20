# Laravel Route

[Kembali](readme.md)

## Introduction

Route adalah proses pengiriman data dari pengguna melalui permintaan/request menuju ke alamat yang sudah terdaftar, di mana alamat tersebut akan mengembalikan output proses dari permintaan tersebut.

![Website Route](./img/website-route.png)

Pada contoh di atas, setelah nama situs mywebsite.com, kita dapat menentukan route yang ingin dilalui dengan menambahkan alamat URI.

## Daftar Isi
- [Laravel Route](#laravel-route)
  * [Introduction](#introduction)
  * [Daftar Isi](#daftar-isi)
  * [Basic Routing](#basic-routing)
    + [The Default Route Files](#the-default-route-files)
    + [Available Router Methods](#available-router-methods)
    + [Redirect Routes](#redirect-routes)
    + [View Routes](#view-routes)
  * [Route Parameters](#route-parameters)
    + [Required Parameters](#required-parameters)
    + [Optional Parameters](#optional-parameters)
    + [Regular Expression (Regex) Constraints](#regular-expression--regex--constraints)
      - [Global Constraint](#global-constraint)
      - [Encoded Forward Slashes](#encoded-forward-slashes)
  * [Named Routes](#named-routes)
    + [Generating URLs To Named Routes](#generating-urls-to-named-routes)
  * [Route Groups](#route-groups)
    + [Middleware](#middleware)
    + [Subdomain Routing](#subdomain-routing)
    + [Route Prefixes](#route-prefixes)
    + [Route Name Prefixes](#route-name-prefixes)
  * [Route Model Binding](#route-model-binding)
    + [Implicit Binding](#implicit-binding)
      - [Customizing The Key](#customizing-the-key)
      - [Custom Keys and Scoping](#custom-keys-and-scoping)
      - [Customizing Missing Model Behavior](#customizing-missing-model-behavior)
    + [Explicit Binding](#explicit-binding)
      - [Customizing The Resolution Logic](#customizing-the-resolution-logic)
  * [Fallback Routes](#fallback-routes)
  * [Rate Limiting](#rate-limiting)
    + [Defining Rate Limiters](#defining-rate-limiters)
      - [Segmenting Rate Limits](#segmenting-rate-limits)
    + [Attaching Rate Limiters to Routes](#attaching-rate-limiters-to-routes)
      - [Throttling With Redis](#throttling-with-redis)
  * [Form Method Spoofing](#form-method-spoofing)
  * [Accessing The Current Route](#accessing-the-current-route)
  * [Cross-Origin Resource Sharing (CORS)](#cross-origin-resource-sharing--cors-)
  * [Route Caching](#route-caching)


## Basic Routing
Secara dasarnya, Laravel route menerima URI dan sebuah closure (fungsi anonim).
```php
use Illuminate\Support\Facades\Route;

Route::get('/greeting', function () {
    return 'Hello World';
});
```
Dapat dilihat bahwa:
- `get` merupakan method yang digunakan
- `‘/greeting’` bertindak sebagai URI
- `function()` berfungsi sebagai Closure untuk menjalankan proses yang diinginkan

### The Default Route Files
Seluruh route pada Laravel dikendalikan pada directory `routes`. Biasanya, penggunaannya umum terbagi menjadi dua, yakni:
Untuk tampilan web, maka digunakan file pada `routes/web.php`
Untuk API (Application Programming Interface), maka dapat digunakan file pada `routes/api.php`.

Kita tinggal melakukan pengisian class Route pada kedua file tersebut. Perlu diingat bahwa untuk memakai API, secara otomatis ketika melakukan routing pada browser ditambahkan prefix `/api`. Keseluruhan file pada direktori `routes` diatur pada `App\Providers\RouteServiceProvider`.

### Available Router Methods
Router pada Laravel juga menyediakan akses respond beberapa method HTTP, seperti:
```php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
```
Namun, khusus untuk seluruh route dengan method POST, PUT, PATCH, atau DELETE, jika digunakan pada file route web, diwajibkan untuk menambahkan CSRF Protection di dalam file view sebelum mengakses route tersebut.
```html
<form method="POST" action="/profile">
    @csrf
    ...
</form>
```

Informasi lebih lanjut mengenai method dapat diakses pada [HTTP Request Method](https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods)

### Redirect Routes
Jika kita ingin berpindah dari satu route ke URI lainnya, kita dapat menggunakan method `Route::redirect` yang secara default mengeluarkan kode status 302.
```php
Route::redirect('/here', '/there');
```

Kita dapat juga menambahkan parameter tambahan untuk mengganti kode status.
```php
Route::redirect('/here', '/there', 301);
```

Adapun method lain yang secara default mengeluarkan kode status 301 adalah berikut.
```php
Route::permanentRedirect('/here', '/there');
```

### View Routes
Jika kita hanya ingin menampilkan tampilan web, method `Route::view` dapat digunakan. Kita tidak perlu mendefinisikan route secara penuh beserta controller yang digunakan.
```php
// tanpa passing array data
Route::view('/welcome', 'welcome');

// dengan passing array data
Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
```

## Route Parameters
Terkadang dalam penggunaan routing, kita memerlukan cara untuk mengambil sebuah segmen pada URI. Maka dari itu, route parameters diperlukan. 

### Required Parameters
Parameter ini digunakan ketika ingin mengambil segmen pada URI dan melakukan passing segmen tersebut dalam bentuk argument ke Closure. Kita juga dapat melakukan passing sebanyak mungkin route parameters yang dibutuhkan.
```php
// route parameter tunggal
Route::get('/user/{id}', function ($id) {
    return 'User '.$id;
});

// route parameter jamak
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    // kode program
});
```

Route parameters akan selalu dimasukkan dalam `{}` (kurung kurawal) dan terdiri dari karakter alphanumeric.

Jika kita ingin melakukan dependency injection pada route callback, kita dapat melakukan list route parameter setelah dependency kita.
```php
use Illuminate\Http\Request;

Route::get('/user/{id}', function (Request $request, $id) {
    return 'User '.$id;
});
```

### Optional Parameters
Terkadang sebuah route parameter tidak selalu hadir pada URI. Maka, kita dapat menambahkan `?` (tanda tanya) setelah nama parameter. Tak lupa, kita juga perlu memberikan nilai default pada variabel route yang bersangkutan.
```php
Route::get('/user/{name?}', function ($name = null) {
    return $name;
});

Route::get('/user/{name?}', function ($name = 'John') {
    return $name;
});
```

### Regular Expression (Regex) Constraints
Kita juga dapat membatasi format karakter apa saja yang tersedia pada route parameter dengan method `where`. Method `where` menerima nama parameter dan regex dari bagaimana parameter dibatasi.
```php
Route::get('/user/{name}', function ($name) {
    // kode program
})->where('name', '[A-Za-z]+');

Route::get('/user/{id}', function ($id) {
    // kode program
})->where('id', '[0-9]+');

Route::get('/user/{id}/{name}', function ($id, $name) {
    // kode program
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
```

Untuk kemudahan pula, pada kasus karakter number, alphabet, alphanumeric, maupun UUID (Universally Unique Identifier), Laravel menyediakan method bantuan, seperti:
```php
Route::get('/user/{id}/{name}', function ($id, $name) {
    // kode program
})->whereNumber('id')->whereAlpha('name');

Route::get('/user/{name}', function ($name) {
    // kode program
})->whereAlphaNumeric('name');

Route::get('/user/{id}', function ($id) {
    // kode program
})->whereUuid('id');
```
Secara default, jika pola route tidak sesuai, maka response HTTP 404 akan dikembalikan.

#### Global Constraint
Jika kita ingin menentukan secara menyeluruh sebuah route parameter akan selalu dibatasi sebuah regex, kita dapat memakai method `pattern`. Hal tersebut dapat didefinisikan dengan mengakses kelas `App\Providers\RouteServiceProvider` dan mengisi method `boot` sebagai berikut.
```php
/**
 * Define your route model bindings, pattern filters, etc.
 *
 * @return void
 */
public function boot()
{
    Route::pattern('id', '[0-9]+');
}
```

Selanjutnya, kita tidak perlu mendefinisikan kembali parameter route karena sudah secara otomatis diaplikasikan pada seluruh router.
```php
Route::get('/user/{id}', function ($id) {
    // Only executed if {id} is numeric...
});
```

#### Encoded Forward Slashes
Laravel routing memperbolehkan seluruh karakter kecuali `/` (garis miring) pada parameter route. Namun, bagaimana untuk memperbolehkan tanda garis miring? Kita dapat mengizinkannya dengan menggunakan `where` pada regex.
```php
Route::get('/search/{search}', function ($search) {
    return $search;
})->where('search', '.*');
```
## Named Routes
Kita dapat memberikan penamaan (yang harus selalu unik) dengan memberikan method `name` pada pendefinisian route.
```php
// penamaan pada function closure
Route::get('/user/profile', function () {
    //
})->name('profile');

// penamaan pada controller action
Route::get(
    '/user/profile',
    [UserProfileController::class, 'show']
)->name('profile');
```

### Generating URLs To Named Routes
Sekarang kita coba memanggil url route yang telah diberikan nama. Kita cukup menggunakan fungsi pembantu `route` dan `redirect`.
```php
// Generating URLs...
$url = route('profile');

// Generating Redirects...
return redirect()->route('profile');
```

Kita juga dapat melakukan passing parameter dengan menambahkan argumen kedua pada fungsi `route`.
```php
// router yang dibuat
Route::get('/user/{id}/profile', function ($id) {
    //
})->name('profile');

// mendapatkan url dengan parameter tambahan, yakni /user/1/profile
$url = route('profile', ['id' => 1]);
```

Jika parameter yang ditambahkan terlalu banyak dari segment yang dimiliki, maka key-value yang dipassing-kan pada route akan secara otomatis digenerate menjadi URL query string.
```php
// router yang dibuat
Route::get('/user/{id}/profile', function ($id) {
    //
})->name('profile');

// mendapatkan url dengan parameter tambahan, yakni /user/1/profile?photos=yes
$url = route('profile', ['id' => 1, 'photos' => 'yes']);
```

## Route Groups
Ketika sebuah development system menjadi besar, route yang digunakan tentu menjadi semakin banyak. Laravel memberikan solusi untuk mengelompokkan route atau membagi atribut route, seperti penggunaan sebagai middleware, sehingga setiap route tidak perlu mendefinisikan atribut-atribut yang mirip pada route yang lain. Adapun, kita juga bisa menggabungkan prefix dan sebagainya pada URI.

### Middleware
Untuk melakukan assign middleware ke seluruh route dalam sebuah group, kita dapat memakai middleware sebelum mendefinisikan method `group`.
```php
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second middleware...
    });

    Route::get('/user/profile', function () {
        // Uses first & second middleware...
    });
});
```
### Subdomain Routing
Route juga dapat melakukan handle terhadap subdomain routing.
```php
Route::domain('{account}.example.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});
```

### Route Prefixes
Method `prefix` digunakan jika ingin memberikan awalan prefix pada setiap URI yang dikelompokkan. Misal kita ingin membuat prefix pada seluruh route dengan awalan `admin`.
```php
Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // Matches The "/admin/users" URL
    });
});
```

### Route Name Prefixes
Kita juga dapat memberikan prefix pada nama dari route. Namun, penamaan prefix tersebut harus menggunakan karakter `.` (titik) di akhir prefix.
```php
Route::name('admin.')->group(function () {
    Route::get('/users', function () {
        // Route assigned name "admin.users"...
    })->name('users');
});
```

## Route Model Binding
Ketika melakukan injeksi model ID ke dalam route atau controller action, kita seringkali melakukan query terhadap database untuk mengembalikan model yang sesuai dengan ID tersebut. Laravel route model binding memberikan kemudahan untuk injeksi otomatis ke dalam route.

### Implicit Binding
Laravel secara otomatis mengatasi model Eloquent yang didefinisikan pada route atau controller action yang bersesuaian dengan nama segment route.
```php
use App\Models\User;

Route::get('/users/{user}', function (User $user) {
    return $user->email;
});
```
Apabila variabel `user` yang ditunjukkan sebagai model Eloquent dari `App\Models\User` dan nama variabel cocok dengan segmen URI `{user}`, Laravel akan secara otomatis melakukan injeksi model instance yang memiliki ID sesuai dengan value dari URI. Jika tidak ditemukan, respon HTTP 404 yang dikembalikan.

Tentu, implicit binding juga bekerja pada method controller.
Pada route akan berbentuk:
```php
// Route definition...
Route::get('/users/{user}', [UserController::class, 'show']);
```
Pada `UserController` akan berbentuk:
```php
// lakukan pemanggilan model instance
use App\Http\Controllers\UserController;
use App\Models\User;

// Controller method definition...
public function show(User $user)
{
    return view('user.profile', ['user' => $user]);
}
```

#### Customizing The Key
Terkadang kita ingin menggunakan kolom selain `id`. Maka, spesifikasikan nama kolom pada definisi parameter.
```php
use App\Models\Post;

// mengambil kolom slug pada model Post
Route::get('/posts/{post:slug}', function (Post $post) {
    return $post;
});
```

Jika ingin keseluruhan model binding ingin diatur agar dapat mengambil selain `id`, override method `getRouteKeyName` pada model Eloquent.
```php
/**
 * Get the route key for the model.
 *
 * @return string
 */
public function getRouteKeyName()
{
    return 'slug';
}
```
#### Custom Keys and Scoping
Kita juga dapat memanggil beberapa model binding.
```php
use App\Models\Post;
use App\Models\User;

Route::get('/users/{user}/posts/{post:slug}', function (User $user, Post $post) {
    return $post;
});
```

#### Customizing Missing Model Behavior
Secara default, respon HTTP 404 akan di-generate jika bound model tidak ditemukan. Tetapi, kita dapat melakukan kustomisasi dengan memanggil method `missing`.
```php
use App\Http\Controllers\LocationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

Route::get('/locations/{location:slug}', [LocationsController::class, 'show'])
        ->name('locations.view')
        ->missing(function (Request $request) {
            return Redirect::route('locations.index');
        });
```

### Explicit Binding
Kita juga bisa mendefinisikan bind secara eksplisit dengan method `model` pada router. Untuk melakukannya, definisikan binding pada method `boot` pada `App\Providers\RouteServiceProvider`.
```php
use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Define your route model bindings, pattern filters, etc.
 *
 * @return void
 */
public function boot()
{
    Route::model('user', User::class);

    // ...
}
```

Selanjutnya, definisikan route yang mengandung parameter `{user}`.
```php
use App\Models\User;

Route::get('/users/{user}', function (User $user) {
    //
});
```

#### Customizing The Resolution Logic
Jika kita ingin melakukan model binding dengan resolution logic tersendiri, gunakan method `Route::bind`. Pengaturan ini dilakukan pada method `boot` pada `App\Providers\RouteServiceProvider`.
```php
use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Define your route model bindings, pattern filters, etc.
 *
 * @return void
 */
public function boot()
{
    Route::bind('user', function ($value) {
        return User::where('name', $value)->firstOrFail();
    });

    // ...
}
```

Selain itu, kita juga dapat override method `resolveRouteBind` pada model Eloquent.
```php
/**
 * Retrieve the model for a bound value.
 *
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \Illuminate\Database\Eloquent\Model|null
 */
public function resolveRouteBinding($value, $field = null)
{
    return $this->where('name', $value)->firstOrFail();
}
```
## Fallback Routes
Kita dapat menggunakan method `Route::fallback` apabila ingin melakukan eksekusi ketika tidak ada route lain yang sesuai dengan request yang diajukan. Secara default, request yang tidak sesuai akan otomatis merender laman 404. Namun, kita dapat melakukan otomasi terhadap fallback ini dengan meletakkannya pada bagian route terakhir pada file `routes/web.php`.
```php
Route::fallback(function () {
    // kode program
});
```
## Rate Limiting
Kita dapat membatasi rate trafik yang ada pada sebuah atau grup route.

### Defining Rate Limiters
Untuk membatasi rate, kita bisa mengganti method `configureRateLimiting` pada class `App\Providers\RouteServiceProvider`. Nantinya, rate limiters menggunakan method `for` pada facade `RateLimiter` yang menerima nama rate limiter dan closure yang berisi konfigurasi limitasi. Konfigurasi yang digunakan dalam closure akan memakai class `Illuminate\Cache\RateLimiting\Limit`.
```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Configure the rate limiters for the application.
 *
 * @return void
 */
protected function configureRateLimiting()
{
    RateLimiter::for('global', function (Request $request) {
        return Limit::perMinute(1000);
    });
}
```

Jika request melebihi limit, response HTTP 429 akan dikembalikan. Kita juga dapat mendefinisikan response tersendiri dengan memakai method `response`.
```php
RateLimiter::for('global', function (Request $request) {
    return Limit::perMinute(1000)->response(function () {
        return response('Custom response...', 429);
    });
});
```
Kita juga dapat membagi limit berdasarkan model. Misal jika ingin membagi limit apakah untuk user khusus atau tidak.
```php
RateLimiter::for('uploads', function (Request $request) {
    return $request->user()->vipCustomer()
                ? Limit::none()
                : Limit::perMinute(100);
});
```

#### Segmenting Rate Limits
Terdapat beberapa kasus dalam membatasi rate limits. Misal, kita hanya memperbolehkan user mengakses route 100 kali per menit per IP address. Untuk menyelesaikan ini, digunakan method `by`.
```php
RateLimiter::for('uploads', function (Request $request) {
    return $request->user()->vipCustomer()
                ? Limit::none()
                : Limit::perMinute(100)->by($request->ip());
});
```
Adapun kasus jika, kita ingin membatasi  akses route dalam 100 kali per menit untuk user terautentikasi serta 10 kali per menit per IP address untuk guest.
```php
RateLimiter::for('uploads', function (Request $request) {
    return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
});
```

### Attaching Rate Limiters to Routes
Sekarang, kita akan mempelajari cara untuk memasukkan rate limiters di atas ke dalam routers. Kita dapat menggunakan [middleware](https://laravel.com/docs/8.x/middleware) `throttle`.
```php
Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('/audio', function () {
        //
    });

    Route::post('/video', function () {
        //
    });
});
```

#### Throttling With Redis
Jika pada projek menggunakan Redis, maka kita bisa mengganti middleware `throttle` yang di-map pada class `Illuminate\Routing\Middleware\ThrottleRequests` dengan class `Illuminate\Routing\Middleware\ThrottleRequestsWithRedis` pada HTTP kernel `App\Http\Kernel`. Penggunaan akan menjadi lebih efisien dengan adanya Redis.
```php
'throttle' => \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
```

## Form Method Spoofing
Karena form HTML standar tidak mendukung action PUT, PATCH, atau DELETE, maka anda perlu menambahkan field `_method` tersembunyi pada form.
```html
<form action="/example" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
```

Atau jika kita memakai [Blade directive](https://laravel.com/docs/8.x/blade), kita dapat memakai `@method` untuk generate `_method` secara otomatis.
```html
<form action="/example" method="POST">
    @method('PUT')
    @csrf
</form>
```

## Accessing The Current Route
Kita juga dapat mengakses informasi pada route handling.
```php
use Illuminate\Support\Facades\Route;

$route = Route::current(); // Illuminate\Routing\Route
$name = Route::currentRouteName(); // string
$action = Route::currentRouteAction(); // string
```

## Cross-Origin Resource Sharing (CORS)
Laravel juga menyediakan response terhadap [CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS) Options pada request HTTP dengan nilai yang bisa dikonfigurasi pada file `config/cors.php`. Seluruh request Options akan diatur oleh middleware `HandleCors` pada middleware stack global yang tersedia pada HTTP kernel `App\Http\Kernel`.

## Route Caching
Ketika deploy ke production, kita dapat memanfaatkan route cache Laravel. Dengan route cache, kita dapat mengurangi waktu yang dibutuhkan untuk mendaftarkan seluruh aplikasi route. Untuk menggunakan route cache, eksekusi command artisan `route:cache`.
```shell
php artisan route:cache
```

Ketika menjalankan command ini pada CLI, file cache route akan diolah pada setiap request. Ketika melakukan penambahan route baru, kita akan membutuhkan untuk generate route cache lagi. Karenanya, jalankan command `route:cache` hanya pada saat deployment project.

Kita juga dapat membersihkan route cache dengan `route:clear`.
```shell
php artisan route:clear
```
