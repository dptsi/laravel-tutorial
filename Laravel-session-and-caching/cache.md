# Cache

[Kembali](readme.md)

## Latar belakang topik

Beberapa tugas pengambilan atau pemrosesan data yang dilakukan oleh suatu aplikasi mungkin saja membutuhkan banyak CPU atau membutuhkan waktu beberapa detik untuk menyelesaikannya. Jika kasus tersebut terjadi, biasanya data akan ditempatkan di cache untuk sementara waktu sehingga dapat diambil dengan cepat ketika ada permintaan berikutnya untuk data yang sama. Data cache biasanya disimpan di penyimpanan data yang sangat cepat seperti Memcached atau Redis.

## Konsep-konsep

Laravel mendukung berbagai caching backend populer seperti Memcached, Redis, DynamoDB, dan database relasional. File konfigurasi cache pada Laravel terletak di config/cache.php. Dalam file ini, kita dapat menentukan cache driver mana yang ingin digunakan. Selain itu, file ini juga berisi opsi-opsi lain yang sudah didokumentasikan dalam file tersebut.

Secara default, Laravel dikonfigurasikan untuk menggunakan file sebagai cache drivernya. Akan tetapi, untuk aplikasi yang lebih besar, disarankan untuk menggunakan driver yang lebih kuat seperti Memcached atau Redis.

## Langkah-langkah tutorial

### Langkah pertama: Mendapatkan cache instance
Untuk mendapatkan penyimpanan cache instance, dapat menggunakan facade `Cache` yang menyediakan akses ke implementasi yang mendasari kontrak cache Laravel.


```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function fungsi()
    {
        ...
    }

}

```


### Langkah kedua: Menyimpan item dalam cache
Dapat menggunakan metode `put` pada facade `Cache` untuk menyimpan item dalam cache seperti pada sintaks di bawah ini.

```php
Cache::put('key', 'value', $second = 10);
```

Jika time storage tidak diteruskan pada metode `put`, item akan disimpan tanpa batas waktu.

```php
Cache::put('key', 'value');
```

Kita juga dapat meneruskan DateTime instance pada metode `put` untuk mewakili waktu expired dari suatu item cache.

```php
Cache::put('key', 'value', now()->addMinutes);
```

#### Simpan jika tidak ada
Metode `add` hanya akan menambahkan item ke dalam cache jika item tersebut memang tidak ada dalam penyimpanan cache. Akan mengembalikan nilai true jika item benar-benar ditambahkan dalam cache.

```php
Cache::add('key', 'value', $seconds);
```

#### Menyimpan item secara permanen
Metode `forever` dapat digunakan untuk menyimpan suatu item dalam cache secara permanen. Karena item-item ini tidak akan expired, item tersebut harus dihapus secara manual dari cache menggunakan metode `forget`.

```php
Cache::forever('key', 'value');
```


### Langkah ketiga: Mengambil item dari cache
Metode `get` facade `Cache` digunakan untuk mengambil item dari cache. Jika item tidak ada dalam cache, null akan dikembalikan atau jika sebelumnya kita meneruskan argumen kedua ke metode `get` yang berisi suatu default value, maka default value tersebut yang akan dikembalikan jika item tidak ada.

```php
$value = Cache::get('key');
$value = Cache::get('key', 'default');
```

Kita juga dapat meneruskan closure sebagai default value. Hasil dari closure akan dikembalikan jika item yang ingin didapatkan ternyata tidak ada dalam cache.

```php
$value = Cache::get('key', function() {
    return 'default';
});
```

#### Memeriksa keberadaan item
Metode `has` dapat digunakan untuk menentukan apakah ada item di dalam cache. Metode ini juga akan mengembalikan false jika terdapat item yang dicari tetapi nilainya null.

```php
if (Cache::has('key')) {
    ...
}
```

#### Incrementing/Decementing Values
Metode `increment` dan `decrement` dapat digunakan untuk menyesuaikan nilai item integer dalam cache. Kedua method ini menerima argumen kedua (opsional) yang menunjukkan jumlah yang digunakan untuk menambah atau mengurangi nilai item. 

```php
Cache::increment('key');
Cache::increment('key', $amount);
Cache::decrement('key');
Cache::decrement('key', $amount);
```

#### Mengambil atau menyimpan
Terkadang kita mungkin ingin mengambil item dari cache, tetapi juga menyimpan default value jika item yang diminta tidak ada. Misalnya, kita mungkin ingin mengambil semua data user dari cache, atau jika tidak ada, ambil data tersebut dari database lalu ditambahkan ke cache. Kita dapat melakukan ini dengan metode `Cache::remember`.

```php
$value = Cache::remember('users', $seconds, function () {
    return DB::table('users')->get();
});
```

Kita juga dapat menggunakan metode `rememberForever` untuk mengambil suatu item dari cache atau menyimpannya secara permanen jika item tersebut tidak ada.

```php
$value = Cache::rememberForever('users', function () {
    return DB::table('users')->get();
});
```

#### Mengambil dan menghapus
Jika kita ingin mengambil suatu item dari cache lalu menghapusnya, kita dapat menggunakan metode `pull`. Metode ini akan mengembalikan null jika item memang tidak ada dalam cache.

```php
$value = Cache::pull('key');
```

### Langkah keempat: Menghapus item dari cache
Menghapus item dari cache dapat dilakukan dengan menggunakan metode `forget`.

```php
Cache::forget('key);
```

Selain itu, juga dapat dilakukan dengan memberikan nilai 0 atau negatif pada expiration time metode `put` seperti di bawah ini.

```php
Cache::put('key', 'value', 0);
Cache::put('key', 'value', -5);
```

Atau kita juga dapat menghapus seluruh cache dengan menggunakan metode `flush`.

```php
Cache::flush();
```
