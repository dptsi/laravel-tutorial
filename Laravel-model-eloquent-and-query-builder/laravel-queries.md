# Laravel-Queries

[Kembali](readme.md)

## Query Builder 

Query Builder merupakan salah satu cara untuk menjalankan query database dengan
lebih mudah, Query Builder juga telah dilengkapi dengan fitur keamanan untuk mencegah
terjadinya SQL Injection (adalah sebuah aksi hacking yang dilakukan di aplikasi client dengan cara memodifikasi perintah SQL yang ada di memori aplikasi client). Selain itu kita dapat menggunakan query builder tanpa harus membuat model terlebih dahulu

### Setting env
Untuk mempraktekan contoh dari latihan menampilkan data dengan QUERY BUILDER pertama kita harus memeriksa file `.env` apakah projek sudah terhubung dengan database atau belum. File ini terletak pada bagian luar projek laravel yang dibuat, dan pastikan pada bagian mysql sudah terkonfigurasi seperti pada gambar dibawah:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=model_pbkke
DB_USERNAME=root
DB_PASSWORD=
```

### Ubah Controller dengan Database Query Builder

#### Mengambil Semua Baris Dari Tabel
 
dengan menggunakan metode `table()` yang disediakan oleh laravel pada `Illuminate\Support\Facades\DB;` yang akhirnya menampilkan hasil query menggunakan metode `get()`  

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Show a list of all of the application's cars.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = DB::table('cars')->get();

        return view('cars.index', ['cars' => $cars]);
    }
}
```
metode `get()` akan mereturn/mengembalikan Illuminate\Support\Collectioninstance yang berisi hasil query di mana setiap hasil adalah turunan dari PHP stdClass objek
jadi nilai dapat diakses setiap kolom dengan mengakses kolom sebagai properti objek:

```php
use Illuminate\Support\Facades\DB;

$users = DB::table('users')->get();

foreach ($users as $user) {
    echo $user->name;
}
```
#### Debugging 

untuk mengecek apakah variabel cars terisi maka kita dapat menggunakan fitur debug yang disediakan oleh laravel yaitu die and dump /dd

```php
dd($cars)
```
atau karena laravel juga ditulis dalam bahasa php maka  kita juga bisa menggunakan var_dump() , echo() ,print() , dan print_r()
```php
var_dump($cars)
```

#### Mengambil Satu Baris / Kolom Dari Tabel ( Metode get(),value(),where(),first() ,find() )

Jika hanya perlu mengambil satu baris dari tabel database,  dapat menggunakan metode `first()`  . Metode ini akan mengembalikan satu stdClass objek

```php
$user = DB::table('users')->where('name', 'John')->first();

return $user->email;
```

Jika tidak membutuhkan seluruh baris, bisa juga  mengekstrak satu nilai dari sebuah record menggunakan metode `value()`. Metode ini akan mengembalikan nilai kolom secara langsung:

```php
$email = DB::table('users')->where('name', 'John')->value('email');
```

Untuk mengambil satu baris dengan nilai id kolomnya, gunakan metode find()

```php
$user = DB::table('users')->find(3);
```

#### Mengambil Daftar Nilai Kolom ( Metode Pluck() )

gunakan metode `pluck()` jika ingin mengambil sebuah data yang berisi nilai-nilai dari satu kolom
Dalam contoh ini, akan mengambil kumpulan judul pengguna:

```php
use Illuminate\Support\Facades\DB;

$titles = DB::table('cars')->pluck('name');

foreach ($cars as $car) {
    echo $title;
}
```
Anda dapat menentukan kolom yang harus digunakan oleh koleksi hasil sebagai kuncinya dengan memberikan argumen kedua ke metode `pluck()`:

```php
$titles = DB::table('users')->pluck('title', 'name');

foreach ($titles as $name => $title) {
    echo $title;
}
```

#### Hasil Chunking

Jika Anda perlu bekerja dengan ribuan rekaman database, pertimbangkan untuk menggunakan `chunk()` metode yang disediakan oleh kelas facade DB. Metode ini mengambil sebagian kecil hasil pada satu waktu dan memasukkan setiap potongan ke dalam penutupan untuk diproses. misal terdapat 3 data di database

lalu menggunakan metode `chunk()`

```php
$cars = DB::table('cars')->orderBy('id')->chunk(2, function ($cars) {
    foreach ($cars as $car) {
        print_r($car);
    }
});
```

maka datanya akan di split menjadi 2 sesuai dengan chunk(2) dan akan tampil sebagai berikut

```c
 //Array 0
 => Array ( 
    [0] => stdClass Object ( [id] => 1 [name] => audi [founded] => 1909 [description] => desc for audi [created_at] => [updated_at] => )
    [1] => stdClass Object ( [id] => 2 [name] => mercedes [founded] => 1926 [description] => desc for mercedes [created_at] =>[updated_at=> ) ) ) 
  
  //Array 1
  => Array ( 
    [0] => stdClass Object ( [id] => 3 [name] => toyota [founded] => 2000 [description] => desc [created_at] => [updated_at] =

```
Ada juga metode `chunkById()` yang cara kerjanya sama dengan `chunk()` namun metode ini akan mengurutkan sesuai Primary Key dari tabel.

#### Menggunakan Metode Lazy()
Metode `Lazy()` mirip dengan metode chunk, bedanya metode `chunk()` tidak melakukan callback setiap chunknya, tapi metode ini akan menghasilkan LazyCollection

#### Menggunakan Agregat (  count(), max(), min(), avg(), dan sum()   )
Terdapat beberapa metode untuk mengambil nilai-nilai agregat seperti `count()`, `max()`, `min()`,`avf()` dan `sum()`
```php
$cars = DB::table('cars')->count();
```
```php
$cars = DB::table('cars')->max('founded');
```
```php
$cars = DB::table('cars')->avg('founded');
```
```php
$cars = DB::table('cars')->sum('founded');
```



Menentukan Jika Rekaman Ada( exist() )
```php
if (DB::table('cars')->where('id', 1)->exists()) {
    return 1;
}
```

```php
if (DB::table('cars')->where('id', 10)->doesntExist()) {
    return 1;
}
```

#### Menggunakan clausa `Select` (  select() , distinct() , addSelect()  )

karena yang dibutuhkan saat menggunakan laravel tidak selalu ingin memilih semua kolom dari tabel database. Dengan menggunakan metode select() bisa menentukan klausa `select` khusus untuk kueri-kueri tertentu:

```php
$cars = DB::table('cars')
            ->select('name', 'founded')
            ->get();
```

```php
$cars = DB::table('cars')->distinct()
                ->select('name')
                ->get();
```


```php
$query = DB::table('cars')->select('name');
$cars = $query->addSelect('founded')->get();
```





#### Raw Expression
DB::raw()digunakan untuk membuat perintah SQL sewenang-wenang yang tidak diurai lebih jauh oleh pembuat kueri.

```sql
SELECT COUNT(*) AS founded_count
FROM founded
WHERE founded < 1999
GROUP BY founded
```
```php
      $cars = DB::table('cars')
             ->select(DB::raw('count(*) as founded_count, founded'))
             ->where('founded', '<', 1999)
             ->groupBy('founded')
             ->get();
```

#### Raw Method
##### SelectRaw
```php
$cars = DB::table('cars')
            ->select(DB::raw('count(*) as founded_count, founded'))
            ->where('founded', '<', 1999)
            ->groupBy('founded')
            ->get();
        
        $cars = DB::select(DB::raw('select * from cars'));
        $cars = DB::select(DB::raw('select * from cars'))->get();
        $cars = DB::select(DB::raw('SELECT * FROM cars WHERE founded = .9999'));
        
        $cars = DB::table('cars')
               ->selectRaw('((founded - ?) * (-1)) as berdiri_selama', [2021])
               ->get();

        // stackoverflow
        $cars = DB::table('cars')
            ->selectRaw('COUNT(*) AS result')
            ->get();

            // Returns a collection of PHP objects,
            // You can call collections method fluently on the result
            // It is cleaner.

        $cars = DB::select(DB::raw("SELECT COUNT(*) AS result FROM cars"));

            //Returns an array of Php object
```

##### WhereRaw/orWhereRaw
```php
$cars = DB::table("cars")
        ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i:%s'))"),'2021-05-28 15:07:35')
        ->get();

$cars = DB::table("cars")
        ->whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d %H:%i:%s') = '2021-05-28 15:07:35'")
        ->get();

$cars = DB::table('cars')
        ->whereRaw('id%2 <> 0 ')
        ->get();
```

##### HavingRaw/orHavingRaw
```php
$cars = DB::table('cars')
        ->select('name', DB::raw('SUM(founded as lama_tahun'))
        ->groupBy('name')
        ->havingRaw('SUM(founded > ?', [2500])
        ->get();
```

##### OrderByRaw
```php
$cars = DB::table('cars')
        // ->orderByRaw('updated_at - created_at DESC')
        ->orderByRaw('name asc')
        ->get();
```

##### GroupByRaw
```php
$cars = DB::table('cars')
        ->select('name', 'founded')
        ->groupByRaw('name, founded')
        ->get();

$cars = DB::table('cars')
        ->select(DB::raw('founded , COUNT(id) as JUMLAH'))
        ->groupBy(DB::raw('founded'))
        ->get();
```
#### Joins
##### Inner Join Clause
```php
$cars = DB::table('cars')
        ->join('car_models', 'cars.id', '=', 'car_models.id')
        ->join('car_production_dates', 'car_models.id', '=', 'car_production_dates.id')
        ->select('car_models.model_name', 'car_production_dates.created_at')
        ->get();
```
##### Left Join / Right Join Clause
```php
$cars = DB::table('car_models')
            ->leftJoin('engines', 'car_models.id', '=', 'engines.id')
            ->get();
```
```php
$cars = DB::table('car_models')
            ->rightJoin('engines', 'car_models.id', '=', 'engines.id')
            ->get();
```
##### Cross Join Clause
```php
$cars = DB::table('car_models')
            ->crossJoin('engines')
            ->get();
```
##### Advanced Join Clauses
```php
$cars = DB::table('car_models')
                    ->join('engines', function ($join) {
                        $join->on('car_models.id', '=', 'engines.id')
                             ->where('engines.id', '<', 3);
                    })
                    ->get();
```
<!-- ##### Subquery Joins -->

#### Unions
```php
$first = DB::table('car_models')
                ->whereNotNull('model_name');
        dd($first);

        $cars = DB::table('car_models')
                ->whereNotNull('car_id')
                ->union($first)
                ->get();
```
#### Menggunakan clausa `Where`

```php
$cars = DB::table('cars')
                ->where('id', '<', 3)
                ->where('founded', '<', 2000)
                ->get();
```

```php
$cars = DB::table('cars')->where('id', 3)->get();
```

```php
$cars = DB::table('cars')
                ->where('founded', '>=', 2000)
                ->get();

$cars = DB::table('cars')
                ->where('founded', '<>', 2000)
                ->get();

$cars = DB::table('cars')
                ->where('name', 'like', 'T%') // t kecil ttp terbaca sm seperti di sql
                ->get();

```

```php
$cars = DB::table('cars')->where([
    ['id', '=', '1'],
    ['founded', '<>', '2000'],
])->get();
```


#### Menggunakan clausa `or Where`

```php
$cars = DB::table('cars')
        ->where('id', '>', 4)
        ->orWhere('name', 'audi')
        ->get();
```

```php
$cars = DB::table('cars')
    ->where('id', '<', 3)
    ->orWhere(function($query) {
        $query->where('name', 'tyotaa');
      })
    ->get();
```

#### Tambahan klausa Where
```php
$cars = DB::table('cars')
            ->whereBetween('founded', [1800, 1900])
            ->get();   
```
```php
$cars = DB::table('cars')
            ->whereNotBetween('founded', [1800, 1900])
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereIn('id', [1, 2, 3])
            ->whereIn('id', [1, 2, 3, 99])
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereNotIn('id', [1, 2, 3])
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereNull('updated_at')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereNotNull('updated_at')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereDate('created_at', '2021-05-28')                 
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereMonth('created_at', '5')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereDay('created_at', '28')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereYear('created_at', '2021')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereTime('created_at', '=', '15:07:35')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereColumn('name', 'founded')
            ->get();
```
```php
$cars = DB::table('cars')
            ->whereColumn('updated_at', '>', 'created_at')
            ->get();
```
```php
$cars = DB::table('cars')
        ->whereColumn([
            ['name', '=', 'founded'],
            ['updated_at', '>', 'created_at'],
        ])->get();
```
```php
$cars =  ();
            $cars = DB::table('cars')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                        ->from('tabe; lain')
                        ->whereColumn('cars.id', 'cars.founded');
            })
            ->get();
```

### Ordering, Grouping, Limit & Offset

#### Ordering

##### Metode orderBy
Metode `orderBy()` merupakan metode mengurutkan hasil output berdasarkan kolom yang diinginkan

desc

```php
$cars = DB::table('cars')
                ->orderBy('name', 'desc')
                ->get();
```

asc

```php
$cars = DB::table('cars')
                ->orderBy('name', 'asc')
                ->get();
```

##### Metode `latest()` dan `oldest()`

Metode `latest()` dan `oldest()` akan menampilkan hasil berdasarkan tanggal. Secara default, hasil akan diurutkan berdasarkan `created_at` kolom tabel . Atau, Anda dapat memberikan nama kolom yang ingin Anda urutkan:

```php
$cars = DB::table('cars')
                ->latest()
                ->first();
```

##### Pemesanan Acak
Metode `inRandomOrder()` dapat digunakan untuk mengurutkan hasil query secara acak.

```php
$Cars = DB::table('cars')
                ->inRandomOrder()
                ->first();
```

###### Menghapus Orderings yang Ada
Metode `reorder()` menghilangkan semua "order by" klausa yang sebelumnya telah diterapkan pada query:

```php
$query = DB::table('cars')->orderBy('name');
$cars = $query->reorder()->get();
```

##### Grouping
##### Metode groupBy dan having
metode `groupBy()` dan `having()` mengelompokkan hasil kueri. Tanda metode `having()` ini mirip dengan metode `where()`:

```php
$cars = DB::table('cars')
        ->groupBy('founded')
        ->having('founded', '>', 2000)
        ->get();
```
##### Limit dan Offset

###### Metode skip() Metode take()

Anda dapat menggunakan metode `skip()` dan `take()` untuk membatasi jumlah hasil yang dikembalikan dari kueri atau untuk melewati sejumlah hasil dalam kueri:

```php
$cars = DB::table('cars')->skip(2)->take(1)->get();
```

Sebagai alternatif, Anda dapat menggunakan metode limitdan offset. Metode ini secara fungsional setara dengan metode takedan skip, masing-masing:

```php
$users = DB::table('users')
                ->offset(10)
                ->limit(5)
                ->get();
```


#### Conditional Clauses


#### Insert Statements ( Insert() ke database / CREATE -> STORE KE DATABASE)

Pembuat kueri juga menyediakan metode `insert()` yang dapat digunakan untuk memasukkan catatan ke dalam tabel database. Metode`insert()` menerima array nama kolom dan nilai-nilai:
```php
DB::table('cars')->insert([
    'name' => 'szuki',
    'founded' => 2203
]);
```

bisa juga memasukan (insert) beberapa record sekaligus secara bersamaan dengan menggunakan array
Setiap array mewakili record yang harus dimasukkan ke dalam tabel
```php
DB::table('cars')->insert([
    ['id' => 10,'name' => 'szuki', 'founded' => 2201, 'description' => 'desc'],
    ['id' => 11,'name' => 'szukii', 'founded' => 2202, 'description' => 'desc'],
]);
```
The Metode insertOrIgnore() akan mengabaikan catatan kesalahan duplikat saat memasukkan catatan ke dalam database

```php
DB::table('cars')->insertOrIgnore([
    // ke 9 masuk
    ['id' => 9,'name' => 'szuki', 'founded' => 2201, 'description' => 'desc'],
    // ke 11 engga
    ['id' => 11,'name' => 'tyotaa', 'founded' => 2202, 'description' => 'desc'],
]);
```
##### Auto-Incrementing IDs

Jika tabel memiliki id auto-incrementing, gunakan metode insertGetId() untuk memasukkan record lalu ambil ID

```php
$id = DB::table('cars')->insertGetId(
    ['name' => 'guling', 'founded' => 1998]
);
```
##### Metode Upserts()

The Metode `upsert()` akan memasukkan catatan yang tidak ada dan memperbarui catatan yang sudah ada dengan nilai-nilai baru yang Anda dapat menentukan. Argumen pertama metode terdiri dari nilai yang akan disisipkan atau diperbarui, sedangkan argumen kedua mencantumkan kolom yang secara unik mengidentifikasi rekaman dalam tabel terkait. Argumen ketiga dan terakhir metode ini adalah larik kolom yang harus diperbarui jika rekaman yang cocok sudah ada dalam database:

```php
DB::table('cars')->upsert([
    ['name' => 'supra', 'founded' => 9998, 'description' => 'Desc'],
    ['name' => 'supraa', 'founded' => 9999, 'description' => 'Desc']
], ['name', 'founded'], ['description']);
```

Dalam contoh di atas, Laravel akan mencoba memasukkan dua record. Jika rekaman sudah ada dengan nilai `name` dan `founded` kolom yang sama , Laravel akan memperbarui kolom `description` rekaman itu .

#### Update Statements ( Update )

Terkadang Anda mungkin ingin memperbarui rekaman yang sudah ada di database atau membuatnya jika tidak ada rekaman yang cocok. Dalam skenario ini,metode `updateOrInsert()` dapat digunakan. The Metode `updateOrInsert()` menerima dua argumen: array kondisi dimana untuk menemukan catatan, dan sebuah array dari kolom dan nilai pasangan menunjukkan kolom diperbarui.

The Metode `updateOrInsert()` akan berusaha untuk menemukan catatan database yang cocok menggunakan argumen pertama kolom dan nilai pasangan. Jika rekaman ada, itu akan diperbarui dengan nilai-nilai di argumen kedua. Jika record tidak dapat ditemukan, record baru akan disisipkan dengan atribut gabungan dari kedua argumen:

```php
DB::table('cars')
    ->updateOrInsert(
        ['name' => 'supra'],
        ['founded' => 1799, 'description' => 'desc for supraaaa']
);
```


##### Memperbarui Kolom JSON
mbo gapaham

```php
$affected = DB::table('cars')
              ->where('id', 1)
              ->update(['options->enabled' => true]);
```

##### Increment & Decrement
Pembuat kueri juga menyediakan metode yang mudah untuk menambah atau mengurangi nilai kolom tertentu. Kedua metode ini menerima setidaknya satu argumen: kolom yang akan diubah. Argumen kedua dapat diberikan untuk menentukan jumlah kolom yang harus ditambah atau dikurangi:

```php
DB::table('cars')->increment('founded');

DB::table('cars')->increment('founded', 1);

DB::table('cars')->decrement('founded');

DB::table('cars')->decrement('founded', 1);
```
#### Delete Statements( DELETE )
Metode `delete()` merupakan metode untuk menghapus sebuah tabel

```php
DB::table('cars')->delete();

DB::table('cars')->where('founded', '>', 3000)->delete();
```

Jika ingin menghapus isi tabel namun tidak menghapus tabelnya, maka bisa menggunakan `truncate()`
```php
DB::table('users')->truncate();
```

#### Pessimistic Locking
Metode ini memungkinkan query yang ditampilkan tidak bisa diupdate(read-only)

```php
DB::table('users')
        ->where('votes', '>', 100)
        ->sharedLock()
        ->get();
```
```php
DB::table('users')
        ->where('votes', '>', 100)
        ->lockForUpdate()
        ->get();
```
