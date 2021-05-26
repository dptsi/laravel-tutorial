# Laravel Request
[Kembali](readme.md)

## Latar Belakang
Dalam Laravel tentunya ada beberapa metode yang dapat digunakan untuk mengirimkan data, baik menggunakan GET, POST, PUT, PATCH, DELETE, ataupun OPTIONS. Untuk memudahkan hal tersebut, diperlukan adanya suatu cara yang dapat melakukan pengaksesan data yang dikirim secara object-oriented. Oleh karena itu, Laravel memiliki kelas ``Illuminate\Http\Request``


## Konsep
Kelas ``Illuminate\Http\Request`` yang dimiliki laravel berfungsi menyediakan interaksi ke request HTTP yang sedang ditangani saat ini secara object-oriented termasuk juga mengambil input, cookies, dan files yang dikirimkan bersama request tersebut.

### Interaksi dengan Request
Untuk mendapatkan objek dari request HTTP saat ini melalui dependency injection, kita harus menambahkan <i>type hinting</i> ``Illuminate\Http\Request``. Objek request yang masuk akan secara otomatis dimasukkan ke Laravel service container. Pada umumnya hal ini dilakukan dengan statement ``use``.
```
use Illuminate\Http\Request;
```
#### Dependency Injection & Route Parameters
Jika kita juga menginginkan menerima input dari route, kita harus mencantumkan parameter route setelah dependensi lainnya. Sebagai contoh, jika kita memiliki route sebagai berikut
```php
use App\Http\Controllers\UserController;

Route::put('/user/{id}', [UserController::class, 'update']);
```
maka kita dapat mengakses ``id`` dengan cara berikut
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
```
### Request Path & Method
Objek dari ``Illuminate\Http\Request`` menyediakan berbagai macam fungsi untuk memeriksa request HTTP yang masuk dan meng-extends ``Symfony\Component\HttpFoundation\Request``. Berikut ini adalah beberapa fungsi penting yang sering digunakan:
#### Mengambil path dari request
Method ``path`` akan mengembalikan informasi mengenai path dari request. Jika request yang masuk menargetkan pada ``http://example.com/foo/bar`` fungsi ini akan mengembalikan ``/foo/bar``
```php
$uri = $request->path();
```

#### Memeriksa Path/Route dari request
Method ``is`` akan memverifikasi apakah path request yang masuk sesuai dengan pattern yang diberikan.
```php
if ($request->is('admin/*')) {
    //
}
```

Method ``routeIs`` akan memverifikasi apakah request yang masuk memiliki nama route sesuai dengan pattern yang diberikan
```php
if ($request->routeIs('admin.*')) {
    //
}
```

#### Mengambil URL dari request
Dapat menggunakan method ``url`` atau ``fullUrl``. Method  ``url`` akan mengembalikan URL tanpa query string sedangkan method ``fullUrl`` akan mengembalikan URL dengan query string.
```php
$url = $request->url();

$urlWithQueryString = $request->fullUrl();
```

#### Mengambil method dari request
Dapat menggunakan method ``method`` yang akan mengembalikan HTTP verbs (HTTP request method) dan bisa juga menggunakan method ``isMethod`` yang akan memverifikasi apakah method dari request sesuai dengan string yang diberikan.
```php
$method = $request->method();

if ($request->isMethod('post')) {
    //
}
```

### Request Headers
Dengan menggunakan method ``header`` kita dapat mendapatkan request header. Jika request tidak memiliki header, method ini akan mengembalikan nilai ``null``.  Namun method ini juga memiliki parameter opsional yang akan di-return ketika request tidak memiliki header.
```php
$value = $request->header('X-Header-Name');

$value = $request->header('X-Header-Name', 'default');
```
Selain itu, juga ada method ``hasHeader`` untuk mengecek apakah request memiliki header tertentu.
```php
if ($request->hasHeader('X-Header-Name')) {
    //
}
```
Terdapat juga method ``berarerToken`` yang akan mengembalikan <i>bearer token</i> dari header Authorization. Jika tidak ada akan mereturn ``null``.
```php
$token = $request->bearerToken();
```

### Request IP
Method ``ip`` akan mengembalikan <i>ip address</i> dari client yang membuat request pada aplikasi kita.
```php
$ipAddress = $request->ip();
```

### Input
#### Mengambil input
Berikut ini merupakan teknik pengambilan data input dari request yang sering digunakan 
- Mengambil semua data input</br>
Method ``all`` akan mengembalikan semua data input yang masuk dari request dalam array
```php
$input = $request->all();
```
- Mengambil sebuah data input</br>
Method ``input`` dapat digunakan untuk mengakses semua user input satu per satu. Pada method tersebut, kita juga dapat menambahkan default value pada argumen kedua yang akan di-return ketika nilai input yang diminta tidak ada pada request.
```php
$name = $request->input('name');

$name = $request->input('name', 'Sally');
```
Jika pada form terdapat input berupa array, maka kita dapat meggunakan notasi ``dot`` untuk mengakses array 
```php
$name = $request->input('products.0.name');

$names = $request->input('products.*.name');
```
- Mengambil input dari query string</br>
Method ``query`` akan mengembalikan nilai dari query string.
Jika tidak diberikan parameter maka method ini akan mengembalikan seluruh nilai query string dalam bentuk associative array
```php
$query = $request->query();
```
Jika diberikan sebuah parameter maka akan mengembalikan nilai dari parameter query string yang dimasukkan.
```php
$name = $request->query('name');
```
Jika nilai dari parameter query string tidak ada, maka parameter kedua akan di-return
```php
$name = $request->query('name', 'Helen');
```
- Mengambil nilai input dari JSON</br>
Dapat dilakukan dengan menggunakan method ``input``
```php
$name = $request->input('user.name');
```

- Mengambil input dari dynamic properties</br>
Jika pada form  memiliki field ``name``, kita juga dapat mengaksesnya dengan cara berikut
```php
$name = $request->name;
```

#### Old Input
Laravel memungkinkan kita menyimpan input yang lama untuk digunakan pada request berikutnya. Biasanya ini digunakan bersamaan dengan validation input.
- Flash input ke session</br>
Method ``flash`` akan melakukan flash input ke session
```php
$request->flash();
```
Jika hanya ingin melakukan flash pada sebagian request saja dapat menggunakan method ``flashOnly`` dan ``flashExcept``
```php
$request->flashOnly(['username', 'email']);

$request->flashExcept('password');
```
- Flash input yang diikuti redirecting</br>
Untuk melakukan flash input yang diikuti dengan redirect, dapat dilakukan dengan menggunakan method ``withInput``
```php
return redirect('form')->withInput();

return redirect()->route('user.create')->withInput();

return redirect('form')->withInput(
    $request->except('password')
);
```
- Mengambil old input
Method ``old`` akan mengambil data input yang sebelumnya telah di-flash dari session
```php
$username = $request->old('username');
```
Laravel menyediakan ``old`` helper global. Jadi ketika pada blade template, kita ingin menampilkan old input akan lebih mudah ketika menggunakan ``old`` helper tersebut.
```php
<input type="text" name="username" value="{{ old('username') }}">
```
#### Cookies
- Mengambil cookies dari request<br>
Semua cookies yang dibuat oleh Laravel dienkripsi dan ditandai dengan kode autentikasi, yang berarti cookies akan dianggap invalid jika diubah oleh client.
```php
$value = $request->cookie('name');
```

### Files
#### Mengambil file yang diupload
Kita dapat mengambil file yang diupload dengan dari objek ``Illuminate\Http\Request`` dengan menggunakan method ``file`` atau dynamic properties. Method ``file`` akan me-return objek ``Illuminate\Http\UploadedFile`` yang meng-extends kelas ``SplFileInfo`` pada PHP.
```php
$file = $request->file('photo');

$file = $request->photo;
```
Untuk mengecek apakah file terdapat pada request, dapat menggunakan method ``hasFile``
```php
if ($request->hasFile('photo')) {
    //
}
```
- <b>Validasi keberhasilan upload</b></br>
Terkadang kita juga harus melakukan verifikasi untuk memastikan tidak ada masalah dalam proses upload file. Hal ini dapat dilakukan dengan method ``isValid``
```php
if ($request->file('photo')->isValid()) {
    //
}
```
- <b>File path dan ekstensi</b></br>
Untuk mengetahui file path dan ekstensi dapat menggunakan method ``path`` dan ``extension``
```php
$path = $request->photo->path();

$extension = $request->photo->extension();
```
#### Menyimpan file yang diupload
Menggunakan method ``store`` dengan parameter path file relatif terhadap root direktori yang telah dikonfigurasi pada filesystem. Path file seharusnya tidak diisi dengan nama file karena ID unik akan dibuat secara otomatis yang digunakan sebagai nama file. Selain itu method ini juga dapat menerima argumen kedua yang bersifat opsional untuk mengatur nama disk yang digunakan untuk menyimpan file.
```php
$path = $request->photo->store('images');

$path = $request->photo->store('images', 's3');
```
Jika kita tidak ingin nama file di-generate secara otomatis, maka kita dapat menggunakan method ``storeAs`` dengan argumen path, nama file, dan disk
```php
$path = $request->photo->storeAs('images', 'filename.jpg');

$path = $request->photo->storeAs('images', 'filename.jpg', 's3');
```

## Langkah-langkah tutorial
Berikut ini merupakan contoh sederhana untuk mengimplementasikan method-method diatas
### Langkah pertama
Membuat form sederhana ``resources\views\formulir.blade.php``
```php
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Guest's Form</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card mt-5">
                    <h3 class="card-title text-center mt-5">
                        Guest's Form
                    </h3>
                    <div class="card-body">
                        <!-- menambahkan query string warna dengan value biru -->
                        <form method="POST" action="{{route('proses-form-guest',['id' => '99','warna' => 'biru'])}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>

                            <!-- Input dalam bentuk array dengan checkbox -->
                            <div class="form-group">
                                <label for="name">Hobby</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Bermain" id="Bermain" name="hobby[]">
                                    <label class="form-check-label" for="Bermain">
                                        Bermain
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Membaca" id="Membaca" name="hobby[]">
                                    <label class="form-check-label" for="Membaca">
                                        Membaca
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Tidur" id="Tidur" name="hobby[]">
                                    <label class="form-check-label" for="Tidur">
                                        Tidur
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control-file" id="gambar" name="gambar">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>
```


### Langkah kedua
Membuat controller ``app\Http\Controllers\GuestController.php``
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public static int $counter = 0;
    public function input()
    {
        return view('formulir');
    }

    public function proses(Request $request, int $id)
    {
        echo "</br>Path = " . $request->path();

        echo "</br>request patern == proses* ? " . ($request->is("proses*") ? 'true' : 'false');
        echo "</br>request route name ==  proses-form-guest? " . ($request->routeIs("proses-form-guest") ? 'true' : 'false');

        echo "</br>url = " . $request->url();
        echo "</br>full url = " . $request->fullUrl();

        echo "</br>Query string warna = " . $request->query('warna');

        echo "</br>Method = " . $request->method();
        echo "</br>Method == post? " . ($request->isMethod('post') ? 'true' : 'false');


        echo "</br>Name = " . $request->input('name');
        echo "</br>City = " . $request->input('city');

        echo "</br>Hobby:";
        for ($i = 0; $i < count($request->input('hobby')); $i++) {
            echo '</br>' . $request->input("hobby.$i");
        }
        echo "</br></br> Gambar";
        echo "</br>gambar ada? " . ($request->hasFile('gambar') ? 'true' : 'false');
        echo "</br> Ekstensi = " . $request->gambar->extension();
    }
}
```

### Langkah ketiga
Menambahkan route pada ``routes\web.php``
```php
Route::get('/formulir', [GuestController::class, 'input'])->name('input-form-guest');
Route::post('/proses-form/{id}', [GuestController::class, 'proses'])->name('proses-form-guest');
```

### Langkah keempat
Jalankan ``http://127.0.0.1:8000/formulir``
![Form](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Request/tampilan-form-guest.png?raw=true)

Output yang diperoleh
![Output](https://github.com/Fitrah1812/laravel-tutorial/blob/master/Laravel-request-validation-and-response/img/Request/output-form-guest.png?raw=true)

