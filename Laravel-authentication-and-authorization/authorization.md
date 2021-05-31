# Authorization

[Kembali](readme.md)

## Daftar Isi
- [Latar belakang topik](#latar-belakang)  
- [Konsep-konsep](#konsep)
    - [Gates](#gates)
       - [Menulis gates](#menulis-gates)
       - [Authorizing actions](#authorize-actions)
       - [Gate responses](#gate-responses)
       - [Intercepting Gate Checks](#gate-checks)
    - [Policies](#policies)
       - [Menulis policies](#menulis-policies)
       - [Guest users](#guest)
       - [Policy Filters](#policy-filters)
       - [Authorizing actions using policies](#authorize-policies)
         - [Via user model](#via-model)
         - [Via controller helpers](#via-controllers)
         - [Via middleware](#via-middleware)
         - [Via blade templates](#via-blade)
- [Langkah-langkah tutorial](#tutorial)
 

<a name="latar-belakang"/>

## Latar belakang topik

Biasanya dalam suatu sistem atau aplikasi, kita akan menemukan banyak user yang akan berinteraksi dengan sistem. Jika semua user yang akan mengakses sistem memiliki izin yang sama, maka data yang seharusnya hanya boleh diakses oleh orang tertentu akan bebas diakses oleh user lain. Sebagai contoh, laporan keuangan disuatu sistem perusahaan yang bersifat rahasia akan dapat diakses oleh semua user. Oleh karena itu, diperlukan otorisasi dalam sebuah sistem.

<a name="konsep"/>

## Konsep-konsep

Otorisasi mengizinkan user yang sudah terotentikasi untuk mengakses resource tertentu dalam sebuah sistem. Sebagai contoh, ketika sebuah user mencoba untuk mengakses bagian admin, sistem akan melakukan verifikasi (mengotorisasi) apakah user memiliki izin untuk mengakses bagian tersebut. Jika user memiliki izin, maka user akan diberi otorisasi dan diberikan akses ke bagian admin. Jika tidak, akses user tersebut akan ditolak.

Laravel menyediakan 2 cara untuk mengotorisasi aksi, yaitu dengan menggunakan gates dan policies.

<a name="gates"/>

### Gates

<a name="menulis-gates"/>

#### Menulis Gates

Gates adalah otorisasi menggunakan pendekatan closure. Biasanya gates didefinisikan dalam method `boot` dari class `App\Providers\AuthServiceProvider` dengan menggunakan `Gate` facade. Pada gates, kita menggunakan method `define` untuk mendeklarasikan otorisasi baru yang menerima dua parameter. Parameter pertama adalah nama yang nantinya akan digunakan sebagai referensi untuk mengotorisasi user. Parameter kedua adalah closure. Pada closure, parameter pertama akan menerima user instance (defaultnya adalah user yang sedang login saat itu) dan dapat menerima argumen tambahan seperti model eloquent. 

Sebagai contoh, kita akan mendefinisikan gate untuk menentukan apakah user dapat mengupdate model `App\Models\Post`. Gate ini akan membandingkan id user dengan user_id pemilik post:

```php
use App\Models\Post;
use App\Models\user;
use Illuminate\Support\Facades\Gate;

public function boot(){
    $this->registerPolicies();
    
    Gate::define('update-post', function(User $user, Post $post) {
        return $user->id === $post->user_id;
    });
}
```

Seperti controllers, gates juga bisa didefinisikan menggunakan class callback array :

```php
<?php
namespace App;
class PostPolicy 
{
    public function update (User $user, Post $post) {
        return $user->id === $post->user_id;
    }
}
```

Lalu di class `App\Providers\AuthServiceProvider`:

```php
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;

public function boot()
{
    $this->registerPolicies();

    Gate::define('update-post', [PostPolicy::class, 'update']);
}
````

<a name="authorize-actions"/>

#### Authorizing Actions

Untuk mengecek apakah seorang user dapat melakukan suatu aksi seperti create, kita dapat menggunakan method `allows`. Pada contoh dibawah ini, kita hanya perlu mempassing `$post` karena Laravel sudah secara otomatis mempassing user yang sedang login saat ini.

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Update the given post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (! Gate::allows('update-post', $post)) {
            abort(403);
        }

        // Update the post...
    }
}
```

Jika kita membutuhkan lebih dari 1 parameter pada closure, maka kita dapat menggunakan array. Sebagai contoh :

```php
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

Gate::define('create-post', function (User $user, Category $category, $pinned) {
    if (! $user->canPublishToGroup($category->group)) {
        return false;
    } elseif ($pinned && ! $user->canPinPosts()) {
        return false;
    }

    return true;
});

if (Gate::allows('create-post', [$category, $pinned])) {
    // The user can create the post...
}
```

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

Checks if a single or array of abilities are allowed. Check akan mereturn boolean.

**4. any**

Digunakan untuk melakukan otorisasi beberapa gate dalam waktu yang sama. Any akan mereturn boolean.

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


Selain itu, jika kita ingin menemukan apakah ada user selain dari user yang sedang login / terautentikasi bisa melakukan aksi, maka kita dapat menggunakan method **`forUser`** pada facade `Gate` :

```php
if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```

<a name="gate-responses"/>

#### Gate Responses

Method yang telah disebutkan sebelumnya akan mereturn boolean. Terkadang, kita ingin mereturn response yang lebih detil. Untuk itu, kita dapat mereturn `Illuminate\Auth\Access\Response` dari gate:

```php
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

Gate::define('edit-settings', function (User $user) {
    return $user->isAdmin
                ? Response::allow()
                : Response::deny('You must be an administrator.');
});
```

Method `Gate::allows` akan tetap mereturn boolean. Kita dapat menggunakan method `Gate::inspect` untuk mendapatkan respons yang lebih lengkap:

```php
$response = Gate::inspect('edit-settings');

if ($response->allowed()) {
    // The action is authorized...
} else {
    echo $response->message();
}
```

<a name="gate-checks"/>

#### Intercepting Gate Checks

Kita dapat menggunakan method `before` untuk mendefinisikan closure yang akan dijalankan sebelum method otorisasi lainnya:

```php
use Illuminate\Support\Facades\Gate;

Gate::before(function ($user, $ability) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

Kita juga dapat menggunakan method `after` untuk mendefinisikan closure yang akan dieksekusi setelah semua pengecekan otorisasi:

```php
Gate::after(function ($user, $ability, $result, $arguments) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

Jika closure `before` atau `after` mereturn non-null maka hasil tersebut akan dianggap hasil dari pengecekan otorisasi.

<a name="policies"/>

### Policies

Policies adalah class yang mengatur logika otorisasi pada suatu model atau resource. Kita dapat membuat class policy dengan menggunakan Artisan command `make:policy` dengan opsi `--model`. Policy ini akan diletakkan di directory `app/Policies`.

Setelah class policy dibuat, kita harus mendaftarkan policy tersebut. Dengan ini, Laravel dapat mengetahui policy mana yang akan digunakan untuk mengotorisasi aksi terhadap suatu model. Kita dapat mendaftarkan kelas policy tersebut di `App\Providers\AuthServiceProvider`.

```php
protected $policies = [
        Post::class => PostPolicy::class,
];
```

Laravel juga dapat menemukan policies secara otomatis selama model dan policy tersebut memenuhi konvensi penamaan Laravel. Policies harus berada pada directory `Policies`. Laravel akan mengecek policies di folder `app/Models/Policies` lalu `app/Policies`. Nama policy juga harus sesuai dengan nama model dan memiliki akhiran `Policy`. Sebagai contoh, model `User` akan memiliki class policy `UserPolicy`.

Kita juga dapat membuat custom policy discovery menggunakan method `Gate:guessPolicyNamesUsing`. Method ini dapat dipanggil pada method `boot` di `AuthServiceProvider`:

```php
use Illuminate\Support\Facades\Gate;

Gate::guessPolicyNamesUsing(function ($modelClass) {
    // Return the name of the policy class for the given model...
});
```

<a name="menulis-policies"/>

#### Menulis Policies

Kita dapat menambahkan beberapa method untuk setiap aksi yang akan diotorisasikan di class policy. Sebagai contoh :

```php
<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
```

Kita dapat menambahkan beberapa method tambahan seperti `view` ataupun `delete` untuk otorisasi beberapa aksi yang melibatkan model `Post`. Penamaan method di policy bersifat bebas. Kita juga dapat menggunakan method `Gate:: ` yang sudah dijelaskan sebelumnya dan mereturn instance `Illuminate\Auth\Access\Response` pada policy.  

Terkadang, ada beberapa method policy yang hanya perlu menerima instance dari user yang sedang login saat ini. Situasi ini umum terjadi ketika mengotorisasi aksi `create`.

```php
public function create(User $user)
{
    return $user->role == 'writer';
}
```

<a name="guest"/>

#### Guest Users

Secara default, semua gates dan policies secara otomatis mereturn `false` jika user tidak login. Namun, kita dapat mendeklarasikan sebuah type-hint "optional" atau menyediakan nilai default null pada parameter user:

```php
<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function update(?User $user, Post $post)
    {
        return optional($user)->id === $post->user_id;
    }
}
```

<a name="policy-filters"/>

#### Policy Filters

Kita dapat menggunakan method `before` ataupun `after` pada policy:

```php
use App\Models\User;

/**
 * Perform pre-authorization checks.
 *
 * @param  \App\Models\User  $user
 * @param  string  $ability
 * @return void|bool
 */
public function before(User $user, $ability)
{
    if ($user->isAdministrator()) {
        return true;
    }
}
```

Method `before` tidak akan dijalankan jika class tersebut tidak mempunyai method dengan nama yang sama dengan nama ability yang akan dicek.

<a name="authorize-policies"/>

#### Authorizing Actions Using Policies

<a name="via-model"/>

**Via The User Model**

Model pada Laravel mempunyai 2 method untuk otorisasi, yaitu `can` dan `cannot`. Method ini menerima nama aksi yang ingin kita otorisasi dan model yang bersangkutan:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Update the given post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }

        // Update the post...
    }
}
```

Jika sebuah policy sudah didaftarkan pada model tersebut, method `can` akan secara otomatis memanggil policy tersebut. Jika tidak ada policy yang didaftarkan, maka method `can` akan memanggil gate yang sesuai dengan nama aksi tersebut.

Beberapa aksi seperti `create` tidak memerlukan instance model. Sehingga, kita dapat mempassing nama class pada method `can`.

```php
if ($request->user()->cannot('create', Post::class)) {
    abort(403);
}
```

<a name="via-controllers"/>

**Via Controller Helpers**

Laravel menyediakan beberapa method `authorize` yang merupakan extend dari class `App\Http\Controllers\Controller`.

```php
public function update(Request $request, Post $post)
{
    $this->authorize('update', $post);

    // The current user can update the blog post...
}
```

Jika method policy tidak memerlukan instance model, kita dapat mempassing nama class:
```php
public function create(Request $request)
{
    $this->authorize('create', Post::class);

    // The current user can create blog posts...
}
```

Jika kita menggunakan resource controllers, kita dapat menggunakan method `authorizeResource` pada constructor controller. Method ini akan menambahkan middleware `can` yang sesuai pada method di resource controller. Method ini menerima nama class model sebagai argumen pertama dan nama route / request parameter yang akan berisi id model sebagai argumen kedua. 

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
}
```

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

<a name="via-middleware"/>

**Via Middleware**

Laravel mempunyai middleware yang dapat melakukan otorisasi aksi sebelum request sampai ke routes atau controllers. Secara default, middleware `Illuminate\Auth\Middleware\Authorize` mempunyai key `can` pada class `App\Http\Kernel`.

```php
use App\Models\Post;

Route::put('/post/{post}', function (Post $post) {
    // The current user may update the post...
})->middleware('can:update,post');
```

Pada contoh ini, kita mempassing middleware `can` dengan dua argumen. Argumen pertama adalah nama aksi yang kita ingin melakukan otorisasi dan argumen kedua adalah parameter route yang ingin kita pass ke method policy. Dalam contoh ini, model `App\Models\Post` akan di passing ke method policy. 

<a name="via-blade"/>

**Via Blade Templates**

Ketika membuat template Blade, kita dapat menampilkan beberapa bagian dari halaman hanya kepada user tertentu. Sebagai contoh, kita ingin menampilkan form update hanya jika user tersebut dapat mengupdate post. Kita dapat menggunakan directives `@can` dan `@cannot`.

```php
@can('update', $post)
    <!-- The current user can update the post... -->
@elsecan('create', App\Models\Post::class)
    <!-- The current user can create new posts... -->
@else
    <!-- ... -->
@endcan

@cannot('update', $post)
    <!-- The current user cannot update the post... -->
@elsecannot('create', App\Models\Post::class)
    <!-- The current user can now create new posts... -->
@endcannot
```

Kita juga dapat menggunakan `@canany` jika aksi berupa array:
```php
@canany(['update', 'view', 'delete'], $post)
    <!-- The current user can update, view, or delete the post... -->
@elsecanany(['create'], \App\Models\Post::class)
    <!-- The current user can create a post... -->
@endcanany
```

<a name="tutorial"/>

## Langkah-langkah tutorial

### Langkah pertama - menambah kolom pada database user

Buka file user migration yang terletak di directory `database\migrations` dan tambahkan satu kolom dengan nama **isAdmin** yang berupa boolean:

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

### Langkah kedua - membuat view private dan response

Jalankan command `php artisan ui:auth` untuk membuat sistem login dan register. Buatlah data user seperti gambar berikut:
 ![alt text](/img/authorization-auth.PNG)

Lalu, Buatlah file `private.blade.php` dan `response.blade.php`, file ini nantinya hanya dapat diakses oleh user admin. Berikut adalah file `private.blade.php`:

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            Halaman private, hanya admin yang dapat melihat halaman ini.
            <br> <a href="{{ url('/home') }}">Kembali</a>
        </div>  
    </div>
</div>
@endsection
```

dan berikut adalah file `response.blade.php`:
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ $msg }}
            <br> <a href="{{ url('/home') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
```

Tambahkan kedua file tersebut di route `routes\web.php`:
```php
Route::get('/private', [HomeController::class, 'private'])->name('private');
Route::get('/response', [HomeController::class, 'response'])->name('response');
```

### Langkah ketiga - membuat gate

Buka class `App\Providers\AuthServiceProvider` dan tambahkan gate seperti contoh dibawah ini. Gate `before` akan dijalankan pertama kali dan gate `after` akan dijalankan terakhir. Gate dengan nama `go-to-private` dan `update-post` hanya akan mengizinkan user admin mengakses resource tertentu. Gate dengan nama `go-to-responses` digunakan untuk mendemonstrasikan penggunaan response pada gate.

```php
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

    Gate::define('update-post', function ($user) {
       return($user->isAdmin);
    });

    // Gate responses
    Gate::define('go-to-response', function (User $user) {
        return $user->isAdmin
                    ? Response::allow()
                    : Response::deny('You must be an administrator.');
    });

    // after
    // Gate::after(function ($user, $ability) {
    //     if ($user->isAdmin) {
    //         return true;
    //     }
    // });
}
```

Kemudian, kita akan menerapkan gate tersebut pada controller `app\Http\Controllers\HomeController`. Jika user memiliki dapat melewati gate tertentu, maka controller akan mereturn view yang sesuai. Untuk tutorial ini, kita akan menggunakan `Gate::allows` pada fungsi private dan Gate response pada fungsi response:

```php
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
    // if (Gate::check('go-to-private')) {
    //     return view('private');
    // }
    // return 'You are not admin!';

    // 4. any
    // if (Gate::any(['go-to-private', 'update-post'], Post::class)) {
    //     return view('private');
    // }
    // return 'user cannot go to this page or update the page';

    // 5. none
    // if (Gate::none(['go-to-private', 'update-post'], Post::class)) {
    //     return 'user cannot go to this page or update the page';
    // }
    // return view('private');

    // 6. authorize
    // Gate::authorize('go-to-private');
    // return view('private');

    // 7. for user - allows
    // $user = DB::table('users')->where('id', '1')->first();
    // if (Gate::forUser($user)->allows('go-to-private')) {
    //     return 'user with ID 1 can go to private page';
    // }

    // 8. for user - denies
    // $user = DB::table('users')->where('id', '2')->first();
    // if (Gate::forUser($user)->denies('go-to-private')) {
    //     return 'user with ID 2 cannot go to private page';
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

### Langkah keempat - hasil gates

Jalankan command `php artisan serve` dan buka http://localhost:8000/. Output halaman private adalah sebagai berikut:
Ketika diakses oleh admin :
![alt text](/img/authorization-output-admin-private.PNG)

ketika diakses oleh user biasa :
![alt text](/img/authorization-output-user-private.PNG)

dan berikut adalah output halaman response ketika diakses oleh admin :
![alt text](/img/authorization-output-admin-response.PNG)

ketika diakses oleh user biasa :
![alt text](/img/authorization-output-user-response.PNG)

### Langkah kelima - membuat policy




### Langkah keenam

### Langkah ketujuh

### Langkah kedelepan

### Langkah kesembilan

### Langkah kesepuluh
