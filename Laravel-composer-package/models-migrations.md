# Models dan Migrations

[Kembali](readme.md)

## Latar belakang topik

Ada banyak case dimana kita membutuhkan untuk memasukkan satu atau lebih Eloquent models ke package kita, seperti ketika misalnya ingin membuat package tentang Ecommerce, kita perlu memasukkan model model seperti produk, transaksi, dan pembayaran.

## Konsep-konsep

Model yang berada pada package **tidak berbeda** dengan model yang biasa kita gunakan di aplikasi laravel biasa. Karena kita menggunakan `Orchestra Testbench`, kita akan membuat kelas beekstensi laravel eloquent model pada direktori `src/Models`

Migration pada projek laravel berada di folder `database/migrations`. Sehingga di package kita, kita tidak menyimpan file migration pada `src/` melainkan pada direktori baru pada `database/migrations`.

Ada beberapa cara untuk mengenerate model bersamaan langsung dengan migrationnya. Cara yang paling mudah dan yang akan kita terapkan pada tutorial ini adalah dengan menggunakan projek laravel 'dummy' dan kemudian kita copy-paste file migration dan moidelnya dan kita ubah namespace nya. 

Setelah membuat file model dan migration, kita perlu untuk melakukan publish migration. Ada dua cara untuk melakukan publish migration, cara yang pertama adalah kita meregister pada `ServiceProvider` Bahwa package kita mempubish migrasinya, sedangkan yang kedua adalah kita menggunakan helper bernama `loadMigrationFrom` yang dapat dilihat lebih lanjut pada link [ini]('https://laravel.com/docs/8.x/packages#migrations').

Terkadang, kita ingin membuat sebuah model yang memiliki dependensi terhadap model bawaan dari laravel (e.g. user), padahal model `User` ini baru terbentuk ketika kita menginstall framework Laravel. Kita dapat menangani masalah seperti ini dengan menggunakan pendekatan memfetch User model dari Auth config, atau dapat menggunakan Polymorphic Relationship yang dapat dilihat lebih lengkapnya pada link [ini]('https://laravelpackage.com/08-models-and-migrations.html#models-related-to-app-user').

## Langkah-langkah tutorial

Pada tutorial ini, kita akan membuat sebuah model `History`, kemudian kita buat migrationnya, dan kemudian kita publish migrationnya.

### Langkah pertama

Kita buat sebuah model `History.php` pada projek laravel 'dummy' kita dan copy isinya ke direkstori `src/Models`. kemudian ganti namespace nya agar sesuai dengan nama package kita.

```php
// 'src/Models/History.php'
<?php

namespace maximuse\HelloWorld\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  use HasFactory;

  // Mendisable proteksi untuk mengassign dari laravel
  protected $guarded = [];
}
```

### Langkah kedua

Setelah membuat model, lakukan migrate pada projek 'dummy' untuk mendapatkan migrasi buatan Laravel sesuai kebutuhan kita. Kemudian copy file hasil migration ke package kita pada direktori `database/migrations`.

```php
// 'database/migrations/2021_06_06_100000_create_histories_table.php'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->integer('result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
```

### Langkah ketiga

Setelah file model dan migration dibuat, kita lakukan publish migration. Seperti yang telah dijelaskan di atas, akan ada dua cara.

#### Metode 1 
Pada metode 1, kita akan meregister migration kita di `ServiceProvider` pada method `boot()` dengan method `publishes` yang menerima 2 argumen, yang pertama adalah array dari file path ("source path" => "destination path") dan yang kedua adalah nama ("tag"). Kita akan menggunakan "stubbed" migration yang diexport ke real migration ketika user mempublish migrations nya. Maka dari itu, kita rename file migration kita dengan menghapus timestamp pada nama file dan tambahkan `.stub` di akhir nama file. Sehingga nama file migration kita akan menjadi `created_histories_table.php.stub`.

```php
class CalculatorServiceProvider extends ServiceProvider
{
  public function boot()
  {
    if ($this->app->runningInConsole()) {
      // Export migration
      if (!class_exists('CreateHistoriesTable')) {
        $this->publishes([
          __DIR__ . '/../database/migrations/create_histories_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_histories_table.php'),
          // kita dapat menambahkan migration lain disini
        ], 'migrations');
      }
    }
  }
}
```

Pada implementasi diatas, kita klakukan pengecekkan terlebih dahulu apakah user telah mempublish migration ini, juka belum maka kita akan publish migration `create_histories_table`. Migration dari package ini sekarang sudah bisa di publish dengan menggunakan tag 'migrations' dengan command 
```php
php artisan vendor:publish --provider="maximuse\HelloWorld\CalculatorServiceProvider" --tag="migrations"
```

#### Metode 2
Metode kedua ini menawarkan loading migration secara otomatis dengan bantuan `loadMigrationsFrom`. Kita hanya perlu untuk menetapkan direktori migration pada `ServiceProvider`, kemudian semua migration akan dieksekusi ketika user mengeksekusi `php artisan migrate`.

```php
class BlogPackageServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
  }
}
```

satu hal yang perlu untuk diperhatikan pada metode ini adalah timestamp pada nama file migration, ketika timestamp pada nama file tidak valid, maka laravel tidak akan memprosesnya.