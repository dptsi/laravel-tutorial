# laravel-validation

[Kembali](readme.md)

## Latar belakang topik

Di luar sana, ada banyak package validasi yang bersifat agnostik, dapat diinstal ke dalam berbagai macam framework bahkan php. Walaupun begitu, laravel sudah menyediakan fitur ini secara built-in, powerful dan tak perlu lagi repot untuk integrasi dengan framework. Validation ini berfungsi untuk melakukan validasi dari request yang dibuat dan untuk dilakukan crosscheck lebih lanjut.

## Konsep-konsep

Pengertian validation adalah untuk memvalidasi suatu request yang sudah dibuat sebelumnya. Secara default laravel telah menyediakan sebuah fungsi untuk membuat proses validasi salah satunya adalah validasi form yaitu kita bisa menggunakan fungsi validate(). 

## Langkah-langkah tutorial

### Langkah pertama
Disini kita akan membuath 2 buah route, yaitu route get. dengan url 'input' yang akan mengakses methode input pada controller FormController dan route yang 1 lagi kita akan membuat route post dengan url 'proses' yang akan memproses method atauu function proses pada controller FormController. Pembuatan route terletak pada :
NamaProject/routes/web.php

```php
Route::get('/input', [FormController::class, 'input']);
Route::post('/proses', [FormController::class, 'proses']);
```

### Langkah Kedua
Selanjutnya kita akan membuat sebuah controller yang sudah dideklarasikan diatas. Karena Laravel memiliki artisan yang merupakan Command-line utility pada Laravel seperti yang dimiliki framework web development lain. dengan cara sebagai berikut : 

```php
php artisan make:controller FormController
```

### Langkah Ketiga
Selanjutnya setelah controller dibuat maka kita akan membuat function yang kita inginkan. Karena tadi mengingkan function input dan proses maka kita akan membuat function tersebut dicontroller yang telah kita buat sebelumnya. Dan file ini berada di : NamaProject/App/Http/Controllers/FormController.php . Adapun controller tersebut berisi hal berikut : 

```php
<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class FormController extends Controller
{
    public function input()
    {
        return view('input');
    }
 
    public function proses(Request $request)
    {
        $this->validate($request,[
           'nama' => 'required|min:5|max:20',
           'pekerjaan' => 'required',
           'usia' => 'required|numeric'
        ]);
 
        return view('proses',['data' => $request]);
    }
}
```

### Langkah Keempat
Selanjutnya kita akan membuat sebuah tampilan 'input' atau dalam ini view('input') untuk bertujuan pada saat link menuju input akan ditampilkan form input nantinya. Lokasi dari file ini adalah NamaProject/Resources/Views/input.blade.php . Secara garis besar hal ini berguna untuk membuat tampilan input.Jika ada validasi error, maka data yang disi pada form tidak hilang, tapi tetap masih bisa ditampilkan, karena tentu akan sangat tidak efektif jika user harus menginput data berulang-ulang jika ada validasi yang salah pada penginputan. Disini terdapat fungsi old() berfungsi untuk menampilkan data yang sebelumnya di input. Source code adalah sebagai berikut :
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Malas Ngoding - Tutorial Laravel #18 : Membuat Form Validasi Pada Laravel</title>
 
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
 
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card mt-5">
                        <div class="card-body">
                            <h3 class="text-center">PBKK</h3>
                            <br/>
 
                            {{-- menampilkan error validasi --}}
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
 
                            <br/>
                             <!-- form validasi -->
                            <form action="/proses" method="post">
                                {{ csrf_field() }}
 
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input class="form-control" type="text" name="nama" value="{{ old('nama') }}">
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan">Pekerjaan</label>
                                    <input class="form-control" type="text" name="pekerjaan" value="{{ old('pekerjaan') }}">
                                </div>
                                <div class="form-group">
                                    <label for="usia">Usia</label>
                                    <input class="form-control" type="text" name="usia" value="{{ old('usia') }}">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="Proses">
                                </div>
                            </form>
 
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
</body>
</html>
```

### Langkah Kelima
Setelah kita membuat tampilan dari input maka kita perlu membuat juga tampilan pada proses dengan directory yang sama yaitu NamaProject/Resources/Views/proses.blade.php. Code adalah sebagai berikut

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation</title>

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h3>PBKK</h3>
                        <h3 class="my-4">Data Yang Di Input : </h3>

                        <table class="table table-bordered table-striped">
                            <tr>
                                <td style="width:150px">Nama</td>
                                <td>{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <td>Pekerjaan</td>
                                <td>{{ $data->pekerjaan }}</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>{{ $data->usia }}</td>
                            </tr>
                        </table>

                        <a href="/input" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html> 
```

###Langkah Keenam
Setelah itu kita juga bisa customisasi pesan yang akan digunakan untuk menampilkan error yang kita inginkan. Kita buka kembali NamaProject/App/Http/Controllers. Dan disini kita akan menambahkan array pesan baru untuk memvalidasi error. Contohnya adalah sebagai berikut :

```php
$messagesError = [
	'required' => ':attribute ini wajib diisi!!',
	'min' => ':attribute harus diisi minimal :min karakter!!!',
	'max' => ':attribute harus diisi maksimal :max karakter!!!',
];

$this->validate($request,[
    'nama' => 'required|min:5|max:20',
    'pekerjaan' => 'required',
    'usia' => 'required|numeric'
],$messagesError);

```


Berikut merupakan tutorial yang dapat saya sampaikan. Mohon maaf apabila terdapat kekurangan. Wassalamualaikum Warrahmatullahi Wabarakatuh.
