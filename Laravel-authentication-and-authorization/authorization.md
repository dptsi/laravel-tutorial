# Authorization

[Kembali](readme.md)

## Latar belakang topik

Biasanya dalam suatu sistem atau aplikasi, kita akan menemukan banyak user yang akan berinteraksi dengan sistem. Jika semua user yang akan mengakses sistem memiliki izin yang sama, maka data yang seharusnya hanya boleh diakses oleh orang tertentu akan bebas diakses oleh user lain. Sebagai contoh, laporan keuangan disuatu sistem perusahaan yang bersifat rahasia akan dapat diakses oleh semua user. Oleh karena itu, diperlukan otorisasi dalam sebuah sistem.

## Konsep-konsep

Otorisasi mengizinkan user yang sudah terotentikasi untuk mengakses resource tertentu dalam sebuah sistem. Sebagai contoh, ketika sebuah user mencoba untuk mengakses bagian admin, sistem akan melakukan verifikasi (mengotorisasi) apakah user memiliki izin untuk mengakses bagian tersebut. Jika user memiliki izin, maka user akan diberi otorisasi dan diberikan akses ke bagian admin. Jika tidak, akses user tersebut akan ditolak.

Laravel menyediakan 2 cara untuk mengotorisasi tindakan, yaitu dengan menggunakan gates dan policies. Gates biasanya digunakan ke aksi yang tidak berhubungan dengan model atau resource apapun, seperti melihat dashboard admin. Sedangkan policies harus digunakan ketika kita ingin mengotorisasi tindakan pada model atau resource tertentu. Biasanya, Gates didefinisikan dalam method boot pada class 

### Gates

#### Writing Gates

Gates adalah closure yang menentukan apakah user memiliki izin atau otorisasi untuk melakukan sesuatu. Biasanya gates didefinisikan dalam method `boot` dari class `App\Providers\AuthServiceProvider` dengan menggunakan `Gate` facade. Gates selalu menerima user instance sebagai argumen pertama dan dapat menerima argumen tambahan seperti model eloquent.

Sebagai contoh, kita akan mendefinisikan gate untuk menentukan apakah user dapat mengupdate model `App\Models\Post`. Gate akan mencoba untuk membandingkan id user yang akan mengupdate model dengan id user yang membuat post :

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

```use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;

public function boot()
{
    $this->registerPolicies();

    Gate::define('update-post', [PostPolicy::class, 'update']);
}
````

#### Authorizing Actions

Untuk mengotorisasi tindakan menggunakan gates, kita harus menggunakan method `allows` atau `denies` yang disediakan oleh facade `Gate`. Kita tidak diharuskan untuk mempassing user yang sedang terotentikasi ke method ini karena akan dipassing secara otomatis oleh laravel.

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

Jika kita ingin menemukan apakah ada user selain dari user yang sedang terautentikasi bisa melakukan tindakan, maka kita dapat menggunakan method `forUser` pada facade `Gate` :

```if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```

Kita dapat mengotorisasi banyak tindakan dalam satu waktu dengan menggunakan method `any` atau `none`

```if (Gate::any(['update-post', 'delete-post'], $post)) {
    // The user can update or delete the post...
}

if (Gate::none(['update-post', 'delete-post'], $post)) {
    // The user can't update or delete the post...
}
```

##### Authorizing Or Throwing Exceptions

Jika kita ingin mencoba untuk mengotorisasi sebuah tindakan dan secara otomatis melakukan throw `Illuminate\Auth\Access\AuthorizationException` jika user tidak diperbolehkan untuk melakukan tindakan tersebut, maka kita dapat menggunakan method `authorize` pada facade `method.` Instance dari `AuthorizationException` secara otomatis akan dikonversi ke 403 HTTP response oleh exception handler Laravel :

```Gate::authorize('update-post', $post);

// The action is authorized...
```

##### Supplying Additional Context

Method gate dengan kemampuan otorisasi (`allows, denies, check, any, none, authorize, can, cannot` dan otorisasi blade directives (`@can, @cannot, @canany`) bisa menerima array sebagai argumen kedua. Elemen array ini akan dipassing sebagai parameter ke gate closure, dan dapat digunakan sebagai tambahan ketika membuat sebuah keputusan otorisasi.

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

if (Gate::check('create-post', [$category, $pinned])) {
    // The user can create the post...
}
```

#### Gate Responses

#### Intercepting Gate Checks

### Creating Policies

#### Generating Policies

#### Registering Policies

### Writing Policies




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
