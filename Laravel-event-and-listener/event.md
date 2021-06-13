# Laravel event and listener

[Kembali](readme.md)

## Latar belakang topik

Dalam melakukan pemrograman, sebaiknya sebuah class hanya melakukan sebuah tugas spesifik. Namun terkadang kita sering memasukkan banyak sekali fungsi ke dalam class sehingga apabila nantinya ada bagian class yang ingin diubah, banyak bagian lain yang ikut berubah. Menggunakan event dan listener mempermudah kita mengatur agar class kita menjalankan satu tugas.

## Konsep-konsep

Event adalah sebuah peristiwa/aktivitas yang terjadi pada aplikasi kita. Event merupakan sebuah cara untuk mengetahui apabila sebuah peristiwa terjadi (sebagai trigger). Listener adalah sebuah class yang akan menunggu perintah untuk dijalankan dari event yang menugaskan listener tersebut. Sebuah event dapat memiliki lebih dari 1 listener.

## Generate event dan listener

### Langkah pertama

Untuk menambahkan event baru beserta listener yang dimilikinya, dapat menuliskannya pada `App\Providers\EventServiceProvider`. Properti `listen` merupakan array yang berisi berbagai event (sebagai key) dan listen yang dimilikinya (sebagai value).

Misal ingin membuat event `LoginHistory` dengan listener `StoreUserLoginHistory`

```php
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

### Langkah alternatif

Terdapat cara lain untuk membuat event dan listener tanpa perlu menuliskannya di `App\Providers\EventServiceProvider` yaitu menggunakan perintah `php artisan make`

```
php artisan make:event LoginHistory
php artisan make:listener StoreUserLoginHistory --event=LoginHistory
```

## Register event dan listener secara manual

Kita dapat melakukan register class maupun listener secara manual dalam `boot` pada `App\Providers\EventServiceProvider`

```php
use App\Events\LoginHistory;
use App\Listeners\StoreUserLoginHistory;
use Illuminate\Support\Facades\Event;

public function boot()
    {
        Event::listen(
            LoginHistory::class,
            [StoreUserLoginHistory::class, 'handle']
        );
        
        Event::listen(function (LoginHistory $event) {
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
        });
    }
```

## Mendefinisikan Event

Class event merupakan sebuah container data yang menyimpan informasi yang berhubungan dengan event tersebut. Contohnya kita akan mendefinisikan class event LoginHistory seperti berikut

```php
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

## Mendefinisikan Listener

Event listener menerima instance event dalam method handle. Ketika kita menggunakan `event:generate` dan `make:listener` maka Artisan akan secara otomatis melakukan import class dari event dan type-hint event ke dalam method handle. Di dalam method handle ini kita melakukan aksi yang dibutuhkan ketika event terjadi
Contohnya kita akan mendefinisikan aksi apa yang akan dilakukan ketika event LoginHistory terjadi:

```php
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

Ketika ingin melakukan dispatch terhadapa sebuah event, kita dapat memanggil method dispatch yang dimiliki oleh class event. Method ini ada ketika kita menambahkan  trait `Illuminate\Foundation\Events\Dispatchable` pada class event kita. Pada contoh kita ingin memanggil event ketika ada seorang user yang melakukan login. Maka dari itu kita akan memanggil event pada `app/Http/Requests/Auth/LoginRequest.php` di dalam method `authenticate()`. Disini kita akan menambah 

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

## Listener dengan Queue

### Membuat Queued Listener

Listener dapat diqueue, listener yang diqueue berguna ketika kita ingin menjalankan task-task yang memerlukan waktu seperti mengirimkan email ataupun melakukan request http. Sebelum menggunakan queued listener kita harus mengkonfigurasi queue dan menjalanakan queue worker.

Untuk membuat listener menjadi queue kita hanya perlu menambahkan ShouldQueue interface pada class listener. Listener yang di generate dari artisan sudah memiliki interface ShouldQueue terimport pada namespace. 

```php
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreUserLoginHistory implements ShouldQueue
{
    //
}
```
Dengan begitu maka ketika event yang dihandle oleh listener ini terpanggil maka listener akan secara otomatis di queue menggunakan Laravel's queue system.

### Menyesuaikan Queue

Apabila ingin mengubah koneksi queue, nama queue, atau waktu delay queue dari sebuah listener, kita dapat melakukannya dengan mendefinisikan properti `$connection`, `$queue`, atau `$delay` pada class listener.

```php
use App\Events\LoginHistory;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreUserLoginHistory implements ShouldQueue
{
    public $connection = 'sqs';
    public $queue = 'listeners';
    public $delay = 10;
}
```

Atau apabila ingin mendefinisikan nama queue listener saat runtime, dapat mendefinisikan fungsi `viaQueue`

```php
public function viaQueue()
{
    return 'listeners';
}
```

### Conditional Queue Listener

Terkadang ada kondisi dimanan kita ingin me-queue listener berdasarkan suatu kondisi /data. Untuk mencapai hal tersebut kita dapat menambahkan method `shouldQueue` pada Listener dan dapat menentukan apakah listener akan di queue atau tidak di dalamnya. Ketika method shouldQueue mereturn false maka listener tidak akan diekesekusi.

```php
public function shouldQueue(LoginHistory $event)
{
    return true;
}  
```

## Event Subscriber

Event subscriber adalah sebuah class yang berisikan multiple events. Dengan adanya subscriber, kita dapat mendefinisikan beberapa handle (listener handle) dalam sebuah class. 

### Membuat Event Subscriber

Karena event subscriber berisikan handle listener, maka kita dapat membuat event subscriber pada `App\Listeners`. Di dalam subscriber kita akan mendefenisikan method `subscribe`
. Method ini akan di pass kedalam event dispatcher instance. Contohnya:

```php
class UserEventSubscriber
{
    
    public function storeUserLogin($event) {
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

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            LoginHistory::class,
            [UserEventSubscriber::class, 'storeUserLogin']
        );
    }
}
```
Dari contoh dapat terlihat kita membuat method storeUserLogin. Method ini merupakan handle yang akan dijalankan ketika event LoginHistory terpanggil. Di bagian subscribe kita menghubungkan event LoginHistory dengan method storeUserLogin tadi.

### Register Event Subscriber

Untuk melakukan register subscriber, dapat dilakukan dengan mendefinisikan properti `$subscribe` pada `EventServiceProvider`.

```php
use App\Listeners\UserEventSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        UserEventSubscriber::class,
    ];
```

