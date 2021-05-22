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
