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
### Middleware Groups
### Sorting Middleware
