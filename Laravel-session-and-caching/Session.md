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
Untuk membuat controller dapat menggunakan Artisan command `php artisan make:controller NamaController`. Disini saya membuat controller dengan nama `SessionController`. Controller yang telah dibuat dapat ditemukan di **app/Http/Controllers**. 
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
}
```
### Langkah kedua : Menyimpan Data
Pada langkah kedua ini adalah langkah pertama untuk berinteraksi dengan session dan method-method yang digunakan dalam session. Tentunya saat ini di dalam session masih belum ada data yang disimpan. Maka untuk menyimpan item atau data di session, kita dapat menggunakan metode `put` :
```php
public function store (Request $request){
    // REQUEST INSTANCE
    $request->session()->put('nama', 'vania');
    $request->session()->put('alamat', 'pasuruan');
    
    // GLOBAL HELPER
    $nama = Session (['nama' => 'vania']);
    $alamat = Session (['alamat' => 'pasuruan']);
    
    echo "Data telah disimpan";
}
```
Silahkah pilih salah satu dari jenis pengoperasian diatas yaitu menggunakan request instance atau global helper. Hasil yang didapatkan setelah mengoperasikan code session diatas yaitu sebagai berikut :
JPG - STORE

Untuk mendapatkan hasil penyimpanan item berupa array di dalam session, dapat menggunakan fungsi sebagai berikut :
```php
public function store (Request $request){
    // REQUEST INSTANCE
    $request->session()->put('nama', ['vania']);
    $request->session()->put('alamat', ['pasuruan']);
    
    // GLOBAL HELPER
    $nama = Session (['nama' => ['vania']]);
    $alamat = Session (['alamat' => ['pasuruan']]);
    
    echo "Data telah disimpan";
}
```
Hasil yang di dapatkan dari code session diatas yaitu sebagai berikut :
JPG - store

#### Memasukkan value ke array session
Metode `push` bisa digunakan untuk memasukkan atau mendorong sebuah value baru ke dalam session array. Misalnya, **nama** disini berisi sebuah array dari nama-nama user, kita dapat memasukkan sebuah value baru ke dalam array seperti ini :
```php
public function push(Request $request){
    $request->session()->push('nama', 'nada');
    $request->session()->push('alamat', 'banjarmasin');

    echo "Data telah dipush";
}
```
Hasil yang di dapatkan setelah mengoperasikan code session diatas yaitu sebagai berikut :
JPG - push
 
### Langkah ketiga : Mengambil Data
Untuk mengambil item dari session, maka dapat menggunakan metode `get` seperti di bawah ini :
```php
public function show(Request $request){
    // REQUEST INSTANCE
    $name = $request->session()->get('nama');
    $address = $request->session()->get('alamat');

    // GLOBAL HELPER
    $name = session ('nama');
    $address = session ('alamat');

    print_r($name);
    print_r($address);
}
```
Metode ini akan menampilkan hasil data yang disimpan di dalam session. Hasil data session yang disimpan saat melakukan operasi store tanpa array di langkah pertama adalah sebagai berikut :
JPG - SHOW

Hasil data session yang disimpan saat melakukan operasi store menggunakan array di langkah pertama adalah sebagai berikut :
JPG - show array

Hasil dari push value di langkah pertama adalah sebagai berikut :
JPG - show push

#### Mengambil semua session data 
Jika ingin menampilkan semua data di dalam session, kita dapat menggunakan metode `all`:
```php
public function all(Request $request){
    $data = $request->session()->all();
    print_r($data);
}
```
Maka akan mendapatkan hasil tampilan seperti berikut :
JPG - showall

### Langkat keempat : Menghapus Data
Metode `forget` akan menghapus sebuah data dari session :
```php
public function delete(Request $request){
    // menghapus key nama
    $request->session()->forget('nama');
    echo "Data telah dihapus";
}

// Forget multiple keys...
$request->session()->forget(['key1', 'key2']);
```
#### Mengambil dan menghapus item
Terdapat 2 jenis metode untuk menghapus data atau item dari session. Cara pertama adalah dengan menggunakan metode `flush`, yang mana akan menghapus semua data di dalam session :
```php
 public function flush(Request $request){
    // menghapus semua data
    $request->session()->flush();
    echo "Data telah dihapus semua";
}
```
JPG - flush.
Maka semua data akan terhapus semua :
JPG - flush show

Cara kedua adalah dengan metode `forget` yang mana akan menghapus data dalam item tertentu. Disini kita bisa melakukan penyimpanan data kembali ke dalam session sesuai dengan tahapan yang telah dijelaskan sebelumnya, yaitu `put` dan `push` dalam bentuk array. Lalu, kita coba untuk menghapus item `nama` dari session dengan menggunakan metode `forget` :
```php
public function delete(Request $request){
    // menghapus key nama
    $request->session()->forget('nama');
    echo "Data telah dihapus";
}
```
JPG - delete
dan jika kita ingin menampilkan data yang sekarang ada di dalam session dengan metode `get` :
JPG - delete show

#### Memeriksa keberadaan item di dalam session
Untuk memeriksa keberadaan item, kita bisa menggunakan metode `has`. Metode ini mengembalikan `true` jika item ada dan tidak bernilai `null`. Sedangkan, metode kebalikan dari `has` adalah metode `missing`. Metode ini mengembalikan nilai `true` jika item tidak ada di dalam session atau bernilai `null`. Disini saya mencoba memeriksa item `nama` di dalam session :
```php
public function hasMis(Request $request){
    if($request->session()->has('nama')){
        print_r($request->session()->get('nama'));
    }

    elseif ($request->session()->missing('nama')){
        echo "Data/Item tidak ada";
    }
}
```
Karena item `nama` telah di-pull sebelumnya, maka hasil yang didapatkan :
JPG - has

Dan jika item diganti menjadi item `alamat`, maka hasil yang akan didapatkan : 
JPG - has2

#### Mengambil dan menghapus data 
Metode `pull` akan mengambil dan menghapus item yang dituju dari session :
```php
public function pull(Request $request){
    $value = $request->session()->pull('alamat');
    print_r($value);
}
```
Disini saya akan melakukan `pull` pada item `nama` :
JPG - PULL

# Langkah Lainnya 
## Flash Data
Metode `flash` adalah metode yang digunakan untuk membuat session yang bersifat sekali pakai. Session yang kita buat dengan metode ini hanya akan berlaku untuk satu buah proses setelah suatu session dibuat. Sehingga `flash` ini sangat efektif untuk digunakan pada proses yang singkat, seperti menampilkan pesan notifikasi. Setelah itu, session yang dibuat dengan `flash` langsung hilang dihapus secara otomatis.<br>
Jenis notifikasi yang biasanya digunakan adalah **error, warning,** dan **success**.<br>

### Langkah pertama : Membuat file html
Pertama, membuat file html untuk menampilkan fitur notifikasi. Disini saya membuat 3 notifikasi sesuai dengan penjelasan diatas :
```php
 <a href="{{ url('flash') }}" class="btn btn-danger btn-sm">
    Klik Disini
</a>

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>	
    <strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>	
    <strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>	
    <strong>{{ $message }}</strong>
</div>
@endif
```
### Langkah kedua : memanggil file html
Langkah kedua adalah membuat fungsi untuk memanggil file html di file controller :
```php
public function index(Request $request)
{
    return view('index',
        ['title'=>'Flash Message']
    );
}
```
### Langkah ketiga : menampilkan flash
Tahap ketiga adalah membuat fungsi `flash` seperti berikut ini :
```php
public function flash(){
    return redirect('/index')->with(['success' => 'Ini pesan Berhasil~']);
    return redirect('/index')->with(['error' => 'Ini pesan error!']);
    return redirect('/index')->with(['warning' => 'Ini Pesan warning !!!']);
}
```
Potongan code pertama akan menampilkan: `Ini Pesan Berhasil~` dengan `mode alert: success` :
JPG - flash success

Potongan code kedua akan menampilkan: `Ini Pesan error!` dengan `mode alert: error` :
JPG - flash error

Potongan code ketiga akan menampilkan: `Ini Pesan warning!!!` dengan `mode alert: warning` :
JPG - flash warning

## Increment dan Decrement Values
Jika session data berisi integer yang ingin ditambah atau dikurangi, kita dapat menggunakan metode `increment` dan `decrement` :
```php
// INCREMENT
public function increment(Request $request){
    print_r($request->session()->increment('count'));
    
    // Menambah angka dengan kelipatan 2
    print_r($request->session()->increment('count', $incrementBy = 2));
}

// DECREMENT
public function decrement(Request $request){
    print_r($request->session()->decrement('count'));
    
    //mengurangi angka dengan kelipatan 3
    print_r($request->session()->decrement('count', $decrementBy = 3));
}
```
Saat melakukan running pada fungsi `increment` di code baris pertama, hasil pertama yang keluar adalah angka 1. Jika kita melakukan refresh, maka akan menjadi angka 2, dan seterusnya. 
JPG - increment
JPG - increment2

Sedangkan pada code baris kedua di fungsi `increment`, angka yang dihasilkan akan bertambah 2 setiap kali di refresh :
jpg - increment6

Disini saya menambah angka hingga menghasilkan angka 10. Kemudian, saya mencoba melakukan running fungsi `decrement` pada code baris pertama sehingga angka akan berkurang 1 menjadi angka 9 :
jpg - decrement

Sedangkan pada code baris kedua di fungsi `decrement`, angka yang muncul adalah hasil angka sebelumnya dikurangi 3 setelah melakukan refresh :
jpg - decrement2

## Regenerating Session ID
Regenerasi session ID sering dilakukan untuk mencegah pengguna jahat dalam mengeksploitasi serangan fiksasi sesi pada aplikasi Anda.
Laravel secara otomatis membuat ulang ID sesi selama otentikasi jika Anda menggunakan salah satu starter kit aplikasi Laravel atau Laravel Fortify; namun, jika Anda perlu membuat ulang ID sesi secara manual, Anda dapat menggunakan metode regenerasi:
# Session Blocking

# Custom Session Drivers
## 
