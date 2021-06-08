# Session

[Kembali](readme.md)

## Latar belakang topik

Misal: jelaskan mengenai latar belakang, alasan penggunaan, dll.

## Konsep-konsep
Session menyediakan cara untuk menyimpan informasi pengguna di beberapa permintaan di server. File Konfigurasi session disimpan di `config/session/php`. Secara default, Laravel dikonfigurasi untuk menggunakan `file` session driver yang kompatibel dengan banyak aplikasi. Konfigurasi Session driver menentukan dimana session data akan disimpan untuk setiap permintaan. Jenis backend popular untuk session driver antara lain :
1. **File** - sessions yang disimpan di **storage/framework/sessions**.
2. **Cookie** - sessions yang disimpan di secure, encrypted cookies.
3. **Database** - sessions yang disimpan di relational database.
4. **Memcached/redis** - sessions yang disimpan di salah satu penyimpanan berbasis cache.
5. **Dynamodb** - sessions yang disimpan di **AWS DynamoDB**
6. **Array** - sessions yang disimpan di PHP array.

## Driver Prerequisites
### Database
Ketika menggunakan `database` session driver, maka perlu membuat sebuah tabel atau migrasi untuk menyimpan **session record**. Schema untuk tabel :
```php
Schema::create('sessions', function ($table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->text('payload');
    $table->integer('last_activity')->index();
});
```
Dapat juga menggunakan artisan command `session:table` untuk melakukan generate migrasi sessions.<br>
`php artisan session:table`<br>
`php artisan migrate`

### Redis
Untuk session driver menggunakan redis dapat dilihat pada dokumentasi [Redis](https://laravel.com/docs/8.x/redis#configuration).

## Langkah-langkah tutorial
Dalam mengoperasikan session dapat menggunakan 2 cara :
1. **Request Instance** - operasi sessions di controller.
2. **Global Session Helper** - operasi sessions di controller dan dapat digunakan dalam file html.
Namun, session sering kali menggunakan **Request Instance** untuk operasi sessions dengan bantuan permintaan HTTP.

### Langkah pertama : Membuat Controller
Untuk membuat controller dapat menggunakan Artisan command `php artisan make:controller NamaController`. 
Controller yang telah dibuat dapat ditemukan di **app/Http/Controllers**. 
```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $value = $request->session()->get('key');

        //
    }
}
```
### Langkah kedua : Menyimpan Item / Data di Session
Untuk menyimpan data di session, kita dapat menggunakan `put` method :
```php
// Via a request instance...
$request->session()->put('key', 'value');

// Via the global "session" helper...
session(['key' => 'value']);
```
Untuk mendapatkan hasil penyimpanan data berupa array di dalam session, dapat menggunkan code sebagai berikut :
```php
// Via a request instance...
$request->session()->put('key', ['value']);

// Via the global "session" helper...
session(['key' => ['value']]);
```
#### Memasukkan value ke array session
`push` method bisa digunakan untuk memasukkan atau mendorong sebuah value baru ke dalam session array. Misalnya, **nama** disini berisi sebuah array dari nama-nama user, kita dapat memasukkan sebuah value baru ke dalam array seperti ini :
```php
 $request->session()->push('nama', 'nada');
 ```
 
