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


## Referensi
* https://laravel.com/docs/8.x/facades
* https://www.sitepoint.com/how-laravel-facades-work-and-how-to-use-them-elsewhere/
* https://www.freecodecamp.org/news/how-to-use-facades-in-laravel/
