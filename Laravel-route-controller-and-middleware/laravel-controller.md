# Laravel Controller

## Daftar Isi

-   [Laravel Controller](#laravel-controller)
    -   [Daftar Isi](#daftar-isi)
    -   [Latar Belakang Topik](#latar-belakang-topik)
    -   [Konsep-Konsep](#konsep-konsep)
    -   [Langkah-Langkah Tutorial](#langkah-langkah-tutorial)
        -   [Langkah Pertama](#langkah-pertama)
        -   [Langkah Kedua](#langkah-kedua)
        -   [Langkah Ketiga](#langkah-ketiga)

## Latar Belakang Topik

Laravel Controller merupakan salah satu bagian dimana seluruh fungsional web dibuat. Pada Controller dilakukan pengaturan untuk mengakses **Model** terkait dengan **Database** dan juga bagaimana mengirimkan datanya ke **View** dalam bentuk response.

## Konsep-Konsep

Salah satu contoh aktivitas pada controller adalah aktivitas CRUD (Create, Read, Update, Delete).

[![konsep-controller.png](https://i.postimg.cc/cJJhZ7xv/konsep-controller.png)](https://postimg.cc/QFR1JTWD)

## Langkah-Langkah Tutorial

Ada 2 cara dalam membuat controller pada laravel. Yang pertama, dengan cara membuat langsung file controller barunya di dalam folder `app/Http/Controllers`. Sedangkan cara yang kedua, dengan menggunakan perintah php artisan dari laravel.

### Langkah Pertama

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

### Langkah Kedua

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
| Verb | URL | Action | Route Name |
|-----------|----------------------|---------|----------------|
| GET | /dosen | index | dosen.index |
| GET | /dosen/create | create | dosen.create |
| POST | /dosen | store | dosen.store |
| GET | /dosen/{Dosen} | show | dosen.show |
| GET | /dosen/{Dosen}/edit | edit | dosen.edit |
| PUT/PATCH | /dosen/{Dosen} | update | dosen.update |
| DELETE | /dosen/{Dosen} | destroy | dosen.destroy |

### Langkah Ketiga

Penggunaan controller yang sederhana, controller dapat dipanggil melalui route pada file `web.php` pada direktori : `laravel-tutorial/routes/web.php`.
Disini kita akan menambahkan route baru untuk memanggil controller. Perhatikan syntax berikut :

```php
Route::get('/dosen', [DosenController::class, 'index']);
```

dan tambahkan `use App\Http\Controllers\DosenController;` pada file web.php dalam routes

Maksud dari syntax di atas adalah pada saat URL “dosen” di akses, maka kita memerintahkan untuk menjalankan method/function `index` yang ada dalam controller `DosenController`. Karena tadi kita membuat file controller tanpa template resource method controller yang tersedia maka kita harus membuat method nya terlebih dahulu pada file controller `DosenController.php`

```php
public function index(){
    return "Halo ini adalah method index, dalam controller DosenController.";
}
```

Maka kita sudah dapat memanggil method/function index pada controller melalui route dosen. Saat dijalankan maka, controller tersebut akan mencetak apa yang direturn oleh function index pada file controller. Berikut adalah hasilnya dengan akses alamat yang dibuat oleh php artisan serve misal `http://127.0.0.1:8000/dosen` atau

[![1622094007401.jpg](https://i.postimg.cc/Gtpdx5dc/1622094007401.jpg)](https://postimg.cc/47jDJwYM)
