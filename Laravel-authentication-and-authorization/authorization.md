# Authorization

[Kembali](readme.md)

## Daftar Isi
- [Latar belakang topik](#latar-belakang)  
- [Konsep-konsep](#konsep)
- [Langkah-langkah tutorial](#tutorial)
 

<a name="latar-belakang"/>

## Latar belakang topik

Biasanya dalam suatu sistem atau aplikasi, kita akan menemukan banyak user yang akan berinteraksi dengan sistem. Jika semua user yang akan mengakses sistem memiliki izin yang sama, maka data yang seharusnya hanya boleh diakses oleh orang tertentu akan bebas diakses oleh user lain. Sebagai contoh, laporan keuangan disuatu sistem perusahaan yang bersifat rahasia akan dapat diakses oleh semua user. Oleh karena itu, diperlukan otorisasi dalam sebuah sistem.

<a name="konsep"/>

## Konsep-konsep

Otorisasi mengizinkan user yang sudah terotentikasi untuk mengakses resource tertentu dalam sebuah sistem. Sebagai contoh, ketika sebuah user mencoba untuk mengakses bagian admin, sistem akan melakukan verifikasi (mengotorisasi) apakah user memiliki izin untuk mengakses bagian tersebut. Jika user memiliki izin, maka user akan diberi otorisasi dan diberikan akses ke bagian admin. Jika tidak, akses user tersebut akan ditolak.

Laravel menyediakan 2 cara untuk mengotorisasi aksi, yaitu dengan menggunakan gates dan policies. Gates adalah otorisasi menggunakan pendekatan closure. Sedangkan Policies adalah class yang mengatur logika otorisasi pada suatu model atau resource. 

Beberapa method yang dapat digunakan untuk melakukan otorisasi adalah :

**1. allows**

Mengecek apakah user dapat mengakses gates tertentu, allows akan mereturn boolean.

```php
if (Gate::allows('update-post', $post)) {
        // user can update post   
}
```

**2. denies**

negasi dari `allows`. Seperti `allows`, denies juga akan mereturn boolean.

```php
if (Gate::denies('update-post', $post)) {
        abort(403);        
}
```

**3. check** 

Mengecek apakah user dapat melewati array gates. Check akan mereturn boolean.

```php
if (Gate::check(['update-post', 'delete-post'], $post)) {
    // The user can update or delete the post...
}
```

**4. any**

Mengecek apakah user dapat melewati salah satu dari array gates. Any akan mereturn boolean.

```php
if (Gate::any(['update-post', 'delete-post'], $post)) {
    // The user can update or delete the post...
}
```

**5. none**

negasi dari **any**. Seperti any, none akan mereturn boolean.

```php
if (Gate::none(['update-post', 'delete-post'], $post)) {
    // The user can't update or delete the post...
}
```

**6. authorize**

Jika kita ingin melakukan otorisasi dan secara otomatis melakukan throw exception `Illuminate\Auth\Access\AuthorizationException` ketika user tidak diizinkan, maka kita dapat menggunakan method `authorize`. Instance dari `AuthorizationException` secara otomatis akan dikonversi ke 403 HTTP response oleh exception handler Laravel. 

```php
Gate::authorize('update-post', $post);
```

**7. forUser**
Jika kita ingin menemukan apakah ada user selain dari user yang sedang login / terautentikasi bisa melakukan aksi, maka kita dapat menggunakan method **`forUser`** pada facade `Gate` :

```php
if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```

**8. before**

Kita dapat menggunakan method `before` untuk mendefinisikan closure yang akan dijalankan sebelum method otorisasi lainnya. `before` dapat digunakan untuk memberikan seluruh akses pada user tertentu tanpa melakukan pengecekan otorisasi lainnya:

```php
Gate::before(function ($user, $ability) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

**9. after**

Method `after` mirip dengan `before`. Hanya saja, method ini akan dijalankan setelah pengecekan otorisasi lainnya:

```php
Gate::after(function ($user, $ability, $result, $arguments) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

Jika closure `before` atau `after` mereturn non-null maka hasil tersebut akan dianggap hasil dari pengecekan otorisasi.

Pada contoh diatas, kita hanya perlu mempassing `$post` karena Laravel sudah secara otomatis mempassing user yang sedang login saat ini.

<a name="tutorial"/>

## Langkah-langkah tutorial

### Langkah pertama - menambah kolom pada database user

Buka file user migration yang terletak di directory `database\migrations` dan tambahkan satu kolom dengan nama **isAdmin** dengan tipe data boolean:

```php
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('isAdmin')->default(0);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
```

Lalu, migrasikan database tersebut:

`php artisan migrate`

### Langkah kedua - membuat view yang diperlukan

Buatlah view `private.blade.php`. View ini nantinya hanya dapat diakses oleh admin yang sudah login:

```blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            Private page, only admin can access this page.
            <br> <a href="{{ url('/home') }}">Back</a>
        </div>  
    </div>
</div>
@endsection
```

Kemudian, buatlah view `response.blade.php`. Seperti view `private`, view ini hanya dapat diakses oleh admin yang sudah login:

```blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $msg }}
            <br> <a href="{{ url('/home') }}">Back</a>
        </div>
    </div>
</div>
@endsection
```

Selanjutnya, buatlah view `post.blade.php`. View ini akan dapat dilihat oleh semua user, termasuk guest user. View ini akan menampilkan data table post:

```blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ url('post/create') }}">Create</a>
            <table style="width:100%">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>User ID</th>
                  <th>Action</th>
                </tr>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->name }}</td>
                    <td>{{ $post->user_id }}</td>
                    <td> <a href="{{ url('post/edit/'.$post->id) }}">Edit</a> <a href="{{ url('post/delete/'.$post->id) }}">Delete</a></td> 
                </tr>
                @endforeach
            </table>
            <br> <a href="{{ url('/') }}">Back</a>
        </div>
    </div>
</div>
@endsection
```

Kemudian, buatlah view `post-detail.blade.php`. User yang menekan link edit atau delete pada view sebelumnya akan diarahkan ke view ini:

```blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $msg }}
            <br> <a href="{{ url('/post') }}">Back</a>
        </div>
    </div>
</div>
@endsection
```

Tambahkan code dibawah ini pada view `welcome.blade.php`:

```blade.php
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif
    <div class="hidden px-6 py-4 sm:block">
        <a href="{{ url('/post') }}" class="text-sm text-gray-700 underline">View Post</a>
    </div>
</div>
```

Tambahkan code di bawah ini pada route `routes\web.php`:
```php
Route::get('/private', [HomeController::class, 'private'])->name('private');
Route::get('/response', [HomeController::class, 'response'])->name('response');

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/create', [PostController::class, 'create']);
Route::get('/post/edit/{id}', [PostController::class, 'edit']);
```

### Langkah ketiga - membuat gate

Biasanya gates didefinisikan dalam method `boot` dari class `App\Providers\AuthServiceProvider` dengan menggunakan `Gate` facade. Pada gates, kita menggunakan method `define` untuk mendeklarasikan otorisasi baru yang menerima dua parameter. Parameter pertama adalah nama yang nantinya akan digunakan sebagai referensi untuk mengotorisasi user. Parameter kedua adalah closure. Pada closure, parameter pertama akan menerima user instance (defaultnya adalah user yang sedang login saat itu) dan dapat menerima argumen tambahan seperti model eloquent. 

Buka class `App\Providers\AuthServiceProvider` dan tambahkan gate seperti dibawah ini. Gate `before` akan dijalankan pertama kali sebelum otorisasi lainnya. Gate dengan nama `go-to-private` dan `update-post` hanya akan mengizinkan user admin mengakses resource tertentu. Gate dengan nama `go-to-responses` digunakan untuk mendemonstrasikan penggunaan response pada gate dan mereturn `Illuminate\Auth\Access\Response`.

```php
use Illuminate\Auth\Access\Response;
 public function boot()
{
    $this->registerPolicies();

    // before
    Gate::before(function ($user, $ability) {
        if ($user->isAdmin) {
            return true;
        }
    });

    Gate::define('go-to-private', function ($user) {
        return($user->isAdmin);
    });

    Gate::define('update-private', function ($user) {
       return true;
    });

    // Gate responses
    Gate::define('go-to-response', function (User $user) {
        return $user->isAdmin
                    ? Response::allow()
                    : Response::deny('You must be an administrator.');
    });

    // after, akan dijalankan terakhir
    // Gate::after(function ($user, $ability) {
    //     if ($user->isAdmin) {
    //         return true;
    //     }
    // });
}
```

Kemudian, kita akan menerapkan gate tersebut pada controller `app\Http\Controllers\HomeController`. Jika user dapat melewati gate tertentu, maka controller akan mereturn view yang sesuai. Untuk tutorial ini, kita akan menggunakan `Gate::allows` untuk otorisasi ke halaman `private`  dan Gate response untuk otorisasi ke halaman `response`. Method `Gate::allows` pada fungsi response akan tetap mereturn boolean, sehingga kita akan menggunakan method `Gate::inspect` untuk mendapatkan respons yang lebih lengkap:

```php
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Http\Models\User;
use DB;

public function private()
{
    // 1. allows
    if (Gate::allows('go-to-private')) {
        return view('private');
    }
    return 'You are not admin!';

    // 2. denies, hasilnya akan sama dengan allows
    // if (Gate::denies('go-to-private')) {
    //     return 'You are not admin!';
    // }
    // return view('private');

    // 3. check
    // if (Gate::check(['go-to-private', 'update-private'])) {
    //     return view('private');
    // }
    // return 'You are not admin!';

    // 4. any
    // if (Gate::any(['go-to-private', 'update-private'])) {
    //     return view('private');
    // }
    // return 'User cannot go to this page or update the page';

    // 5. none
    // if (Gate::none(['go-to-private', 'update-private'])) {
    //     return 'User cannot go to this page or update the page';
    // }
    // return view('private');

    // 6. authorize
    // Gate::authorize('go-to-private');
    // return view('private');

    // 7. for user - allows
    // $user = DB::table('users')->where('id', '1')->first();
    // if (Gate::forUser($user)->allows('go-to-private')) {
    //     return 'User with ID 1 can go to private page';
    // }

    // 8. for user - denies
    // $user = DB::table('users')->where('id', '2')->first();
    // if (Gate::forUser($user)->denies('go-to-private')) {
    //     return 'User with ID 2 cannot go to private page';
    // }
}

public function response()
{
    $response = Gate::inspect('go-to-response');

    if ($response->allowed()) {
        return view('response', ['msg' => 'custom response']); 
    } else {
        echo $response->message();
    }
}
```

Jalankan command `php artisan serve` dan buka http://localhost:8000/. Output halaman private adalah sebagai berikut:
Ketika diakses oleh admin :

![alt text](/Laravel-authentication-and-authorization/img/authorization-3-admin-private.PNG)

ketika diakses oleh user biasa :

![alt text](/Laravel-authentication-and-authorization/img/authorization-3-user-private.PNG)

dan berikut adalah output halaman response ketika diakses oleh admin :

![alt text](/Laravel-authentication-and-authorization/img/authorization-3-admin-response.PNG)

ketika diakses oleh user biasa :

![alt text](/Laravel-authentication-and-authorization/img/authorization-3-user-response.PNG)

### Langkah keempat - membuat policy

Buatlah model post dengan tambahan kolom `name` dan `user_id` yang berupa id dari user pemilik post. Kemudian, buatlah policy untuk model Post dengan menggunakan artisan command berikut :

```
php artisan make:policy PostPolicy --model=Post
```

Policy ini akan diletakkan di directory `app/Policies`. Setelah class policy dibuat, kita harus mendaftarkan policy tersebut pada `App\Providers\AuthServiceProvider`. Dengan ini, Laravel dapat mengetahui policy mana yang akan digunakan untuk mengotorisasi aksi terhadap suatu model. 

```php
protected $policies = [
    'App\Models\Post' => 'App\Policies\PostPolicy',
];
```

Selanjutnya, kita dapat menambahkan method untuk setiap request otorisasi pada class `PostPolicy` seperti pada kode dibawah ini:

```php
public function viewAny(User $user)
{
    return optional($user)->id > 0;
}

public function create(User $user)
{
    return $user->id > 0;
}

public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}

public function delete(User $user, Post $post)
{
    return $user->id === $post->user_id;
}

```

Jalankan command `php artisan serve` dan buka http://localhost:8000/. Ketika kita menekan link **View Post**, kita dapat melihat list dari post meskipun user belum melakukan login (Guest User). Hal ini dikarenakan pada method `viewAny`, user bersifat optional.

![alt text](/Laravel-authentication-and-authorization/img/authorization-4.PNG)

### Langkah kelima - menggunakan policy dengan model user

Model pada Laravel mempunyai 2 method untuk otorisasi, yaitu `can` dan `cannot`. Method ini menerima nama aksi yang ingin kita otorisasi dan model yang bersangkutan. Jika sebuah policy sudah didaftarkan pada model tersebut, method `can` akan secara otomatis memanggil policy tersebut. Jika tidak ada policy yang didaftarkan, maka method `can` akan memanggil gate yang sesuai dengan nama aksi tersebut.

Buatlah controller untuk `Post` berupa `PostController` dan tambahkan code dibawah ini:

```php
public function edit($id)
{
    $user = Auth::user();
    $post = Post::find($id);

    if ($user && $user->can('update', $post)) {
        return view('post-detail', ['msg' => 'User can edit post']); 
    }else{
        abort(403);
    }
}
```

Method `can` akan secara otomatis memanggil policy yang sesuai dengan model `Post`. Jika user biasa mencoba untuk mengedit post user lain, maka akan muncul error berikut :

![alt text](/Laravel-authentication-and-authorization/img/authorization-5-error.PNG)

Jika user biasa mencoba untuk mengedit postnya sendiri, akan muncul output berikut :

![alt text](/Laravel-authentication-and-authorization/img/authorization-5.PNG)

### Langkah keenam - menggunakan policy dengan helper controller

Laravel mempunyai method `authorize` pada controllers. Method ini akan melakukan throw exception `Illuminate\Auth\Access\AuthorizationException` ketika user tidak memiliki akses. Tambahkan code dibawah ini pada `PostController`:

```php
public function create()
{
    $this->authorize('create', Post::class);

    return view('post-detail', ['msg' => 'User can create post']); 
}
```

`create` adalah nama aksi otorisasi dan `Post::class` adalah model yang bersangkutan. Ketika user yang belum login mencoba untuk membuat post, error 403 akan ditampilkan :

![alt text](/Laravel-authentication-and-authorization/img/authorization-6-guest.PNG)

Jika user sudah login, maka akan muncul halaman berikut :

![alt text](/Laravel-authentication-and-authorization/img/authorization-6.PNG)

Method controller dibawah ini akan dipetakan ke method policy yang sesuai. Ketika sebuah request di arahkan ke method controller, method policy yang sesuai akan secara otomatis dipanggil sebelum method controller dijalankan:

| **Method Controller** | **Method Policy** |
| --------------------- | ----------------- |
| index                 | viewAny           |
| show                  | view              |
| create                | create            |
| store                 | create            |
| edit                  | update            |
| update                | update            |
| destroy               | delete            |


### Langkah ketujuh - menggunakan policy dengan middleware

Laravel mempunyai middleware yang dapat melakukan otorisasi aksi sebelum request sampai ke routes atau controllers. Tambahkan code dibawah ini pada route :

```php
Route::get('/post/delete/{post}', [PostController::class, 'destroy'])->middleware('can:delete,post');
```

Middleware `can` akan menerima dua argumen, yaitu nama aksi yang akan diotorisasi dan route parameter yang akan dipassing ke method policy, dalam hal ini adalah model `Post`.

### Langkah kedelapan - menggunakan policy dengan blade template

Ketika membuat template Blade, kita dapat menampilkan beberapa bagian dari halaman hanya kepada user tertentu. Sebagai contoh, kita ingin menampilkan link edit dan delete hanya kepada pemilik post. Kita dapat menggunakan method `can`, `cannot` atau `canany`. Tambahkan code berikut pada view `post.blade.php`:

```blade.php
@foreach($posts as $post)
<tr>
    <td>{{ $post->id }}</td>
    <td>{{ $post->name }}</td>
    <td>{{ $post->user_id }}</td>
    <td> 
        @canany(['update', 'delete'], $post)
        <a href="{{ url('post/edit/'.$post->id) }}">Edit</a> <a href="{{ url('post/delete/'.$post->id) }}">Delete</a>
        @endcanany
    </td> 
</tr>
@endforeach
```

Outputnya adalah sebagai berikut :

![alt text](/Laravel-authentication-and-authorization/img/authorization-8.PNG)

### Langkah kesembilan - menggunakan policy before

Method `before` atau `after` juga dapat digunakan pada policy. Kita dapat menambahkan method `before` pada class `PostPolicy` :

```php
public function before(User $user, $ability)
{
    if ($user->isAdmin) {
        return true;
    }
}
```

dengan ini, fungsi before akan dijalankan terlebih dahulu dan user admin dapat melakukan aksi apapun, termasuk mengedit postingan user lain. 

![alt text](/Laravel-authentication-and-authorization/img/authorization-10-before.PNG)

Jika kita ingin memberikan semua akses kepada user admin namun tetap membatasi hal tersebut, kita dapat menggunakan method `after` yang akan dijalankan setelah pengecekan otorisasi lainnya :

```php
// public function before(User $user, $ability)
// {
//     if ($user->isAdmin) {
//         return true;
//     }
// }

public function after(User $user, $ability)
{
    if ($user->isAdmin) {
        return true;
    }
}
```

Dengan ini, user admin tidak dapat lagi mengedit post user lain.

### Langkah kesepuluh - membuat custom policy discovery


Laravel dapat menemukan policies secara otomatis selama model dan policy tersebut memenuhi konvensi penamaan Laravel. Policies harus berada pada directory `Policies`. Laravel akan mengecek policies di folder `app/Models/Policies` kemudian `app/Policies`. Nama policy juga harus sesuai dengan nama model dan memiliki akhiran `Policy`. Jika kita ingin membuat logika custom policy discovery, kita dapat menggunakan method `Gate::guessPolicyNamesUsing`. Tambahkan code ini pada class `AuthServiceProvider` :

```php
protected $policies = [
    //'App\Models\Post' => 'App\Policies\PostPolicy',
];

public function boot()
{
    $this->registerPolicies();

    Gate::guessPolicyNamesUsing(function ($modelClass) {
        return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
    });
}
```

Dengan menggunakan guessPolicyNamesUsing, Laravel akan tetap dapat menemukan class policy yang sesuai.
