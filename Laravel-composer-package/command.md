# Command
[Kembali](readme.md)

## Latar belakang topik

Pernah mendengar artisan? Yaa benar, Laravel dikirimkan dengan file executable `artisan`, yang mana menyediakan banyak sekali command yang membantu kita.

## Konsep-konsep

Command biasanya kita ketikkan pada Command Line Interface (CLI). Yang mana, kita dapat memasukkan command command yang kita butuhkan seperti `php artisan make:controller Post` dan `php artisan serve`. Kita perlu untuk membuat kelas yang mengextend `Command` pada `use Illuminate\Console\Command`. Kelas ini nantinya akan memiliki:
- `$signature` yang merupakan merupakan command yang akan kita gunakan
- `$description` yang merupakan deskripsi dari command ini
- `handle()` yang merupakan method yang berisi apa yang akan command kita lakukan.

Kelas ini akan berada pada folder `Console` pada `src/`. Pada modul ini kita akan belajar bagaimana cara membuat artisan command yang sederhana untuk end user kita.

## Langkah-langkah tutorial
Pada tutorial ini, kita akan membuat command `php artisan helloworld:install` yang mana memudahkan user untuk mempublish file konfigurasi.

### Langkah pertama

Buat kelas bernama `InstallHelloWorld.php` dan letakkan pada `src/Console`. Kita akan kita gunakan sebagai kelas pengatur command kita. Kelas ini akan memberi info ketika kita menginstall, kemudian akan memanggil command lain untuk mempublish file config, juga akan mengecek apakah file config sudah ada atau belum.

```php
<?php

namespace maximuse\HelloWorld\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallHelloWorld extends Command
{
    protected $signature = 'helloworld:install';

    protected $description = 'Install HelloWorld';

    public function handle()
    {
        // Info ketika telah memulai instalasi
        $this->info('Installing HelloWorld...');

        $this->info('Publishing configuration...');

        // Cek jika file config sudah ada
        if (!$this->configExists('helloworld.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            // Konfirmasi apakah ingin mengoverwrite yang telah ada
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        // Info ketika telah selesai
        $this->info('Successfully Installed HelloWorld');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "maximuse\HelloWorld\CalculatorServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = '';
        }

       $this->call('vendor:publish', $params);
    }
}
```

### Langkah kedua

Setelah command jadi, kiota register command tersebut pada service provider kita pada method `boot()`:
```php
...

public function boot()
{
    // Mengizinkan user menggunakan command melalui CLI
    if ($this->app->runningInConsole()) {
        $this->commands([
            InstallHelloWorld::class,
        ]);
    }
}
```

### Langkah ketiga
Kita bisa melakukan testing pada command kita yang telah kita buat tadi. Kita buat file `InstallHelloWorldTest.php`. Karena kita menggunakan `Orchestra Testbench`. Kita dapat mengecek keberadaan file `helloworld.php`.

```php
<?php

namespace maximuse\HelloWorld\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use maximuse\HelloWorld\Tests\TestCase;

class InstallHelloWorldTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('helloworld.php'))) {
            unlink(config_path('helloworld.php'));
        }

        $this->assertFalse(File::exists(config_path('helloworld.php')));

        Artisan::call('helloworld:install');

        $this->assertTrue(File::exists(config_path('helloworld.php')));
    }
}
```

### Langkah keempat
Laravel juga mengizinkan kita untuk membuat command yang dapat disembunyikan dari list Artisan command. kita hanya perlu untuk menambahkan properti `$hidden` yang bernilai true. Namun meskipun bersifat hidden, user tetap dapat menggunakan command ini.

```php
class InstallHelloWorld extends Command
{
    protected $hidden = true;

    protected $signature = 'helloworld:install';

    protected $description = 'Install the HelloWorld';

    public function handle()
    {
        // ...
    }
}
```