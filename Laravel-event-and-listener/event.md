# Topik 1

[Kembali](readme.md)

## Latar belakang topik

Dalam melakukan pemrograman, sebaiknya sebuah class hanya melakukan sebuah tugas spesifik. Namun terkadang kita sering memasukkan banyak sekali fungsi ke dalam class sehingga apabila nantinya ada bagian class yang ingin diubah, banyak bagian lain yang ikut berubah. Menggunakan event dan listener mempermudah kita mengatur agar class kita menjalankan satu tugas.

## Konsep-konsep

Event adalah sebuah peristiwa/aktivitas yang terjadi pada aplikasi kita. Event merupakan sebuah cara untuk mengetahui apabila sebuah peristiwa terjadi (sebagai trigger). Listener adalah sebuah class yang akan menunggu perintah untuk dijalankan dari event yang menugaskan listener tersebut. Sebuah event dapat memiliki lebih dari 1 listener.

## Membuat event dan listener

### Langkah pertama

Untuk menambahkan event baru beserta listener yang dimilikinya, dapat menuliskannya pada `App\Providers\EventServiceProvider`. Properti `listen` merupakan array yang berisi berbagai event (sebagai key) dan listen yang dimilikinya (sebagai value).

Misal ingin membuat event `LoginHistory` dengan listener `StoreUserLoginHistory`

```php
<?php

use App\Events\LoginHistory;
use App\Listeners\StoreUserLoginHistory;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
       
        LoginHistory::class => [
            StoreUserLoginHistory::class,
        ]
    ];
}
```

### Langkah kedua

Setelah itu generate event dan listener yang baru dibuat menggunakan perintah `php artisan event:generate` pada command line. Event akan tersedia di `app\Events` dan listener akan tersedia di `app\Listeners`

## Langkah alternatif

Terdapat cara lain untuk membuat event dan listener tanpa perlu menuliskannya di `App\Providers\EventServiceProvider` yaitu menggunakan perintah `php artisan make`

```
php artisan make:event LoginHistory
php artisan make:listener StoreUserLoginHistory --event=LoginHistory
```

## Defining Event

class event merupakan sebuah container data yang menyimpan informasi yang berhubungan dengan event tsb. Contohnya kita akan mendefinisikan class event LoginHistory seperti berikut

```
class LoginHistory
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;


    public function __construct($user)
    {
        $this->user = $user;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
```

Dapat terlihat pada contoh, class event tidak menyimpan logika. Class event ini menjadi container dari instance User yang melakukan login. 


## Defining Listener

Event listener menerima instance event dalam method handle. Ketika kita menggunakan event:generate dan make:listener maka Artisan akan secara otomatis melakukan import class dari event dan type-hint event ke dalam method handle. Di dalam method handle ini kita melakukan aksi yang dibutuhkan ketika event terjadi
Contohnya kita akan mendefinisikan aksi apa yang akan dilakukan ketiak event LoginHistory terjadi:

```
 public function handle(LoginHistory $event)
    {
        $current_timestamp = Carbon::now()->toDateTimeString();

        $userinfo = $event->user;

        $saveHistory = DB::table('login_history')->insert(
            [
                'name' => $userinfo->name,
                'email' => $userinfo->email,
                'created_at' => $current_timestamp,
                'updated_at' => $current_timestamp
            ]
        );
        return $saveHistory;
    }
```

Dapat terlihat pada contoh, kita memasukan aksi yang ingin terjadi ketika event dipanggil di dalam method handle. Pada contoh, kita akan menyimpan informasi user yang melakukan login ke dalam database login_history. 


## Dispatching Event
ketika ingin melakukan dispatch terhadapa sebuah event, kita dapat memanggil method dispatch yang dimiliki oleh class event. Method ini ada ketika kita menambahkan  trait Illuminate\Foundation\Events\Dispatchable pada class event kita. Pada contoh kita ingin memanggil event ketika ada seorang user yang melakukan login. Maka dari itu kita akan memanggil event pada app/Http/Requests/Auth/LoginRequest.php di dalam method authenticate(). Disini kita akan menambah 

```
$user = Auth::user();
LoginHistory::dispatch($user);
```

cara lain 
```
$user = Auth::user();
event(new LoginHistory($user));
```


Dengan begitu, ketika ada user yang melakukan login, event LoginHistory akan terpanggil dan listener akan menyimpan data yang ada ke dalam database login_history






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
