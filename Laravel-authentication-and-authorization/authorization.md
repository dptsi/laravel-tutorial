# Authorization

[Kembali](readme.md)

## Latar belakang topik

Biasanya dalam suatu sistem atau aplikasi, kita akan menemukan banyak user yang akan berinteraksi dengan sistem. Jika semua user yang akan mengakses sistem memiliki izin yang sama, maka data yang seharusnya hanya boleh diakses oleh orang tertentu akan bebas diakses oleh user lain. Sebagai contoh, laporan keuangan disuatu sistem perusahaan yang bersifat rahasia akan dapat diakses oleh semua user. Oleh karena itu, diperlukan otorisasi dalam sebuah sistem.

## Konsep-konsep

Otorisasi mengizinkan user yang sudah terotentikasi untuk mengakses resource tertentu dalam sebuah sistem. Sebagai contoh, ketika sebuah user mencoba untuk mengakses bagian admin, sistem akan melakukan verifikasi (mengotorisasi) apakah user memiliki izin untuk mengakses bagian tersebut. Jika user memiliki izin, maka user akan diberi otorisasi dan diberikan akses ke bagian admin. Jika tidak, akses user tersebut akan ditolak.

Laravel menyediakan 2 cara untuk mengotorisasi tindakan, yaitu dengan menggunakan gates dan policies.

### Gates

#### Writing Gates

Gates adalah otorisasi menggunakan pendekatan closure. Biasanya gates didefinisikan dalam method `boot` dari class `App\Providers\AuthServiceProvider` dengan menggunakan `Gate` facade. Pada gates, kita menggunakan method `define` untuk mendeklarasikan otorisasi baru yang menerima dua parameter. Parameter pertama adalah nama yang nantinya akan digunakan sebagai referensi untuk mengotorisasi user. Parameter kedua adalah closure. Pada closure, parameter pertama akan menerima user instance (defaultnya adalah user yang sedang login saat itu) dan dapat menerima argumen tambahan seperti model eloquent. 

Sebagai contoh, kita akan mendefinisikan gate untuk menentukan apakah user dapat mengupdate model `App\Models\Post`. Gate ini akan membandingkan id user dengan user_id pemilik post:

```use App\Models\Post;
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

```<?php
namespace App;
class PostPolicy 
{
    public function update (User $user, Post $post) {
        return $user->id === $post->user_id;
    }
}
```

Lalu di class `App\Providers\AuthServiceProvider`:

```use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;

public function boot()
{
    $this->registerPolicies();

    Gate::define('update-post', [PostPolicy::class, 'update']);
}
````

#### Authorizing Actions

Untuk mengecek apakah seorang user dapat melakukan suatu tindakan seperti create, kita dapat menggunakan method `allows`. Pada contoh dibawah ini, kita hanya perlu mempassing `$post` karena Laravel sudah secara otomatis mempassing user yang sedang login saat ini.

```<?php

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

```use App\Models\Category;
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

```if (Gate::allows('update-post', $post)) {
        // user can update post   
   }
```

**2. denies**

negasi dari `allows`. Seperti `allows`, denies juga akan mereturn boolean.


```if (Gate::denies('update-post', $post)) {
        abort(403);        
   }
```

**3. check** 

Checks if a single or array of abilities are allowed. Check akan mereturn boolean.

**4. any**

Digunakan untuk melakukan otorisasi beberapa gate dalam waktu yang sama. Any akan mereturn boolean.

```if (Gate::any(['update-post', 'delete-post'], $post)) {
    // The user can update or delete the post...
}
```

**5. none**

negasi dari **any**. Seperti any, none akan mereturn boolean.

```if (Gate::none(['update-post', 'delete-post'], $post)) {
    // The user can't update or delete the post...
}
```

**7. authorize**

Jika kita ingin melakukan otorisasi dan secara otomatis melakukan throw exception `Illuminate\Auth\Access\AuthorizationException` ketika user tidak diizinkan, maka kita dapat menggunakan method `authorize`. Instance dari `AuthorizationException` secara otomatis akan dikonversi ke 403 HTTP response oleh exception handler Laravel. 

```Gate::authorize('update-post', $post);```


Selain itu, jika kita ingin menemukan apakah ada user selain dari user yang sedang login / terautentikasi bisa melakukan tindakan, maka kita dapat menggunakan method **`forUser`** pada facade `Gate` :

```if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```

#### Gate Responses

Method yang telah disebutkan sebelumnya akan mereturn boolean. Terkadang, kita ingin mereturn response yang lebih detil. Untuk itu, kita dapat mereturn `Illuminate\Auth\Access\Response` dari gate:

```use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

Gate::define('edit-settings', function (User $user) {
    return $user->isAdmin
                ? Response::allow()
                : Response::deny('You must be an administrator.');
});
```

Method `Gate::allows` pada contoh tersebut akan tetap mereturn boolean. Kita dapat menggunakan method `Gate::inspect` untuk mendapatkan respons yang lebih lengkap:

```$response = Gate::inspect('edit-settings');

if ($response->allowed()) {
    // The action is authorized...
} else {
    echo $response->message();
}
```

#### Intercepting Gate Checks

Kita dapat menggunakan method `before` untuk mendefinisikan closure yang akan dijalankan sebelum method otorisasi lainnya:

```use Illuminate\Support\Facades\Gate;

Gate::before(function ($user, $ability) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

Kita juga dapat menggunakan method `after` untuk mendefinisikan closure yang akan dieksekusi setelah semua pengecekan otorisasi:

```Gate::after(function ($user, $ability, $result, $arguments) {
    if ($user->isAdministrator()) {
        return true;
    }
});
```

Jika closure `before` atau `after` mereturn non-null maka hasil tersebut akan dianggap hasil dari pengecekan otorisasi.

### Policies

#### Generating Policies

Policies adalah class yang 

#### Registering Policies

#### Writing Policies

### Perbedaan Gates dan Policies




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
