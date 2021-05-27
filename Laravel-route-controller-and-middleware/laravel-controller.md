LARAVEL ROUTE DASAR
# Laravel Route Dasar
## Latar Belakang Topik
Dalam mengakses sebuah web, route adalah bagian yang mengatur rute pada project aplikasi web. Hal paling mendasar pada route di Laravel adalah biasanya route menerima URI (sekuen karakter unik yang memberikan identifikasi terhadap teknologi web) serta closure (fungsi anonim). Penggunaan kedua hal tersebut akan memudahkan kita mengatur project Laravel nantinya.

Misal kita ingin membangun web. Tentu, agar lebih terorganisasi, kita ingin akses web kita dapat diakses selayaknya directory pada operating system. Pada contoh ini, kita akan membangun route di mana memberikan akses URI “/pegawai” yang menampilkan view welcome. Tentu untuk melakukan penggunaan itu, penggunaan route Laravel akan memudahkan dalam mengakses lokasi URI tersebut. Kita juga nantinya, dapat pula melakukan redirecting view misal dari “/employee” di mana employee dan pegawai memiliki arti yang sama agar bisa mengakses link pegawai pada akhirnya.

Banyaknya fitur tersebut lah yang akan kita pelajari pada konsep Laravel Route.

## Konsep-Konsep
Konsep dari Laravel Route ini akan terbagi menjadi dua, yakni route api dan route web. Route api ini secara otomatis akan menambahkan “/api” pada bagian awal router. Sedangkan, route web akan memberikan akses penuh terhadap route. Secara default keseluruhan file route ini dapat diakses pada directory “routes” tergantung apakah kita ingin mengakses web (`routes\web.php`) atau api (`routes\api.php`). Seluruh file pada route ini diatur secara otomatis oleh `App\Providers\RouteServiceProvider`.

## Langkah-Langkah Tutorial
### Langkah Pertama
Sebelum kita memulai project Laravel, kita dapat melakukan [instalasi Laravel](https://laravel.com/docs/8.x/installation). Terdapat banyak cara untuk menginstall project Laravel, namun di sini kita akan menggunakan composer.
```
composer create-project laravel/laravel laravel-tutorial

cd laravel-tutorial

php artisan serve
```
Nantinya, akan terbuat folder laravel-tutorial sebagai berikut.

Selain itu, setelah kita memasukkan command `php artisan serve` maka kita akan mendapatkan akses development server aplikasi Laravel seperti berikut.


Jika kita mengakses development server tersebut, maka kita dapatkan tampilan sebagai berikut.


### Langkah Kedua
Sekarang kita akan membangun contoh view sederhana untuk URI “/pegawai”. Di sini, kita mengakses `resources\views`. Akan terlihat file `welcome.blade.php`. Lakukan copy file tersebut dan hasilnya ganti menjadi `welcome-pegawai.blade.php`.



Setelah itu, ganti isi body menjadi:
```html
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0" style="font-size: 3em">
            Laravel Pegawai
        </div>
    </body>
```

### Langkah Ketiga
Setelah ini, kita mencoba untuk membangun router ke URI “/pegawai”. Di sini, mulanya kita membuat akses route pada file `routes\web.php`.


Dapat kita lihat bahwa di sini, terdapat class `Route` dengan method `get`. Ini merupakan method dalam melakukan akses HTTP terhadap web aplikasi Laravel. Dapat dilihat pula, parameter dari method ini sebagai berikut:
`‘/’` sebagai URI
`function()` sebagai Closure untuk menjalankan proses

Sekarang, kita menentukan [HTTP request method](https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods) yang diinginkan. Karena kita hanya menampilkan data view sederhana kita akan memakai `GET` sebagai request method-nya. Selanjutnya, kita tinggal menggunakan kode berikut.
```php
Route::get('/pegawai', function () {
    return view('welcome-pegawai');
});
```

Langkah Ketiga - versi 2
Adapun karena kita hanya menampilkan view, kita juga dapat menggunakan method `view` yang nantinya mengambil parameter URI dan nama file view.
```php
Route::view("/pegawai", "welcome-pegawai");
```

### Langkah Keempat
Sekarang kita coba memanggil command `php artisan serve`. Maka tampilannya akan menjadi sebagai berikut.


### Langkah Kelima
Sekarang kita akan membuat redirect dari “/employee” menuju route “/pegawa”. Untuk melakukan hal ini, kembali ke file `routes\web.php`. Di sini, kita akan menggunakan method `redirect` yang mengambil dua parameter, yakni URI asal dan URI tujuan.
```
Route::redirect("/employee", "/pegawai");
```

Jika kita menjalankan command `php artisan serve` dan mengakses URI “/employee”, maka kita akan diarahkan segera ke route “/pegawai”.

## Kesimpulan
Penggunaan Laravel Route dapat membantu penggunaan akses web menjadi lebih terorganisasi. Keseluruhan kustomisasi Laravel Route tinggal diakses pada directory `routes` dan kita dapat melihat bermacam kemudahannya. Selain itu, class `Route` menyediakan method yang lengkap untuk memberikan bermacam model request yang dapat diajukan oleh developer.

LARAVEL ROUTE DENGAN PARAMETER
# Laravel Route Dengan Parameter
## Latar Belakang Topik
Dalam perkembangannya, terkadang kita ingin menggunakan URI sebagai tambahan informasi/parameter dari pengguna. Walaupun ada cara pengiriman data dengan menggunakan method `POST`. Namun, penggunaan ini berfungsi untuk memodifikasi data Pada contoh kali ini kita akan belajar cara akses data yang kita letakkan untuk hanya melakukan request dari pengguna.

Misalkan dalam sebuah directory operating system kita terdapat folder “pegawai” yang terdiri atas banyak sekali file yang terdiri dari angka 1-tak terhingga. Tentu untuk membuka setiap file tersebut, tidak mungkin jika kita harus menulis satu per satu akses directory setiap file ke dalam catatan kita. Maka dari itu, diperlukan sebuah parameter tambahan yang berfungsi untuk mengorganisasi akses route ke setiap directory, tanpa harus menulis route untuk setiap file.

Maka dari itu, kita akan memanggil route “/pegawai/{id}” yang nantinya cukup menampilkan view nama route tersebut dan parameter yang diberikan. Selain itu, untuk mencegah user melakukan akses parameter id berupa karakter angka, maka kita juga akan membatasi ekspresi id yang diberikan. Terakhir, kita akan melakukan grouping route jika nantinya melakukan scale up terhadap seluruh route Laravel.

Banyaknya fitur tersebut lah yang akan kita pelajari pada konsep Laravel Route.

## Konsep-Konsep
Konsep dari Laravel Route dengan parameter ini adalah memberikan pengguna kemudahan dalam melakukan kustomisasi segmen pada route. Misal anggap saja dalam route terdapat “/pegawai/1” dan “/pegawai/2”. Dalam contoh sederhana, mungkin kita tinggal membuat fungsi route untuk masing-masing route tersebut. Namun, bagaimana jika route yang diberikan sampai “/pegawai/9999”. Tentu mustahil jika kita membuat fungsi sebanyak itu. Maka, dari itu, diperlukan parameter yang berfungsi menerima keseluruhan angka tersebut dan menjadikan sebuah route dengan request method khusus.

## Langkah-Langkah Tutorial

### Langkah Pertama
Untuk menambahkan parameter pada route, kita tinggal melakukannya pada file `routes\web.php`. Di sini, kita perlu menentukan model route yang akan kita pasang, contohnya “/pegawai/1”, dan seterusnya. Untuk memberi tahu Laravel bahwa angka 1 tersebut adalah parameter id, kita tinggal mengganti URI pada setiap method pada Route dengan “/pegawai/{id}”. Nantinya kita tinggal menambahkan `$id` sebagai parameter pada fungsi closure kita.

Kita cukup melihat nilai `$id` pegawai. Nantinya, kodingannya akan menjadi seperti berikut.
```php
Route::get("/pegawai/{id}", function ($id) {
    return "Pegawai dengan id: " . $id . ".";
});
```
Maka, tampilannya akan menjadi sebagai berikut.


### Langkah Kedua
Kini, permasalahan terjadi jika kita mengisi `$id` dengan string lain (bukan angka). Maka, id yang seharusnya hanya angka maka dapat menampilkan string.

Tentu hal tersebut menimbulkan permasalahan terbaru, jika id hanya terbatas pada angka. Untuk mengatasi hal tersebut, kita cukup menambahkan method `where` di mana mengambil string dari nama parameter dan [regular expression](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions/Cheatsheet) dari parameter. Jadinya sebagai berikut.

```
Route::get("/pegawai/{id}", function ($id) {
    return "Pegawai dengan id: " . $id . ".";
})->where('id', '[0-9]+');
```
Apabila kita mencoba mengakses kembali route tadi, kita akan dikembalikan pada laman 404.


Jika kita kesulitan dalam memahami regex, kita juga bisa menambahkan method khusus seperti `whereNumber` (hanya angka), `whereAlpha` (hanya alphabet), `whereAlphaNumeric` (hanya angka dan alphabet), atau `whereUuid` (hanya unsigned). Kodenya akan seperti berikut.
```
Route::get("/pegawai/{id}", function ($id) {
    return "Pegawai dengan id: " . $id . ".";
})->whereNumber('id');
```
Tampilannya pun akan sama dan memiliki pengembalian sama seperti di atas jika tidak sesuai.

### Langkah Kedua - versi 2
Kita juga dapat melakukan chaining terhadap route parameter semisal kita ingin mengambil lebih dari satu parameter. Misal kita ingin memanggil URI “/pegawai/{id}/city/{city}”. Jika kita ingin membatasi id hanya berupa angka dan city hanya berupa alphabet. Di sini, kita dapat memanggil sebagai berikut.
```
Route::get("/pegawai/{id}/city/{city}", function ($id, $city) {
    return "Pegawai dengan id: " . $id . ", dengan kota: " . $city . ".";
})->where(['id' => '[0-9]+', 'city' => '[a-z]+']);
```
Atau bisa diubah menjadi chaining method berikut.
```
Route::get("/pegawai/{id}/city/{city}", function ($id, $city) {
    return "Pegawai dengan id: " . $id . ", dengan kota: " . $city . ".";
})->whereNumber('id')->whereAlpha('city');
```

Tampilannya akan menjadi seperti berikut.

### Langkah Ketiga
Apabila route yang dimiliki semakin banyak, tentu kita akan kebingungan untuk mengatur semakin banyaknya route yang ada. Maka dari itu, diperlukan namanya route groups. Misal kita membangun route “/pegawai/view”, “/pegawai/{id}”, “/pegawai/name/{name}”, dan seterusnya. Ketiga route tersebut memiliki kesamaan yakni menggunakan prefix “pegawai”. Maka, kita tinggal memakai prefix “pegawai” serta melakukan group terhadap seluruh prefix “pegawai”.

Di sini, kita cukup memakai method `prefix` dan method `group` untuk melakukan hal di atas. Method `prefix` mengambil parameter prefix URI dan method `group` memanggil fungsi callback.
```php
Route::prefix("/pegawai")->group(function () {
    Route::get("/view", function () {
        return "Pegawai Laravel.";
    });
    Route::get("/{id}", function ($id) {
        return "Pegawai dengan id: " . $id . ".";
    })->whereNumber('id');
    Route::get("/name/{name}", function ($name) {
        return "Pegawai dengan name: " . $name . ".";
    })->whereAlpha('name');
});
```

Selanjutnya, kita tinggal mengakses URI tersebut.

LARAVEL MIDDLEWARE
# Laravel Middleware
## Latar Belakang Topik


Dalam perkembangannya, terkadang kita ingin menggunakan URI sebagai tambahan informasi/parameter dari pengguna. Walaupun ada cara pengiriman data dengan menggunakan method `POST`. Namun, penggunaan ini berfungsi untuk memodifikasi data Pada contoh kali ini kita akan belajar cara akses data yang kita letakkan untuk hanya melakukan request dari pengguna.

Misalkan dalam sebuah directory operating system kita terdapat folder “pegawai” yang terdiri atas banyak sekali file yang terdiri dari angka 1-tak terhingga. Tentu untuk membuka setiap file tersebut, tidak mungkin jika kita harus menulis satu per satu akses directory setiap file ke dalam catatan kita. Maka dari itu, diperlukan sebuah parameter tambahan yang berfungsi untuk mengorganisasi akses route ke setiap directory, tanpa harus menulis route untuk setiap file.

Maka dari itu, kita akan memanggil route “/pegawai/{id}” yang nantinya cukup menampilkan view nama route tersebut dan parameter yang diberikan. Selain itu, untuk mencegah user melakukan akses parameter id berupa karakter angka, maka kita juga akan membatasi ekspresi id yang diberikan. Terakhir, kita akan melakukan grouping route jika nantinya melakukan scale up terhadap seluruh route Laravel.

Banyaknya fitur tersebut lah yang akan kita pelajari pada konsep Laravel Route.

## Konsep-Konsep
Konsep dari Laravel Route dengan parameter ini adalah memberikan pengguna kemudahan dalam melakukan kustomisasi segmen pada route. Misal anggap saja dalam route terdapat “/pegawai/1” dan “/pegawai/2”. Dalam contoh sederhana, mungkin kita tinggal membuat fungsi route untuk masing-masing route tersebut. Namun, bagaimana jika route yang diberikan sampai “/pegawai/9999”. Tentu mustahil jika kita membuat fungsi sebanyak itu. Maka, dari itu, diperlukan parameter yang berfungsi menerima keseluruhan angka tersebut dan menjadikan sebuah route dengan request method khusus.

## Langkah-Langkah Tutorial

### Langkah Pertama

LARAVEL CONTROLLER
# Laravel Controller
## Pengenalan
Laravel Controller merupakan salah satu bagian dimana seluruh fungsional web dibuat. Pada Controller dilakukan pengaturan untuk mengakses **Model** terkait dengan **Database** dan juga bagaimana mengirimkan datanya ke **View** dalam bentuk response. 
Salah satu contoh aktivitas pada controller adalah aktivitas CRUD (Create, Read, Update, Delete).

[![konsep-controller.png](https://i.postimg.cc/cJJhZ7xv/konsep-controller.png)](https://postimg.cc/QFR1JTWD)

## Cara Membuat Controller pada laravel
Ada 2 cara dalam membuat controller pada laravel. Yang pertama, dengan cara membuat langsung file controller barunya di dalam folder `app/Http/Controllers`. Sedangkan cara yang kedua, dengan menggunakan perintah php artisan dari laravel.
 
###  Cara Pertama
Kita langsung membuat file controller baru pada laravel dengan membuat langsung filenya di dalam folder controllers. Di sini kita akan mengikuti format penulisan pada laravel, jadi nama file controllernya dibuat dengan huruf besar di awal Controllernya. Misalnya kita akan membuat controller pegawai, buat file baru dengan nama `DosenController.php` dalam folder controllers. Berikut adalah lokasi directorinya pada kasus ini : **laravel-tutorial/app/Http/Controllers/DosenController.php**

[![1622092709307.jpg](https://i.postimg.cc/c4ZQ07Td/1622092709307.jpg)](https://postimg.cc/yWQJTZwG)

Perhatikan syntax di bawah ini :
```php
<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
class DosenController extends Controller
{
    //di sini isi controller dosen
}
```

Pada syntax tersebut, di deklarasikan class controller DosenController, dengan wajib extends Controller dari laravel.

### Cara Kedua
Cara kedua, kita dapat membuat file controller baru seperti pada cara pertama dengan cara yang lebih mudah. Caranya dengan memanfaatkan php artisan yang terdapat pada laravel. Dengan fitur ini kita dapat membuat serta mengontrol project kita. **php artisan** adalah fitur unggulan yang ada pada laravel, yang dibuat untuk memudahkan kita dalam pengembangan menggunakan laravel.
**php artisan** untuk membuat file controller baru dapat dibuat dengan syntax berikut yang diketik melalui terminal atau command prompt (CMD)

```php
php artisan make:controller DosenController
```
Perintah `make:controller` di atas adalah perintah dari php artisan untuk membuat controller baru sesuai nama yang diinginkan. Pada kasus ini file controller tersebut bernama `DosenController`. Maka file controller `DosenController.php` akan dibuat secara otomatis.
Selain itu, dengan memanfaatkan `php artisan make:controller` ini kita dapat langsung menulis kode sesuai template **Resource Controller** pada dalam controller dengan menambahkan `--resource` setelah nama file controller :

```php
php artisan make:controller DosenController --resource
```
Berikut adalah DosenController jika dibuat dengan resource :
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
```
Berikut adalah method yang dapat dilakukan oleh Resource Controller :
| Verb      | URL                  | Action  | Route Name     |
|-----------|----------------------|---------|----------------|
| GET       | /dosen              | index   | dosen.index   |
| GET       | /dosen/create       | create  | dosen.create  |
| POST      | /dosen              | store   | dosen.store   |
| GET       | /dosen/{Dosen}      | show    | dosen.show    |
| GET       | /dosen/{Dosen}/edit | edit    | dosen.edit    |
| PUT/PATCH | /dosen/{Dosen}      | update  | dosen.update  |
| DELETE    | /dosen/{Dosen}      | destroy | dosen.destroy |

## Cara Menggunakan controller
Penggunaan controller yang sederhana, controller dapat dipanggil melalui route pada file `web.php` pada direktori : `laravel-tutorial/routes/web.php`.
Disini kita akan menambahkan route baru untuk memanggil controller. Perhatikan syntax berikut :

```php
Route::get('/dosen', [DosenController::class, 'index']);
```
dan tambahkan `use App\Http\Controllers\DosenController;` pada file web.php dalam routes

Maksud dari syntax di atas adalah pada saat URL “dosen” di akses, maka kita memerintahkan untuk menjalankan method/function `index` yang ada dalam controller `DosenController`. Karena tadi  kita membuat file controller tanpa template resource method controller yang tersedia maka kita harus membuat method nya terlebih dahulu pada file controller `DosenController.php`

```php
public function index(){
    return "Halo ini adalah method index, dalam controller DosenController.";
}
```
Maka kita sudah dapat memanggil method/function index pada controller melalui route dosen. Saat dijalankan maka, controller tersebut akan mencetak apa yang direturn oleh function index pada file controller. Berikut adalah hasilnya dengan akses alamat yang dibuat oleh php artisan serve misal `http://127.0.0.1:8000/dosen` atau 

[![1622094007401.jpg](https://i.postimg.cc/Gtpdx5dc/1622094007401.jpg)](https://postimg.cc/47jDJwYM) 
