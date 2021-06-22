# Laravel Feature Testing

[Kembali](readme.md)

## Latar belakang topik

Pada unit testing, kita sudah belajar bagaimana cara menguji masing masing komponen secara individu. Namun, bagaimana cara kita menguji aplikasi kita ketika komponennya digabungkan?

Disini masuk **Feature Testing**.

Pada feature testing, kita akan menguji fitur-fitur aplikasi kita.

## Konsep-konsep

Banyak cara untuk menguji fitur dari aplikasi laravel kita, bisa dengan tool dari laravel sendiri, atau dari tool eksternal seperti Postman.

Namun, pada topik kali ini, kita hanya akan membicarakan feature testing dengan 2 cara : 

[HTTP Tests](#http-tests)  
[Browser Tests](#browser-tests)

## Langkah-langkah tutorial

## HTTP Tests

HTTP tests adalah cara kita menguji HTTP route dan request aplikasi kita, seperti layaknya menggunakan `Postman`. Namun, pada HTTP test dari Laravel, kita tidak benar-benar mengirim HTTP request lewat jaringan, melainkan keseluruhan jaringan pengiriman disimulasikan dalam internal Laravel.
### Basic Test

Untuk membuat Feature Testing di Laravel, dapat menggunakan perintah berikut:

```
php artisan make:test PostTest
```

Lalu masukkan kode dibawah ini didalam file PostTest yang telah dibuat.

```
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

```
php artisan test
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

Untuk membuat Unit Testing di Laravel, dapat menggunakan perintah berikut:

```
php artisan dusk:make PostTest
```

Lalu masukkan kode dibawah ini didalam file PostTest yang telah dibuat.

```
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

```
php artisan dusk
```