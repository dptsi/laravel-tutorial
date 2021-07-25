# Topik 2

[Kembali](readme.md)

## Latar belakang topik

Sebelum membahas mengenai Facade pada Laravel, akan dibahas mengenai **Facade pattern**, karena Facade pada Laravel ini sebenarnya dapat dikatakan mengimplementasikan Facade pattern. Facade pattern adalah salah satu *software design pattern* yang biasa digunakan pada *object oriented programming* (OOP), di mana facade itu sendiri adalah sebuah class yang membungkus suatu library yang kompleks agar menjadi sebuah interface yang lebih **simpel dan mudah untuk dibaca**. Pada facade pattern, terdapat sebuah class yang disebut facade class yang akan mengakses class lain. Class pengguna (client), tidak akan mengakses class yang di-'facade' secara langsung, melainkan mengaksesnya melalui facade class. Terlihat bahwa facade pattern ini bertujuan untuk menutupi/menyembunyikan kerumitan yang ada, seperti terlihat pada gambar di bawah.

![image](https://user-images.githubusercontent.com/48936125/119772074-d464d480-bee8-11eb-86f2-d52d386dcafb.png)

Setelah mengetahui mengenai Facade pattern, dapat mulai membahas mengenai Facade pada Laravel sendiri. Facade pada Laravel menyediakan sebuah static interface ke kelas-kelas yang tersedia pada service container dari aplikasi yang dibuat. Facade pada Laravel dapat diasumsikan sebagai cara singkat dan ringkas untuk mengakses sebuah object di container, sekaligus mempermudah keperluan testing (*testability* dan *flexibility*).

Akan tetapi, ketika menggunakan facade harus berhati-hati agar tidak terjadi ***scope creep***. Karena facade sangat mudah untuk digunakan dan diimplementasikan, sangat memungkinkan class yang dibuat semakin besar berkembang dan menggunakan banyak facade hanya dalam 1 class saja. Jadi, ketika menggunakan facade, sebaiknya tetap memperhatikan ukuran dari class yang dibuat, agar scope tanggung jawab dari class itu tidak terlalu besar. Jika memang ukuran class sudah terlalu besar, sebaiknya dipecah menjadi beberapa class yang lebih kecil.

## Konsep-konsep

Facade pada Laravel semuanya terdefinisikan dalam namespace ```Illuminate\Support\Facades```. Jadi, penggunaannya cukup sederhana sebagai berikut:
```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/cache', function () {
    return Cache::get('key');
});
```
Di mana terlihat ketika mengakses properti ataupun method, Facade menggunakan double colons (::). Ini disebabkan karena method yang dipanggil melalui facade berupa static methods. Perbedaan antara static methods dan non-static methods pada PHP sebenarnya terdapat pada diperlukan atau tidaknya instansiasi sebuah class menjadi object untuk mereference class tersebut. Pada static methods, tidak diperlukan instansiasi sebuah class untuk mereferensi class tersebut, sedangkan pada non-static methods, harus melakukan instansiasi class agar dapat di-refer.

Berikut contoh static methods pada PHP:
```php
<?php
class backend {
	private const language = "php";
	public static function language() {
    	echo self::language;
  	}
}

backend::language();  //php
```

Sedangkan contoh non-static methods pada PHP:
```php
<?php
class backend{
	public function language($name){
		echo $name;
	}
}

$test = new backend; //creating an instance of the class
$test->language('php'); //php
```

Setelah memahami perbedaan static dan non-static methods pada PHP, kembali membahas mengenai Facade. 

Facade pada Laravel yang secara default ada, tersedia pada direktori ```vendor > laravel > framework > src > Illuminate > Support > Facades```.

![image](https://user-images.githubusercontent.com/48936125/119774933-1bed5f80-beed-11eb-868c-6b5325ab0a32.png)

Misalkan mencoba menggunakan code pada file ```DB.php``` untuk melihat isi dari sebuah facade, di mana sebenarnya menggunakan file apapun akan terlihat sama. Berikut source code dari file ```DB.php```:
```php
<?php

namespace Illuminate\Support\Facades;

class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
```

Terlihat bahwa ```DB``` merupakan sebuah class yang meng-extend base class ```Facade``` yang didapatkan dari namespace pada baris di atasnya. Pada class ```DB```, kita hanya memiliki satu method dengan access modifier protected yaitu ```getFacadeAccessor()```, di mana method tersebut hanya mengembalikan sebuah string ```db```. ```db``` yang merupakan nama dari facade ini akan dikembalikan agar kita dapat mengakses facade ini di mana pun dalam aplikasi Laravel yang kita buat, tanpa perlu menginisiasinya. Misalkan di controller kita membutuhkan mengambil data dari database, dapat langsung menggunakannya seperti berikut ```$data = DB::table('provinsi')->get();```. Terlihat bahwa facade membuat code lebih mudah untuk dibaca, ringkas, dan teratur.

Selain itu, Facade pada Laravel juga menyediakan testing methods. Misalkan kita ingin menguji apakah method ```Cache::get``` sudah dipanggil dengan argumen yang diharapkan:
```php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/cache', function () {
    return Cache::get('key');
});
```
Kita dapat menggunakan test script berikut untuk mengujinya:
```php
use Illuminate\Support\Facades\Cache;

/**
 * A basic functional test example.
 *
 * @return void
 */
public function testBasicExample()
{
    Cache::shouldReceive('get')
         ->with('key')
         ->andReturn('value');

    $response = $this->get('/cache');

    $response->assertSee('value');
}
```

## Langkah-langkah tutorial

### Langkah pertama

Buat direktori baru bernama ```Classes``` dalam direktori ```app``` laravel, kemudian dalam direktori baru ini buat suatu direktori baru bernama ```Facade```.
Dalam direktori ```Classes``` ini buatlah juga sebuah file bernama ```Student.php```.

Lalu isi file ```Student.php``` dengan source code berikut:
```php
<?php
namespace App\Classes;

class Student {
	public function studentName() {
		return 'Sheinna';
	}
}
```

### Langkah kedua

Berikutnya buatlah sebuah file bernama ```Student.php``` pula di dalam direktori ```Facade``` yang sudah dibuat tadi. Lalu isi file ```Student.php``` ini dengan source code berikut:
```php
<?php
namespace App\Classes\Facade;

use Illuminate\Support\Facades\Facade;

class Student extends Facade {
	protected static function getFacadeAccessor() {
		return 'student';
	}
}
```

### Langkah ketiga

Berarti sementara ini dalam direktori ```app``` kita sudah mempunyai direktori baru bernama ```Classes``` dengan struktur sebagai berikut:
```
Classes/
--Facade/
----Student.php
--Student.php
```

Setelah berhasil membuat 2 file tersebut, kita harus membuat provider, dengan cara menjalankan perintah berikut pada terminal:
```
php artisan make:provider StudentProvider
```

### Langkah keempat

File ```StudentProvider.php``` yang baru saja kita buat dapat ditemukan di direktori ```app/Providers```. Tambahkan source code berikut pada file ```StudentProvider.php``` untuk melakukan *binding*.
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StudentProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('student', function() {
            return new \App\Classes\Student;
        });
    }
}
```

### Langkah kelima

Setelah membuat provider, maka yang perlu dilakukan hanyalah mendaftarkan provider yang dibuat ke dalam ```config/app.php```.

Dengan cara menambahkan 2 baris berikut pada **provider class** dan **aliases class**:
```php
<?php

    'providers' => [
   
        //...
        App\Providers\StudentProvider::class,

    ],
    
    ...

    'aliases' => [

        //...
        'Student' => App\Classes\Facade\Student::class,

    ],

```

### Langkah keenam

Langkah terakhir sebelum kita dapat menggunakan facade yang baru saja kita buat adalah dengan melakukan generate ulang file ```autoload.php``` agar kelas-kelas yang baru dibuat tadi dapat langsung dikenali oleh aplikasi (tanpa harus include/require secara manual), dengan menjalankan perintah berikut pada terminal:
```
composer dump-autoload
```

### Langkah ketujuh

Sekarang facade yang baru saja dibuat sudah dapat digunakan di mana saja dalam aplikasi Laravel kita secara mudah dan sederhana. Sebagai contoh mengujinya kita dapat mencoba menjalankan **tinker** Laravel dengan cara menjalankan perintah berikut pada terminal:
```
php artisan tinker
```

### Langkah kedelapan

Kemudian kita dapat mencoba memanggil fungsi ```stundentName()``` yang sudah kita buat pada class ```Student```, langsung pada terminal, dengan perintah:
```
Student::studentName()
```

### Langkah kesembilan

Selain menggunakan **tinker**, kita dapat mencoba langsung dengan melakukan edit file ```welcome.blade.php``` pada aplikasi Laravel kita. File ```welcome.blade.php``` terdapat pada direktori ```resources/views/```. Misalkan kita coba mengubah title 'Laravel' menjadi ```Student::studentName()```. Akan tetapi karena file ```welcome.blade.php``` ditulis dengan HTML, maka harus menggunakan ```{{ }}``` untuk menjalankan perintah PHP, sebagai berikut:
```html
	<title>{{Student::studentName()}}</title>
```

## Referensi
* https://laravel.com/docs/8.x/facades
* https://www.sitepoint.com/how-laravel-facades-work-and-how-to-use-them-elsewhere/
* https://www.freecodecamp.org/news/how-to-use-facades-in-laravel/
