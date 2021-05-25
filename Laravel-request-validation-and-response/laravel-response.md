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
Perlu kita ingat bahwa sebagai besar metode respons dapat dirantai, memungkinkan konstruksi instance dengan respons yang lancar. Misalnyam kita dapat menggunakan metode header untuk menambahkan serangkaian header ke response sebelum mengirimkannya kembali ke pengguna.

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
Laravel menyertakan cache. Middleware headers, yang dapat digunakan untuk menyetel header Cache-Control dengan cepat untuk sekelompok rute. JIka etag ditentukan dalam daftar arahan, hash MD5 dari konten respons akan secara otomatis disetela sebagai pengenal ETag.

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




### Redirect
Redirect responses merupakan objek dari kelas ``Illuminate\Http\RedirectResponse`', ada banyak cara untuk melakukan generate objek tersebut. Cara yang paling mudah adalah dengan menggunakan global ``redirect`` helper
```php
Route::get('/dashboard', function () {
    return redirect('home/dashboard');
});
```
Terkadang kita juga perlu melakukan redirect ke lokasi sebelumnya, contohnya ketika form yang dikirim tidak valid. Untuk hal ini, kita dapat menggunakan fungsi global ``back`` helper. Karena fitur ini menggunakan session, maka harus dipastikan route yang memanggil fungsi ``back`` menggunakan ``web`` middleware group.
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


##### Populating Parameters Via Eloquent Models
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
