# Laravel Middleware

[Kembali](readme.md)

## Introduction

Middleware menyediakan mekanisme yang mudah untuk memeriksa dan memfilter permintaan HTTP yang memasuki aplikasi kita. Misalnya, Laravel menyertakan middleware yang memverifikasi bahwa pengguna aplikasi kita telah diautentikasi. Jika pengguna tidak diautentikasi, middleware akan mengarahkan pengguna ke layar login aplikasi. Namun, jika pengguna diautentikasi, middleware akan mengizinkan permintaan untuk melanjutkan lebih jauh ke dalam aplikasi. Sesuai namanya ‘middle’ yang berarti tengah, maka letak Middleware adalah berada di tengah antara controller dan router. Ada pula yang mengartikan Middleware adalah software yang menengahi sebuah aplikasi dengan yang lain. Dengan begini, proses integrasi antar aplikasi dapat berjalan dengan lebih mudah. Semua middleware ini terletak di `app/Http/Middlewaredirektori`.

Fungsi-fungsi Middleware secara umum adalah:
1. Authentication (seperti pada Laravel).
2. Validasi input.
3. Authorization.
4. Data logger.
5. Sanitasi input.
6. Meresponse handler, dan lain sebagainya.

## Defining Middleware

Untuk membuat middleware baru, gunakan `make:middleware` perintah Artisan:
```
php artisan make:middleware EnsureTokenIsValid
```
Perintah ini akan menempatkan kelas `EnsureTokenIsValid` baru dalam direktori `app/Http/Middleware`. Di middleware ini, hanya akan mengizinkan akses ke route jika token input yang diberikan cocok dengan nilai yang ditentukan. Jika tidak, akan diarakan kembali pengguna ke home URI:
```
<?php

namespace App\Http\Middleware;

use Closure;

class EnsureTokenIsValid
{
    /**
     * Menghandle request yang masuk
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('token') !== 'my-secret-token') {
            return redirect('home');
        }
        return $next($request);
    }
}
```
Seperti yang kita lihat, jika `token` yang diberikan tidak cocok dengan `my-secret-token`, middleware akan mengembalikan pengalihan HTTP ke klien; jika tidak, permintaan tersebut akan diteruskan lebih jauh ke dalam aplikasi. Untuk meneruskan permintaan lebih dalam ke aplikasi (mengizinkan middleware untuk "pass"), kita harus memanggil `$next` callback dengan ekstensi `$request`.
Alangkah baiknya adalah membayangkan middleware sebagai serangkaian "layer" yang harus dilalui permintaan HTTP sebelum masuk ke aplikasi Anda. Setiap layer dapat memeriksa permintaan dan bahkan menolaknya seluruhnya.
Catatan : Semua middleware diselesaikan melalui service container , jadi kita dapat memberi petunjuk tentang dependensi apa pun yang diperlukan dalam konstruktor middleware.

### Middleware & Responses

Tentu saja, middleware dapat melakukan tugas sebelum atau sesudah meneruskan permintaan lebih dalam ke dalam aplikasi. Misalnya, middleware berikut akan melakukan beberapa tugas sebelum permintaan ditangani oleh aplikasi:
```
<?php

namespace App\Http\Middleware;

use Closure;

class BeforeMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action

        return $next($request);
    }
}
```
Namun, middleware ini akan menjalankan tugasnya setelah permintaan ditangani oleh aplikasi:
```
<?php

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Perform action

        return $response;
    }
}
```

## Registering Middleware

### Global Middleware

Jika ingin middleware dijalankan selama setiap permintaan HTTP ke aplikasi kita, register kelas middleware di `$middleware` properti `app/Http/Kernel.php` kelas kita.
### Assigning Middleware to Routes

Jika ingin menetapkan middleware ke route tertentu, kita harus menetapkan kunci middleware terlebih dahulu di file`app/Http/Kernel.php` . Secara default,properti `$routeMiddleware kelas ini berisi entri untuk middleware yang disertakan dengan Laravel. Kita dapat menambahkan middleware kita sendiri ke daftar ini dan menetapkannya sebagai kunci pilihan kita:
```
// Within App\Http\Kernel class...

protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
];
```
Setelah middleware didefinisikan di kernel HTTP, Anda dapat menggunakan metode `middleware` untuk menetapkan middleware ke route:
```
Route::get('/profile', function () {
    //
})->middleware('auth');
```
Kita dapat menetapkan beberapa middleware ke route dengan meneruskan array of nama middleware ke metode `middleware`:
```
Route::get('/', function () {
    //
})->middleware(['first', 'second']);
```
Saat menetapkan middleware, juga dapat memberikan nama kelas yang memenuhi syarat:
```
use App\Http\Middleware\EnsureTokenIsValid;

Route::get('/profile', function () {
    //
})->middleware(EnsureTokenIsValid::class);
```
Saat menetapkan middleware ke grup route, terkadang perlu mencegah middleware diterapkan ke route individu dalam grup. Kita dapat melakukannya dengan menggunakan metode `withoutMiddleware`:
```
use App\Http\Middleware\EnsureTokenIsValid;

Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/', function () {
        //
    });

    Route::get('/profile', function () {
        //
    })->withoutMiddleware([EnsureTokenIsValid::class]);
});
```
Metode `withoutMiddleware` hanya dapat menghapus route middleware dan tidak berlaku untuk middleware global.

### Middleware Groups

Terkadang kita  mungkin ingin mengelompokkan beberapa middleware di bawah satu tombol agar membuatnya lebih mudah untuk ditetapkan ke route. Kita dapat melakukannya dengan menggunakan properti `$middlewareGroups` dari kernel HTTP kita.
LARAVEL mengandung middleware umum yang mungkin ingin menerapkan ke route web dan API kita. Ingat, grup middleware ini secara otomatis diterapkan oleh `App\Providers\RouteServiceProvider` penyedia server aplikasi kita untuk meroutekan dalam file yang sesuai route web dan api:
```
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        // \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],

    'api' => [
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```
Grup middleware dapat ditetapkan ke route dan controller actions menggunakan sintaks yang sama dengan middleware individual. Sekali lagi, grup middleware membuatnya lebih nyaman untuk menetapkan banyak middleware ke sebuah route sekaligus:
```
Route::get('/', function () {
    //
})->middleware('web');

Route::middleware(['web'])->group(function () {
    //
});
```
**Catatan** : Grup middleware web dan api secara otomatis diterapkan ke aplikasi kita yang sesuai dengan file `routes/web.php` dan `routes/api.php` oleh `App\Providers\RouteServiceProvider`.

### Sorting Middleware

Terkadang, kita mungkin memerlukan middleware kita untuk mengeksekusi dalam urutan tertentu tetapi tidak memiliki kendali atas urutannya saat ditetapkan ke route . Dalam kasus ini, kita dapat menentukan prioritas middleware Anda menggunakan properti `$middlewarePriority` dari file `app/Http/Kernel.php` kita. Properti ini mungkin tidak ada di kernel HTTP kita secara default. Jika tidak ada, kita dapat menyalin definisi defaultnya di bawah ini:
```
/**
 * The priority-sorted list of middleware.
 *
 * This forces non-global middleware to always be in the given order.
 *
 * @var array
 */
protected $middlewarePriority = [
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
    \Illuminate\Routing\Middleware\ThrottleRequests::class,
    \Illuminate\Session\Middleware\AuthenticateSession::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \Illuminate\Auth\Middleware\Authorize::class,
];
```

## Middleware Parameters

Middleware juga dapat menerima parameter tambahan. Misalnya, jika aplikasi kita perlu memverifikasi bahwa pengguna yang diautentikasi memiliki "peran" tertentu sebelum melakukan tindakan tertentu, kita dapat membuat `EnsureUserHasRolemiddleware` yang menerima nama peran sebagai argumen tambahan.

Parameter middleware tambahan akan diteruskan ke middleware setelah `$nextargumen`:
```
<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserHasRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()->hasRole($role)) {
            // Redirect...
        }

        return $next($request);
    }

}
```
Parameter middleware dapat ditentukan saat menentukan route  dengan memisahkan nama middleware dan parameter dengan a :. Beberapa parameter harus dipisahkan dengan koma:
```
Route::put('/post/{id}', function ($id) {
    //
})->middleware('role:editor');
```


## Terminable Middleware

Terkadang middleware mungkin perlu melakukan beberapa pekerjaan setelah respon HTTP telah dikirim ke browser. Jika kita menentukan metode `terminate` di middleware dan server web kita menggunakan FastCGI, metode `terminate` tersebut akan secara otomatis dipanggil setelah respons dikirim ke browser:
```
<?php

namespace Illuminate\Session\Middleware;

use Closure;

class TerminatingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        // ...
    }
}
```
Metode `terminate` harus menerima permintaan dan respon. Setelah kita menentukan middleware yang dapat diakhiri, kita harus menambahkannya ke daftar route atau middleware global dalam file `app/Http/Kernel.php`.
Saat memanggil metode` terminate` pada middleware kita, Laravel akan menyelesaikan instance baru dari middleware dari service container . Jika kita ingin menggunakan instance middleware yang sama saat metode `handle` dan metode `terminate` dipanggil, register middleware dengan container menggunakan metode container `singleton`. Biasanya ini harus dilakukan dengan metode `register` dari `AppServiceProvider` kita:
```
use App\Http\Middleware\TerminatingMiddleware;

/**
 * Register any application services.
 *
 * @return void
 */
public function register()
{
    $this->app->singleton(TerminatingMiddleware::class);
}
```


