# Laravel-Eloquent

[Kembali](readme.md)

## Konsep Model Pada Laravel 

Laravel adalah kerangka kerja aplikasi web berbasis PHP yang sumber terbuka, menggunakan konsep Model-View-Controller

Model, Model mewakili struktur data. Biasanya model berisi fungsi-fungsi yang membantu seseorang dalam pengelolaan basis data seperti memasukkan data ke basis data, pembaruan data dan lain-lain.

Eloquent ORM ( Object Relational Mapping ) merupakan teknik untuk memetakan basis data relasional ke model objek. 

Laravel menyertakan Eloquent, sebuah object-relational mapper (ORM) yang membuatnya menyenangkan untuk berinteraksi dengan database. Saat menggunakan Eloquent, setiap tabel database memiliki "Model" yang sesuai yang digunakan untuk berinteraksi dengan tabel itu. Selain mengambil catatan dari tabel database, model Eloquent memungkinkan untuk menyisipkan, memperbarui, dan menghapus catatan dari tabel juga ( Melakukan CRUD ).

Berinteraksi dengan database seperti menampilkan, menambah,
mengubah atau menghapus data menggunakan Eloquent ORM lebih disarankan walaupun kita dapat menggunakan Query Builder tanpa membuat model terlebih dahulu.

`ELOQUENT` mengaharuskan kita mendefinisikan kolom-kolom yang kita gunakan, namun kita
tidak perlu mendefinisikan semua kolom, hanya kolom yang boleh diisi oleh pengguna (fillable )



### Membuat Model 

jalankan perintah melalui php artisan dan tambahkan -m jika ingin membuat migration dari model yang ingin kita buat

```
php artisan make:model Car --migration

php artisan make:model Car -m
```

```php
# Generate a model and a CarFactory class...
php artisan make:model Car --factory
php artisan make:model Car -f

# Generate a model and a CarSeeder class...
php artisan make:model Car --seed
php artisan make:model Car -s

# Generate a model and a CarController class...
php artisan make:model Car --controller
php artisan make:model Car -c

# Generate a model and a migration, factory, seeder, and controller...
php artisan make:model Car -mfsc

# Shortcut to generate a model, migration, factory, seeder, and controller...
php artisan make:model Car --all

# Generate a pivot model...
php artisan make:model CarProduct --pivot
```

buat controller dari modelnya 

```
php artisan make:controller CarsController --resource

php artisan make:controller CarsController -r


saat di cek di route akan secara automatis mengikat model dengan fungsi-fungsi yang disediakan dicontroller

php artisan route:list
```


### Eloquent Model Conventions

Model yang dihasilkan oleh perintah make:model  akan ditempatkan di direktori app/Models. 

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    //
    protected $table = 'my_flights';
    protected $primaryKey = 'flight_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $dateFormat = 'U';

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
    
    protected $connection = 'sqlite';
    
    protected $attributes = [
        'delayed' => false,
    ];

    protected static function booted()
    {
        static::addGlobalScope(new AncientScope);
    }

        protected static function booted()
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->where('created_at', '<', now()->subYears(2000));
        });
    }
}

```


### Relasi


untuk memahami relasi sebelumnya sudah disediakan diagram modelnya seperti berikut untuk memudahkan 

[gambar relasi]


#### one to many

terdapat dua tabel didalam database yaitu car dan car_models dengan ketentuan berikut

maka dari kedua model tersebut memiliki relasi one to many karena 1 car memiliki banyak car model sedangkan 1 car model hanya dimiliki oleh 1 car

buat model-modelnya dan definisikan fungsi relasinya

    car.php

 ```php
   public function carmodels()
    {
        return $this->hasMany(CarModel::Class);
    } 
```

lalu definisikan inversenya    

```php
    carmodel.php
        // A car model belongs to a car 
    public function car(){
        return $this->belongsTo(Car::class);

    }
```

jika sudah maka cek relasinya apakah model-model itu sudah saling terhubung
dengan cara : 

debug datanya melalui controller 

```php
    $car = Car::find($id);
    dd($car->carmodels);   

    // manggil carmodel dari model car
    $cm = $car->carModel;
    dd($cm);
    
    // manggil car dari model use App\Models\Carmodel;
    $cm = cm::find($id);
    dd($cm);
    
```
jika data sudah tampil maka terhubung


#### one to one

terdapat dua tabel didalam database yaitu car dan headquarters(kantor besar) dengan ketentuan berikut

maka dari kedua model tersebut memiliki relasi one to one dikarenakan 1 car memiliki satu headquarter dan 1 headquarter juga hanya dimiliki oleh 1 car

buat model-modelnya dan definisikan fungsi relasinya

buat dulu modelnya sm migrasinya untuk headquarter

    car.php
```php
    public function headquarter(){
        return $this->hasOne(Headquarter::class);
    }
```
```php
public function car(){
    return $this->belongsTo(Car::class);
}
```

jika sudah maka cek relasinya apakah model-model itu sudah saling terhubung
dengan cara : 

debug datanya melalui controller 

```php
    $car = Car::find($id);
    dd($car->headquarter);   
    
    // manggil headquarter dari model car
    $hq = $car->headquarter; 
    dd($hq);

    // manggil dari model use App\Models\Headquarter;
    $hq = Headquarter::find($id);
    dd($hq->car);
```
jika data sudah tampil maka terhubung

#### many to many

car.php
```php
    public function headquarter(){
        return $this->hasOne(Headquarter::class);
    }
```

#### hasManyThrough/hasOneThrough

##### hasManyThrough

car.php
```php
    // Define a has many through relationship
    public function engine(){
        return $this->hasManyThrough(
                    Engine::class ,
                    CarModel::class,
                    'car_id', // Foreign key on CarModel Table //
                    'model_id' // Foreign key on Engine Table
            );
    }
```
