# Authentication

[Kembali](readme.md)

## Latar belakang topik

Banyak aplikasi web yang menyediakan cara untuk mengautentikasi penggunanya melalui *login*. Fitur ini cukup kompleks dan beresiko untuk diimplementasikan ke dalam aplikasi web. Maka dari itu, Laravel menyediakan *tools* yang dibutuhkan untuk mengimplementasikan autentikasi dengan cepat, aman, dan mudah.

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

berikut adalah tampilan `login`.

![authentication 2](/Laravel-authentication-and-authorization/img/authentication-3.png)

berikut adalah tampilan `register`.

![authentication 2](/Laravel-authentication-and-authorization/img/authentication-4.png)

berikut adalah tampilan `dashboard`.

![authentication 2](/Laravel-authentication-and-authorization/img/authentication-5.png)


#### Mengambil data user terautentikasi

Kita dapat mengakses data terkait user yang terautentikasi dengan metode `user` dari `Auth` facade.

```php
use Illuminate\Support\Facades\Auth;

    public function retrieve(){
        $user = Auth::user()->name;
        $id = Auth::id();

        return view('dashboard', compact(['user', 'id']));
    }
```

selain itu, kita juga dapat mengakses user terautentikasi melalui `Illuminate\Http\Request` pada controller dengan metode `user`.

```php
use Illuminate\Support\Facades\Auth;

    public function retrieve(Request $request){
        // ALTERNATIVE
        $user = $request->user()->name;
        $id = $request->user()->id;

        return view('dashboard', compact(['user', 'id']));
    }
```

#### Mengecek apakah user sudah terautentikasi

Kita dapat menggunakan metode `check` pada `Auth` facade yang akan mereturn `true` jika user terautentikasi.

```php
use Illuminate\Support\Facades\Auth;

if (Auth::check()) {
    // The user is logged in...
}
```

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

##### Remember me
Kita dapat menambahkan argumen kedua pada metode `attempt` untuk mengimplementasikan fitur `remember me`. Jika nilainya `true`, maka Laravel akan menjaga user tetap terautentikasi sampai user melakukan logout. Fitur ini menggunakan kolom `remember_token` pada tabel `users`.

```php
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }
```

##### Menambahkan guard

Kita dapat menggunakan guard yang tersedia dalam mengautentikasi user. Dengan begini, kita dapat meengatur autentikasi untuk berbagai bagian yang berbeda dengan menggunakan model atau tabel yang berbeda.

List dari guard yang tersedia dapat dilihat pada file `auth.php` dalam folder config.

```php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],
```
Berikut adalah contoh penggunaan guard `web`.

```php
        if (Auth::guard('web')->attempt($credentials, true)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }
```

##### Throttling

Laravel Breeze sudah mengimplementasikan throttling, yaitu jika user memasukkan data yang salah untuk login secara berulang kali, maka user tidak bisa melakukan login selama waktu yang ditentukan. Berikut adalah cuplikan throttling pada Laravel Breeze yang diimplementasikan pada `LoginRequest.php`.

```php
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
```


#### 2. HTTP

Autentikasi dengan HTTP atau HTTP Basic Authentication menyediakan cara autentikasi tanpa perlu setting halaman login. Caranya adalah dengan menggunakan middleware `auth.basic` pada rute yang diinginkan. Middleware ini sudah termasuk dalam framework Laravel sehingga tidak perlu dibuat sendiri.

```php
Route::get('/profile', function() {
    return view('profile');
})->middleware('auth.basic')->name('profile');
```

Berikut adalah tampilan login menggunakan HTTP. Secara default, kolom `email` pada tabel `users` digunakan sebagai `username` di sini.

![authentication 2](/Laravel-authentication-and-authorization/img/authentication-2.png)


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
berikut adalah tampilan halaman konfirmasi password.

![authentication 6](/Laravel-authentication-and-authorization/img/authentication-6.png)

### Langkah keenam : Custom guards

Untuk membuat custom guard, kita dapat menggunakan metode `extend` dari `Auth` facade. Metode ini dipanggil pada `service provider`. Berikut adalah code untuk menambahkan custom guard pada `AuthServiceProvider`.

```php
<?php

namespace App\Providers;

use App\Services\Auth\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('jwt', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...

            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });
    }
}
```

setelah custom guard didefinisikan, maka selanjutnya guard dapat direferensikan melalui file `auth.php` pada folder config.

```php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

### Langkah ketujuh : Custom user providers

Custom user provider dibutuhkan untuk membuat user provider autentikasi sendiri, yang mana biasa dibutuhkan apabila kita tidak menggunakan database relasional untuk menyimpan data. Digunakan metode `provider` dari facade `Auth` untuk membuat custom user provider.

```php
<?php

namespace App\Providers;

use App\Extensions\MongoUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('mongo', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new MongoUserProvider($app->make('mongo.connection'));
        });
    }
}
```

setelah itu, provider diregistrasi pada `auth.php`.

```php
'providers' => [
    'users' => [
        'driver' => 'mongo',
    ],
],
```
selanjutnya provider dapat direferensikan pada konfigurasi `guards`.

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

### Langkah kedelapan : Contract pada autentikasi

Interface berikut digunakan untuk mekanisme autentikasi Laravel dapat bekerja sesuai dengan data yang ada.

#### User Provider Contract
```php
<?php

namespace Illuminate\Contracts\Auth;

interface UserProvider
{
    public function retrieveById($identifier);
    public function retrieveByToken($identifier, $token);
    public function updateRememberToken(Authenticatable $user, $token);
    public function retrieveByCredentials(array $credentials);
    public function validateCredentials(Authenticatable $user, array $credentials);
}
```

- `retrieveByID` : fungsi untuk menerima key yang merepresentasikan user.
- `retrieveByToken` : fungsi yang mengambil data user berdasarkan identifier dan `remember me` token.
- `updateRememberToken` : mengupdate `remember_token` user dengan `$token` baru. Token baru ini diberikan setelah sukses autentikasi dengan `remember me` atau setelah user logout.
- `retrieveByCredentials` : menerima array berisi credentials dari `Auth::attempt` saat mencoba autentikasi.
- `validateCredentials` : membandingkan `$user` dengan `$credentials` yang diberikan untuk proses autentikasi.

#### Authenticable Contract

User provider mereturn implementasi dari interface ini dari metode `retrieveById`, `retrieveByToken`, dan `retrieveByCredentials`

```php
<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable
{
    public function getAuthIdentifierName();
    public function getAuthIdentifier();
    public function getAuthPassword();
    public function getRememberToken();
    public function setRememberToken($value);
    public function getRememberTokenName();
}
```

- `getAuthIdentifierName` : mereturn nama dari primary key user.
- `getAuthIdentifier` : mereturn primary key user.
- `getAuthPassword` : mereturn password user setelah di-hash.
- `getRememberToken`, `setRememberToken`, `getRememberTokenName` : berkaitan dengan token untuk fitur `remember me`.

### Events

Terdapat sejumlah events pada proses autentikasi yang dapat disambungkan dengan listener pada `EventServiceProvider`.

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Illuminate\Auth\Events\Registered' => [
        'App\Listeners\LogRegisteredUser',
    ],

    'Illuminate\Auth\Events\Attempting' => [
        'App\Listeners\LogAuthenticationAttempt',
    ],

    'Illuminate\Auth\Events\Authenticated' => [
        'App\Listeners\LogAuthenticated',
    ],

    'Illuminate\Auth\Events\Login' => [
        'App\Listeners\LogSuccessfulLogin',
    ],

    'Illuminate\Auth\Events\Failed' => [
        'App\Listeners\LogFailedLogin',
    ],

    'Illuminate\Auth\Events\Validated' => [
        'App\Listeners\LogValidated',
    ],

    'Illuminate\Auth\Events\Verified' => [
        'App\Listeners\LogVerified',
    ],

    'Illuminate\Auth\Events\Logout' => [
        'App\Listeners\LogSuccessfulLogout',
    ],

    'Illuminate\Auth\Events\CurrentDeviceLogout' => [
        'App\Listeners\LogCurrentDeviceLogout',
    ],

    'Illuminate\Auth\Events\OtherDeviceLogout' => [
        'App\Listeners\LogOtherDeviceLogout',
    ],

    'Illuminate\Auth\Events\Lockout' => [
        'App\Listeners\LogLockout',
    ],

    'Illuminate\Auth\Events\PasswordReset' => [
        'App\Listeners\LogPasswordReset',
    ],
];
```
