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




####

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
