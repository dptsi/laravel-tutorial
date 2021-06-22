# Laravel Feature Testing

[Kembali](readme.md)

## Latar belakang topik

Pada unit testing, kita sudah belajar bagaimana cara menguji masing masing komponen secara individu. Namun, bagaimana cara kita menguji aplikasi kita ketika komponennya digabungkan?

Disini masuk **Feature Testing**.

Pada feature testing, kita akan menguji fitur-fitur aplikasi kita.

## Konsep-konsep

Banyak cara untuk menguji fitur dari aplikasi laravel kita, bisa dengan tool dari laravel sendiri, atau dari tool eksternal seperti Postman.

Namun, pada topik kali ini, kita hanya akan membicarakan feature testing dengan 2 cara : 

[HTTP Tests](#http-tests) dengan [dokumentasi ini](https://laravel.com/docs/8.x/http-tests)  
[Browser Tests](#browser-tests) dengan [dokumentasi ini](https://laravel.com/docs/8.x/dusk)

## Langkah-langkah tutorial

## HTTP Tests

HTTP tests adalah cara kita menguji HTTP route dan request aplikasi kita, seperti layaknya menggunakan `Postman`. Namun, pada HTTP test dari Laravel, kita tidak benar-benar mengirim HTTP request lewat jaringan, melainkan keseluruhan jaringan pengiriman disimulasikan dalam internal Laravel.
### Basic Test

Untuk membuat Feature Testing di Laravel, dapat menggunakan perintah berikut:

```bash
php artisan make:test PostTest
```

Lalu masukkan kode dibawah ini didalam file PostTest yang telah dibuat.

```php
<?php

namespace Tests\Feature;

use App\Modules\Post\Core\Domain\Model\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_welcome_status()
    {
        $response = $this->get('/post');

        $response->assertStatus(200);
    }
}

```
Untuk menjalankan test file ini, dapat menjalankan perintah

```bash
php artisan test
```
Jika ada kesalahan atau error, cek lagi versi laravel atau lihat [dokumentasi](https://laravel.com/docs/8.x/http-tests)
### HTTP Authentication

Laravel menyediakan helper function untuk memudahkan testing HTTP Request dengan session. Developer bisa menggunakan `actingAs` untuk membuat request layaknya dibuat oleh user tertentu.

```php
public function test_an_action_that_requires_authentication()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->get('/');
}
```
Pada contoh ini, user dibuat oleh user factory, dan digunakan untuk membuat request.Request yang diterima akan layaknya dikirim oleh `$user`. Lihat lebih lengkap di [dokumentasi](https://laravel.com/docs/8.x/http-tests#session-and-authentication)

### Test File Upload

Laravel menyediakan `Illuminate\Http\UploadedFile` menyediakan method `fake` untuk membuat dummy file atau image untuk testing. Hal ini, digabungkan dengan method `fake` facade `Storage`, sangat mempermudah testing upload file.
```php
public function test_avatars_can_be_uploaded()
{
    Storage::fake('avatars');

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->post('/avatar', [
        'avatar' => $file,
    ]);

    Storage::disk('avatars')->assertExists($file->hashName());
}
```

### HTPP Result & Response

Selain menggunakan assertion yang [ada](https://laravel.com/docs/8.x/http-tests#available-assertions), laravel juga menyediakan cara untuk mendebug result & response dari HTTP test dengan men-`dump` mereka.
```php
public function test_basic_test()
{
    $response = $this->get('/');

    $response->dumpHeaders();

    $response->dumpSession();

    $response->dump();
}
```
## Browser Tests

Pada browser test ini, kita menggunakan Laravel Dusk.

Laravel Dusk adalah browser automation and testing API yang disediakan oleh Laravel. Dusk tidak mengharuskan kamu untuk menginstall JDK atau Selenium ke browser kita, melainkan Dusk menggunakan ChromeDriver. Namun, kita bebas menggunakan driver yang compatible dengan Selenium yang lain.

Untuk menggunakan browser tests, kita harus menambahkan laravel/dusk ke dependency composer.

```
composer require --dev laravel/dusk
```
Setelah menginstall dusk, jalankan `dusk:install`
```
php artisan dusk:install
```

Jika menggunakan laravel sail, bisa ikuti petunjuk [ini](https://laravel.com/docs/8.x/sail#laravel-dusk) untuk installasi dusk.

Jika perlu enviroment yang berbeda ketika menjalankan dusk, bisa membuat file `.env.dusk.{enviroment}` seperti `.env.dusk.development` atau `.env.dusk.local`

### Basic Test

Untuk membuat Browser Test di Laravel, dapat menggunakan perintah berikut:

```bash
php artisan dusk:make PostTest
```

Lalu masukkan kode dibawah ini didalam file PostTest yang telah dibuat.

```php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->assertSee('Post');
        });
    }
}

```
Untuk menjalankan test file ini, dapat menjalankan perintah

```bash
php artisan dusk
```

### Advanced Test with navigation and typing

Pada test ini kita akan mencontohkan fungsi untuk menguji halaman membuat post.

```php
public function testCreateVisible()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/post')
            ->clickLink('Create post')
            ->type('title', "TestCreatePost2")
            ->type('description', "TestCreatePostDescription2")
            ->press('submit-post')
            ->assertSee('TestCreatePost2');
    });
}
```

Disini kita bisa memvisualisasikan apa yang terjadi di test kita. 

```php
$browser->visit('/post')
```
untuk datang ke `APP_URL:APP_PORT/post` untuk mengetahui lebih lanjut cara navigasi bisa cek [ini](https://laravel.com/docs/8.x/dusk#navigation)
```php
->clickLink('Create post')
```
untuk menekan link dengan tulisan `Create post` untuk mengetahui lebih lanjut cara memilih elemen HTML dengan dusk bisa cek [ini](https://laravel.com/docs/8.x/dusk#interacting-with-elements)
```php
->type('title', "TestCreatePost2")
->type('description', "TestCreatePostDescription2")
```
untuk memasukkan input di form dengan nama `title` dan `description`, lalu
```php
->press('submit-post')
```
untuk menekan tombol dengan nama `submit-post`, dan
```php
->assertSee('TestCreatePost2');
```
untuk melakukan assertion. Untuk list lengkap assertion yang ada bisa melihat di [sini](https://laravel.com/docs/8.x/dusk#available-assertions)

### Laravel Dusk Page

Laravel dusk memiliki fitur `Page` yang bisa digunakan untuk memudahkan testing pada halaman yang memiliki banyak aksi. 

Untuk membuat dusk page, gunakan command
```bash
php artisan dusk:page Login
```

Page memiliki tiga method default, 
* `url` untuk mendefinisikan URL yang merepresentasikan page
* `assert` untuk mendefinisikan assertion yang dilakukan untuk memastikan bahwa kita sudah berada pada page tertentu
* `elements` untuk mendefinisikan shortcut untuk CSS selector dalam page tertentu

Untuk datang ke suatu page tertentu, gunakan
```php
// $browser->visit(new [PageClassName])
$browser->visit(new Login)
```

Page bisa memiliki method yang bisa dipanggil untuk memudahkan testing
```php
public function login(Browser $browser, $username, $password)
{
    $browser->type('username', $username)
            ->type('password', $password)
            ->press('Create Playlist');
}
```
lalu pada test bisa menggunakan method tersebut dengan
```php
$browser->visit(new Login)
        ->login('user','password')
        ->assertSee('Successfully Login');
```

### Laravel Dusk Component
Laravel dusk memiliki fitur `Component` yang dapat digunakan untuk komponen UI yang muncul di beberapa tempat di aplikasi, oleh karena itu tidak memiliki URL tertentu.

Untuk membuat dusk component, gunakan command

```bash
php artisan dusk:component DatePicker
```

penggunaan dari dusk component sama seperti dusk [Page](#laravel-dusk-page), namun melainkan `url`, dusk component menggunakan method `selector` untuk mendefinisikan CSS selector dari komponen tersebut
```php
public function selector()
{
    return '.date-picker';
}
```
Sama seperti page, dusk component bisa memiliki method yang dapat digunakan untuk mempermudah testing.
```php
public function selectDate(Browser $browser, $year, $month, $day)
{
    $browser->click('@date-field')
            ->within('@year-list', function ($browser) use ($year) {
                $browser->click($year);
            })
            ->within('@month-list', function ($browser) use ($month) {
                $browser->click($month);
            })
            ->within('@day-list', function ($browser) use ($day) {
                $browser->click($day);
            });
}
```
Untuk menggunakan dusk component
```php
public function testBasicExample()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->within(new DatePicker, function ($browser) {
                    $browser->selectDate(2019, 1, 30);
                })
                ->assertSee('January');
    });
}
```

### Laravel Dusk Continous Integration

Untuk menjalankan dusk tests dengan framework CI yang kalian mau, bisa cek tutorial terbaru di [dokumentasi](https://laravel.com/docs/8.x/dusk#continuous-integration)





