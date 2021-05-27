# Laravel-Queries

[Kembali](readme.md)

## Query Builder 

Query Builder merupakan salah satu cara untuk menjalankan query database dengan
lebih mudah, Query Builder juga telah dilengkapi dengan fitur keamanan untuk mencegah
terjadinya SQL Injextion (adalah sebuah aksi hacking yang dilakukan di aplikasi client dengan
cara memodifikasi perintah SQL yang ada di memori aplikasi client). Selain itu kita dapat
menggunakan query builder tanpa harus membuat model terlebih dahulu

Misal: jelaskan mengenai latar belakang, alasan penggunaan, dll.

## Konsep-konsep


Misal: jelaskan mengenai pengertian, konsep, alur, dll.

## Langkah-langkah tutorial

### Setting env

Untuk mempraktekan contoh dari latihan menampilkan data dengan QUERY BUILDER
pertama kita harus memeriksa file `.env` apakah projek sudah terhubung dengan database atau
belum. File ini terletak pada bagian luar projek laravel yang dibuat, dan pastikan pada bagian
mysql sudah terkonfigurasi seperti pada gambar dibawah:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=model_pbkke
DB_USERNAME=root
DB_PASSWORD=
```



### Ubah Controller dengan Database Query Builder

#### Mengambil Semua Baris Dari Tabel
 
dengan menggunakan metode `table()` yang disediakan oleh laravel pada `Illuminate\Support\Facades\DB;` yang akhirnya menampilkan hasil query menggunakan metode `get()`  

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Show a list of all of the application's cars.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = DB::table('cars')->get();

        return view('user.index', ['cars' => $cars]);
    }
}
```
metode `get()` akan mereturn/mengembalikan Illuminate\Support\Collectioninstance yang berisi hasil query di mana setiap hasil adalah turunan dari PHP stdClass objek
jadi nilai dapat diakses setiap kolom dengan mengakses kolom sebagai properti objek:

```php
use Illuminate\Support\Facades\DB;

$users = DB::table('users')->get();

foreach ($users as $user) {
    echo $user->name;
}
```

#### Mengambil Satu Baris / Kolom Dari Tabel ( Metode get(),value(),where(),first() ,find() )

Jika hanya perlu mengambil satu baris dari tabel database,  dapat menggunakan metode `first()`  . Metode ini akan mengembalikan satu stdClass objek

```php
$user = DB::table('users')->where('name', 'John')->first();

return $user->email;
```

Jika tidak membutuhkan seluruh baris, bisa juga  mengekstrak satu nilai dari sebuah record menggunakan metode `value()`. Metode ini akan mengembalikan nilai kolom secara langsung:

```php
$email = DB::table('users')->where('name', 'John')->value('email');
```

Untuk mengambil satu baris dengan nilai id kolomnya, gunakan metode find()

```php
$user = DB::table('users')->find(3);
```

#### Mengambil Daftar Nilai Kolom ( Metode Pluck() )

gunakan metode `pluck()` jika ingin mengambil sebuah data yang berisi nilai-nilai dari satu kolom
Dalam contoh ini, akan mengambil kumpulan judul pengguna:

```php
use Illuminate\Support\Facades\DB;

$titles = DB::table('users')->pluck('title');

foreach ($titles as $title) {
    echo $title;
}
```
Anda dapat menentukan kolom yang harus digunakan oleh koleksi hasil sebagai kuncinya dengan memberikan argumen kedua ke metode `pluck()`:

```php
$titles = DB::table('users')->pluck('title', 'name');

foreach ($titles as $name => $title) {
    echo $title;
}
```
