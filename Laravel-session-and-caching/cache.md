# Cache

[Kembali](readme.md)

## Latar belakang topik

Beberapa tugas pengambilan atau pemrosesan data yang dilakukan oleh suatu aplikasi mungkin saja membutuhkan banyak CPU atau membutuhkan waktu beberapa detik untuk menyelesaikannya. Jika kasus tersebut terjadi, biasanya data akan ditempatkan di cache untuk sementara waktu sehingga dapat diambil dengan cepat ketika ada permintaan berikutnya untuk data yang sama. Data cache biasanya disimpan di penyimpanan data yang sangat cepat seperti Memcached atau Redis.

## Konsep-konsep

Laravel mendukung berbagai caching backend populer seperti Memcached, Redis, DynamoDB, dan database relasional. File konfigurasi cache pada Laravel terletak di config/cache.php. Dalam file ini, kita dapat menentukan cache driver mana yang ingin digunakan. Selain itu, file ini juga berisi opsi-opsi lain yang sudah didokumentasikan dalam file tersebut.

Secara default, Laravel dikonfigurasikan untuk menggunakan file sebagai cache drivernya. Akan tetapi, untuk aplikasi yang lebih besar, disarankan untuk menggunakan driver yang lebih kuat seperti Memcached atau Redis.

## Driver Prerequisites

### Database

Ketika ingin menggunakan `database` sebagai cache driver, kita perlu mengatur sebuah tabel yang akan menyimpan item cache. Contoh deklarasi `Schema` untuk tabel tersebut bisa dilihat pada kode di bawah.

```php
Schema::create('cache', function ($table) {
    $table->string('key')->unique();
    $table->text('value');
    $table->integer('expiration');
});
```

### Cache Driver Lain

Apabila ingin menggunakan cache driver yang lain seperti [Memcached](https://laravel.com/docs/8.x/cache#memcached), [Redis](https://laravel.com/docs/8.x/cache#redis), dan [DynamoDB](https://laravel.com/docs/8.x/cache#dynamodb) dapat dilihat pada [Dokumentasi Cache Laravel](https://laravel.com/docs/8.x/cache).

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
Cache::put('key', 'value', now()->addMinutes(1));
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
Cache::forget('key');
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

## Atomic Locks
Untuk memanfaatkan fitur ini, kita harus menggunakan cache driver memcached, redis, dynamodb, database, file, atau array sebagai cache driver default. Selain itu, semua server harus berkomunikasi dengan server cache pusat yang sama. 

### Driver Prerequisites

Jika menggunakan database sebagai cache driver, terlebih dahulu kita perlu mengatur sebuah tabel yang akan berisi cache lock aplikasi kita. Untuk contoh deklarasi `Schema` dapat dilihat di bawah.

```php
Schema::create('cache_locks', function ($table) {
    $table->string('key')->primary();
    $table->string('owner');
    $table->integer('expiration');
});
```

### Mengelola locks

Atomic locks memungkinkan kita memanipulasi locks terdistribusi tanpa mengkhawatirkan race condition. Kita dapat membuat serta mengelola locks dengan metode `Cache::lock`.

```php
use Illuminate\Support\Facades\Cache;

$lock = Cache::lock('foo', 10);

if ($lock->get()) {
    // Lock acquired for 10 seconds...

    $lock->release();
}
```

Jika lock tidak tersedia ketika kita memintanya, kita dapat menginstruksikan Laravel untuk menunggu beberapa saat. Jika lock tidak dapat diperoleh dalam batas waktu tersebut, `Illuminate\Contracts\Cache\LockTimeoutException` akan di-throw seperti pada kode di bawah ini.

```php
use Illuminate\Contracts\Cache\LockTimeoutException;

$lock = Cache::lock('foo', 10);

try {
    $lock->block(5);

    // Lock acquired after waiting a maximum of 5 seconds...
} catch (LockTimeoutException $e) {
    // Unable to acquire lock...
} finally {
    optional($lock)->release();
}
```

Kode di atas dapat disederhanakan dengan meneruskan closure ke metode `block` seperti di bawah ini.

```php
Cache::lock('foo', 10)->block(5, function () {
    // Lock acquired after waiting a maximum of 5 seconds...
});
```

## Cache Helper
Selain menggunakan facade `Cache`, kita juga dapat menggunakan fungsi global `cache` untuk mengambil dan menyimpan data item via cache. Berikut sintaks untuk mengambil data item di cache.

```php
$value = cache('key');
```

Untuk menyimpan data item, kita sediakan array key-value dan expiration time ke fungsi yang akan menyimpan nilai tersebut ke dalam cache selama expiration time yang ditentukan.

```php
cache(['key' => 'value'], $seconds);
cache(['key' => 'value'], now()->addMinutes(10));
```

Ketika fungsi `cache` dipanggil tanpa argumen apapun, dia akan mengembalikan instance dari implementasi `Illuminate\Contracts\Cache\Factory` yang memungkinkan kita memangil metode caching lain seperti contoh di bawah ini.

```php
cache()->remember('users', $seconds, function () {
    return DB::table('users')->get();
});
```

## Cache Tags

Cache tags tidak didukung saat menggunakan file, dynamodb, atau database sebagai cache driver. Dengan cache tags, kita juga dapat menggunakan metode `put` untuk menyimpan tagged cache item, metode `get` untuk mengakses tagged cache item, dan metode `flush` untuk menghapus tagged cache item.

```php

// Storing Tagged Cache Items
Cache::tags(['people', 'artists'])->put('John', $john, $seconds);
Cache::tags(['people', 'authors'])->put('Anne', $anne, $seconds);

// Accessing Tagged Cache Items
$john = Cache::tags(['people', 'artists'])->get('John');
$anne = Cache::tags(['people', 'authors'])->get('Anne');

// Removing Tagged Cache Items
Cache::tags(['people', 'authors'])->flush();
Cache::tags('authors')->flush();

```

## Event

Ketika mengeksekusi kode pada setiap operasi cache, kita juga dapat mendengarkan `event` yang dipicu oleh cache. Biasanya kita perlu menempatkan event listeners dalam class `App\Providers\EventServiceProvider` ke dalam aplikasi kita.

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Illuminate\Cache\Events\CacheHit' => [
        'App\Listeners\LogCacheHit',
    ],

    'Illuminate\Cache\Events\CacheMissed' => [
        'App\Listeners\LogCacheMissed',
    ],

    'Illuminate\Cache\Events\KeyForgotten' => [
        'App\Listeners\LogKeyForgotten',
    ],

    'Illuminate\Cache\Events\KeyWritten' => [
        'App\Listeners\LogKeyWritten',
    ],
];

```
