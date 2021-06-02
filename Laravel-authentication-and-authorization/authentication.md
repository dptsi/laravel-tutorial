# Authentication

[Kembali](readme.md)

## Latar belakang topik

Banyak aplikasi web yang menyediakan cara untuk mengautentikasi penggunanya melalui *login*. Fitur ini cukup kompleks dan beresiko untuk diimplementasikan ke dalam aplikasi web. Maka dari itu, Laravel menyediakan *tools* yang dibutuhkanm untuk mengimplementasikan autentikasi dengan cepat, aman, dan mudah.

## Konsep-konsep

Pada dasarnya, fasilitas autentikasi pada Laravel terdiri dari `guards` dan `providers`. `Guards` menentukan bagaimana pengguna diautentikasi untuk tiap request. Contohnya adalah session guard yang menjaga state menggunakan session storage dan cookies.

`Providers` menentukan bagaimana pengguna diambil dari penyimpanan persisten. Laravel mendukung pengambilan data pengguna menggunakan *Eloquent* dan database query builder. Namun kita bisa menentukan provider tambahan yang dibutuhkan dalam aplikasi yang dibangun.

Konfigurasi autentikasi pada aplikasi web yang dibangun terletak pada `config/auth.php`. File ini berisi beberapa opsi untuk mengubah perilaku autentikasi dari Laravel.

## Langkah-langkah tutorial

### Langkah pertama : Menginstall starter kit

Laravel menyediakan starter kit untuk membantu membangun sistem autentikasi dengan mudah. Starter kit yang disediakan ada dua, yaitu `Laravel Breeze` dan `Laravel Jetstream`. Untuk tutorial ini, kita akan menggunakan `Laravel Breeze`.

Laravel Breeze dapat diinstall pada aplikasi yang sudah dibuat dengan menggunakan perintah :

```php
composer require laravel/breeze --dev
```

Setelah Composer selesai menginstall, jalankan perintah berikut untuk menambahkan views, routes, controllers, dan resources lain ke aplikasi yang ada.

```php
php artisan breeze:install
```

Setelah itu, jalankan perintah berikut untuk mengcompile asset-assetnya.

```php
npm install && npm run dev
```

berikut adalah tampilan halaman default laravel setelah menginstall Laravel Breeze. Akan ada opsi `login` dan `register` di kanan atas halaman.

![authentication 1](/Laravel-authentication-and-authorization/img/authentication-1.png)

### Langkah kedua : Autentikasi lainnya

#### 1. Manual

Kita juga bisa mengimplementasikan autentikasi tanpa menggunakan starter kit. Kita membutuhkan `Auth` facade untuk layanan autentikasi ini, maka dari itu perlu diingat untuk mengimpor `Auth` facade pada kelas yang digunakan. Metode yang digunakan untuk autentikasi ini adalah `attempt`, yang menangani form *login* dari aplikasi yang dibuat. Jika autentikasinya sukses, maka akan terbuat user session.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
```

Metode `attempt` menerima argumen pertama berupa array key atau pasangan nilai yang akan digunakan untuk mencari user pada database. Pada contoj di atas, user akan dicari berdasarkan `email`, yang apabila ditemukan maka password yang sudah di-hash di database akan dibandingkan dengan password yang ada di array. Metode `attempt` akan mereturn `true` jika autentikasi berhasil.

[REMEMBER ME]

#### 2. HTTP

Autentikasi dengan HTTP atau HTTP Basic Authentication menyediakan cara autentikasi tanpa perlu setting halaman login. Caranya adalah dengan menggunakan middleware `auth.basic` pada rute yang diinginkan. Middleware ini sudah termasuk dalam framework Laravel sehingga tidak perlu dibuat sendiri.

```php
Route::get('/profile', function () {
    // Only authenticated users may access this route...
})->middleware('auth.basic');
```

Berikut adalah tampilan login menggunakan HTTP. Secara default, kolom `email` pada tabel `users` digunakan sebagai `username` di sini.

![authentication 2](/Laravel-authentication-and-authorization/img/authentication-2.png)

[STATELESS]

#### 3. Lainnya
- Autentikasi instance user

Metode ini berguna pada saat kita sudah mempunyai instance user yang valid dan ingin dapat diautentikasi, seperti pada saat setelah pengguna melakukan register dan ingin melanjutkan untuk login. Berikut adalah perintah yang digunakan untuk mengimplementasikannya.

```php
use Illuminate\Support\Facades\Auth;

Auth::login($user);
```

Dapat juga ditambahkan argumen untuk fungsi `remember me`, yaitu dengan perintah sebagai berikut.

```php
Auth::login($user, $remember = true);
```

- Autentikasi dengan ID

Dengan metode ini, aplikasi akan melakukan autentikasi dengan primary key dari database yang digunakan. Berikut adalah contoh perintah untuk melakukan autentikasi sebagai user dengan ID 1.

```php
Auth::loginUsingId(1)
```

Dapat juga ditambahkan argumen untuk fungsi `remember me`, yaitu dengan perintah sebagai berikut.

```php
Auth::loginUsingId(1, $remember = true)
```

- Autentikasi sekali

Kita dapat menggunakan metode `once` untuk mengautentikasi pengguna pada aplikasi untuk satu request. Tidak ada session atau cookies yang digunakan dengan metode ini.

```php
if (Auth::once($credentials)) {
    //
}
```

### Langkah keempat : Logging out

User dapat keluar atau log out dari aplikasi dengan menggunakan metode `logout` yang disediakan oleh `Auth` facade. Informasi autentikasi dari session user akan dihapus.

Direkomendasikan untuk menginvalidate session user dan mengenerate ulang CSRF token user. Setelah logout, pada umumnya user akan dialihkan ke halaman root aplikasi.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Log the user out of the application.
 *
 * @param  \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
}
```

#### Invalidate session pada device lain

Laravel menyediakan cara untuk menginvalidasi atau me-*logout* session user yang aktif di device lain tanpa menginvalidasi session di device yang sekarang digunakan. Fitur ini biasa digunakan saat user mengganti password akun mereka dan ingin keluar dari device lain yang terautentikasi selain device yang sedang dipakai.

Pertama-tama, pada kelas `App\Http\Kernel` bagian middleware `web`, uncomment middleware `Illuminate\Session\Middleware\AuthenticateSession`.

```php
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
```

[LOGOUTOTHERDEVICES]

### Langkah kelima : Konfirmasi password

Fitur ini dapat diimplementasikan untuk kasus di mana user diminta untuk mengkonfirmasi password mereka sebelum dapat melakukan sebuah aksi, seperti saat akan mengakses area sensitif.

#### Form konfirmasi

Pertama-tama, kita akan menyiapkan tampilan untuk user mengkonfirmasi passwordnya (tampilan ini sudah ada bersama package Laravel Breeze).

```php
Route::get('/confirm-password', function () {
    return view('auth.confirm-password');
})->middleware('auth')->name('password.confirm');
```

#### Konfirmasi password

Selanjutnya adalah membuat route untuk menangani request dari form konfirmasi tadi. Route ini akan mengecek valid atau tidaknya password dan meneruskan user ke destinasi tujuan.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

Route::post('/confirm-password', function (Request $request) {
    if (! Hash::check($request->password, $request->user()->password)) {
        return back()->withErrors([
            'password' => ['The provided password does not match our records.']
        ]);
    }

    $request->session()->passwordConfirmed();

    return redirect()->intended('settings');
})->middleware(['auth', 'throttle:6,1'])->name('password.confirm');
```

Request `password` akan dicocokkan dengan password user yang terautentikasi. `passwordConfirmed` akan mengeset timestamp pada session user untuk menghitung berapa lama user tersebut telah mengonfirmasi passwordnya (lebih lanjut pada konfigurasi tambahan). Selanjutnya user akan diarahkan ke alamat tujuan.

#### Protecting routes

Selanjutnya adalah menerapkan middleware `password.confirm` pada rute tujuan. Middleware ini sudah ada dengan instalasi default Laravel sehingga tidak perlu dibuat sendiri.

```php
Route::get('/settings', function(){
    return view('settings');
})->middleware(['password.confirm'])->name('settings');
```

#### Konfigurasi tambahan

Setelah user mengkonfirmasi passwordnya, aplikasi tidak akan meminta user untuk menginput password kembali selama waktu yang ditentukan pada `password_timeout` yang dapat diatur di `config/auth.php`.

```php
    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    // OLD TIMEOUT
    'password_timeout' => 10800,

    // NEW TIMEOUT
    // 'password_timeout' => 30,
```

### Langkah keenam : Custom guards

### Langkah ketujuh : Custom user providers

### Langkah kedelapan : Social authentication

### Langkah kesembilan : Events
