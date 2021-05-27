# Laravel Response

[Kembali](readme.md)

## Latar belakang topik
HTTP (Hypertext Transfer Protocol) merupakan protokol yang digunakan untuk mengirim data melalui web. Client mengirimkan HTTP request ke server kemudian server akan mengembalikan respon ke client. Respon itu dapat berupa informasi status penyelesaian request ataupun berisi konten pada body dari response. Jadi agar client dan server dapat berkomunikasi aplikasi web memiliki HTTP request untuk membaca data dari client dan HTTP response untuk membaca.

## Konsep-konsep
HTTP Response yaitu dimana server akan merespon permintaan yang dikirim oleh client.
### Membuat Responses
#### String & Arrrays
Semua routes dan controllers harus me-return response untuk dikirim kembali ke browser pengguna. Laravel menyediakan berbagai macam cara untuk me-return respnse. Cara yang paling dasar adalah dengan me-return string dari sebuah route atau controller. Laravel akan secara otomatis mengubah string tersebut menjadi full HTTP response.

```php
Route::get('/', function () {
    return 'Hello World';
});
```
Selain itu, kita juga dapat me-return array. Laravel akan secara otomatis mengubah array tersebut menjadi JSON response.

```php
Route::get('/', function () {
    return [1, 2, 3];
});
```

#### Response Objects
Pada umumnya kita tidak hanya me-return string dan array saja, melainkan keseluruhan objek dari ``Illuminate\Http\Response ataupun view``. Dengan me-return keseluruhan objek dari ``Response`` kita dapat menyesuaikan status code dari HTTP response dan headers sesuai dengan kebutuhan. Sebuah objek ``Response`` akan mewarisi kelas ``Symfony\Component\HttpFoundation\Response`` yang menyediakan berbagai macam method untuk membuat HTTP response.

```php
Route::get('/home', function () {
    return response('Hello World', 200)
                  ->header('Content-Type', 'text/plain');
});
```

#### Eloquent Models & Collections
Kita juga dapat mengembalikan model dan koleksi Eloquent ORM langsung dari rute dan pengontrol. Saat kita melakukannya, Laravel akan secara otomatis mengonversi model dan koleksi menjadi response JSON. 

```php
use App\Models\User;

Route::get('/user/{user}', function (User $user) {
    return $user;
});
```  

### Attaching Headers To Responses
Perlu kita ingat bahwa sebagai besar metode respons dapat dilanjutkan secara berantai (chainable), memungkinkan konstruksi instance dengan respons yang lancar. Misalnya kita dapat menggunakan metode header untuk menambahkan serangkaian header ke response sebelum mengirimkannya kembali ke pengguna.

```php
return response($content)
            ->header('Content-Type', $type)
            ->header('X-Header-One', 'Header Value')
            ->header('X-Header-Two', 'Header Value');
```

Atau kita juga bisa menggunakan method withHeaders untuk menentukan sebuah array headers ditambahkan ke response.

```php
return response($content)
            ->withHeaders([
                'Content-Type' => $type,
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
```

#### Cache Control Middleware
Laravel menyertakan middleware ``cache.headers``, yang dapat digunakan untuk menyetel header ``Cache-Control`` dengan cepat untuk sekelompok rute. Jika ``etag`` ditentukan dalam list arahan, hash MD5 dari konten respons akan secara otomatis disetel sebagai pengenal ETag.

```php
Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::get('/privacy', function () {
        // ...
    });

    Route::get('/terms', function () {
        // ...
    });
});
```

### Attaching Cookies To Responses
Kita juga dapat melampirkan cookie ke objek ``Illuminate\Http\Responses`` menggunakan method ``cookie``. Kita juga dapat melakukan <i>passing</i> nama, nilai dan jumlah menit cookie yang dianggap valid untuk metode ini:

```php
return response('Hello World')->cookie(
    'name', 'value', $minutes
);
```
Cookie method juga menerima beberapa argument lagi meskupun jarang digunakan. Secara umum, argumen ini memiliki tujuan dan arti yang sama dengan argumen yang akan diberikan ke method ``setcookie`` pada PHP native

```php
return response('Hello World')->cookie(
    'name', 'value', $minutes, $path, $domain, $secure, $httpOnly
);
```

Jika kita ingin memastikan bahwa cookie dikirim dengan respons keluar tetapi kita belum memiliki instance dari respon tersebut, kita dapat menggunakan ``Cookie`` facade untuk mengantrikan cookie yang dibuat ke response saat dikirim. Method ``queue`` ini menerima argumen yang diperlukan untuk membuat instance cookie. Cookie ini akan dilampirkan ke respons keluar sebelum dikirim ke browser 

```php
use Illuminate\Support\Facades\Cookie;

Cookie::queue('name', 'value', $minutes);
```

#### Generating Cookie Instances
Jika kita ingin membuat objek ``Symfony\Component\HttpFoundation\Cookie`` yang dapat diberikan ke objek response di lain waktu, kita dapat menggunakan global cookie helper. Cookie ini tidak akan mengirim kembali ke klien kecuali jika mengirimkannya.

```php
$cookie = cookie('name', 'value', $minutes);
return response('Hello World')->cookie($cookie);
```

#### Expiring Cookies Early
Kita juga dapat menghapus cookie dengan mengakhirnya melalui metode withoutCookie.
```php
return response('Hello World')->withoutCookie('name');
```

Jika kita belum memiliki respons keluar, kita dapat menggunakan method ``expire`` dari Cookie facade untuk menghentikan sebuah cookie

```php
Cookie::expire('name');
```

### Cookies & Enxryption
Secara default, semua cokkie yang dihasilkan oleh laravel dienkripsi dan tidak dapat diubah atau dibaca oleh klien. Jika kita ingin menonaktifkan enkripsi untuk subset cookie yang dibuat aplikasi kita. Kita bisa menggunakan ``$except``  dari ``App\Http\Middleware\EncryptCookies``. Lokasi dari middleware terdapat pada folder ``app/Http/Middleware``.


```php
/**
 * The names of the cookies that should not be encrypted.
 *
 * @var array
 */
protected $except = [
    'cookie_name',
];
```

### Redirect
Redirect responses merupakan objek dari kelas Illuminate\Http\RedirectResponse, ada banyak cara untuk melakukan generate objek tersebut. Cara yang paling mudah adalah dengan menggunakan global redirect helper
```php
Route::get('/dashboard', function () {
    return redirect('home/dashboard');
});
```
Terkadang kita juga perlu melakukan redirect ke lokasi sebelumnya, contohnya ketika form yang dikirim tidak valid. Untuk hal ini, kita dapat menggunakan fungsi global helper. Karena fitur ini menggunakan session, maka harus dipastikan route yang memanggil fungsi menggunakan web middleware group.

```php
Route::post('/user/profile', function () {
    // Validate the request...

    return back()->withInput();
});
```
catatan: method ``withInput`` akan melakukan flash data input yang masuk ke session 
#### Redirect To Named Routes
Untuk melakukan redirect ke suatu route dengan nama tertentu dapat dilakukan dengan cara berikut:
```php
return redirect()->route('login');
```
Jika route memiliki parameter, kita dapat melakukan <i>passing</i> paramter melalui argumen kedua 
```php
return redirect()->route('profile', ['id' => 1]);
```


- <b>Populating Parameters Via Eloquent Models</b>
Jika kita melakukan redirect ke suatu route dengan "ID" pada sebuah Eloquent model, kita dapat melakukan <i>passing</i> model tersebut secara langsung. ID akan diekstrak secara otomatis.
```php
// For a route with the following URI: /profile/{id}

return redirect()->route('profile', [$user]);
```

#### Redirecting To Controller Actions
Kita juga dapat melakukan redirect ke controller actions. Untuk melakukan hal tersebut, kita harus melakukan <i>passing</i> controller dan nama action pada method ``action``
```php
use App\Http\Controllers\UserController;

return redirect()->action([UserController::class, 'index']);
```
Jika controller route membutuhkan parameter, kita dapat melakukan <i>passing</i> parameter pada argumen kedua 
```php
return redirect()->action(
    [UserController::class, 'profile'], ['id' => 1]
);
```

#### Redirecting To External Domains
Untuk melakukan redirect ke luar domain dari aplikasi kita dapat menggunakan method ``away``.
```php
return redirect()->away('https://www.google.com');
```

#### Redirecting With Flashed Session Data
Melakukan redirecting dan flash data ke session pada waktu yang sama.
```php
Route::post('/user/profile', function () {
    // ...

    return redirect('dashboard')->with('status', 'Profile updated!');
});
```
Untuk menampilkan pesan yang telah di-flash melalui ``blade`` syntax
```php
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
```

### Other Response Types
Response helper dapat digunakan untuk menghasilkan contoh respons lainnya. Ketika response helper dipanggil tanpa argumen, implementasi dari contract ``Illuminate\Contracts\Routing\ResponseFactory`` akan direturn. Kontrak ini memberikan beberapa metode bermanfaat untuk menghasilkan tanggapan.

#### View Responses
Jika kita membutuhkan kontrol atas status dan header respons, tetapi juga perlu mengembalikan sebuah view sebagai konten respons. Kita bisa menggunakan method ``view`` ini :

```php
return response()
            ->view('hello', $data, 200)
            ->header('Content-Type', $type);
```

Tentu saja, jika kita tidak perlu meneruskan kode status HTTP khusus atau header khusus, Kita dapat menggunakan globa view helper function.


#### JSON Responses
Method ``json`` akan secara otomatis mengatur header ``Content-Type`` ke ``application/json``, serta mengonversi array yang diberikan ke JSON menggunakan fungsi PHP ``json_encode``


```php
return response()->json([
    'name' => 'Abigail',
    'state' => 'CA',
]);
```

Jika kita membuat sebuah JSONP response, kita bisa menggunakan method ``json`` yang dikombinasi dengan method ``withCallback``:

```php
return response()
            ->json(['name' => 'Abigail', 'state' => 'CA'])
            ->withCallback($request->input('callback'));
```

#### File Downloads
Method ``download`` dapat digunakan untuk menghasilkan respons yang memaksa browser pengguna untuk mengunduh file dengan path yang telah diberikan. Method ini menerima nama file sebagai argumen kedua untuk metode tersebut, yang akan menentukan nama file yang dilihat oleh pengguna yang mengunduh file. Terakhir, kita dapat melakukan <i>passing</i> sebuah array Headers HTTP sebagai argumen ketiga.

```php
return response()->download($pathToFile);
return response()->download($pathToFile, $name, $headers);
```

- <b>Streamed Downloads</b>
Terkadang kita mungkin ingin mengubah respons string dari operasi tertentu menjadi respons yang dapat diunduh tanpa harus menulis konten operasi. Kita dapat menggunakan streamDownload dalam skenario ini. Metode ini menerima callback nama file dan sebuah array opsional header sebagai argumennya.

```php
use App\Services\GitHub;

return response()->streamDownload(function () {
    echo GitHub::api('repo')
                ->contents()
                ->readme('laravel', 'laravel')['contents'];
}, 'laravel-readme.md');
```


#### File Responses
Method file memungkinkan untuk menampilkan file, seperti gambar atau pdf, langsung di browser pengguna alih-alih memulai pengunduhan. Metode ini menerima jalur ke file sebagai argumen pertamanya dan sebuah array dari headers sebagai argumen yang kedua.

```php
return response()->file($pathToFile);

return response()->file($pathToFile, $headers);
```

### Response Macros
Jika kita ingin menentukan respons kustom yang bisa kita gunakan kembali di berbagai route dan controllers, kita bisa menggunakan method ``macro`` pada ``Response`` facade. Biasanya kita harus memanggil method dari method ``boot`` pada salah satu service provider dari aplikasi kita contohnya ``App\Providers\AppServiceProvider``:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('caps', function ($value) {
            return Response::make(strtoupper($value));
        });
    }
}
```

Macro function menerima nama sebagai argumen pertamanya dan penutupan sebagai argumen keduanya. Macro's closure akan dijalankan saat memanggil nama macro dari implementasi ResponseFactory atau response helper.


```php
return response()->caps('foo');
```


## Langkah-langkah tutorial

### Pembuatan Basic Response
Kita ingin membuat membuat sebuah response langsung ke web dengan mengatur routenya dan langsung melakukan pengembalian berupa sebuah kalimat langsung di web.

```php
Route::get('/basic', function(){
    return 'Hallo ngab, coba basic';
});
```
![image1](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/basic.jpg)


### Pembuatan Response Object
Jika kita ingin mengembalikan string atau array sederhana dari tindakan route. kita akan kembali ke contoh atau tampilan Illuminate/Http/Response.

```php
Route::get('/header', function(){
    return response('Hallo', 200)->header('Content-Type','text/html');
});
```
![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/header.jpg)

### Pembuatan Attach Cookie
Metode ini digunakan pada saat di user session disebuah web aplikasi. Cookie dapat dibuat oleh global cookie helper di laravel. Dan cookie adalah asebuah instance dari Symfony\Component\HttpFoundation\Cookie. Cookie dapat kita lampirkan ke response menggunakan metode withcookie(). Cookie yang dihasilkan oleh laravel dienkripsi sehingga tidak dapat dimodifikasi ataupun dibaca oleh klien.

```php
Route::get('/header-cookie', function(){
    return response('Hallo', 200)
    ->header('Content-Type','text/html')
    ->withcookie('name','Fitrah Arie');
});
```

![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/header-cookie.jpg)

### Json Response
Berikut merupakan cara menampilkan respons dalam bentuk Json.
```php
Route::get('/json', function(){
    return response()->json([
        'Nama1' => 'Fitrah',
        'Nama2' => 'Ryan'
    ]);
})
```

![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/json.jpg)

### Attaching Cookies to Responses
Metode ini memungkinkan kita untuk dengan mudah melampirkan cookie ke respons. Kita dapat menggunakan metode cookie untuk menghasilkan cookie dan dengan lancara melampirkannya ke contoh respons berikut

```php
Route::get('/cookie', function () {
    $content = 'Hello World';
    $type = 'text/plain';
    $minutes = 1;
    return response($content)
                ->header('Content-Type', $type)
                ->cookie('name', 'value', $minutes);
});
```
![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/cookie.jpeg)

### Redirect
Metode ini menggunakan redirect yang bertujuan mereturn route "/dashboard" ke root.

```php
Route::get('/dashboard',function(){
    return redirect('/');
});
```
Berikut pada saat pemanggilan 

![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/redirectbefore.jpeg)

Berikut setelah pemanggilan

![image](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Response/redirectafter.jpeg)
