<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheController extends Controller
{
    // Storing items in the cache
    public function storeCache() {

        $key = "user";
        $value = "nada";
        $ttl = 60; // in seconds
        
        // if( Cache::put($key, $value, $ttl) )
        //     echo "Data berhasil ditambahkan dalam cache";
        // else
        //     echo "Data tidak bisa ditambahkan";
        
        // Cache::put($key, $value);
        // Cache::put($Key, $value, now()->addSeconds(30));

        // Store if not present
        // if ( Cache::add($key, $value, $ttl) )
        //     echo "Data berhasil ditambahkan dalam cache";
        // else
        //     echo "Data sudah ada dalam cache";

        // Storing item forever
        if( Cache::forever('angka', 12) )
            echo "Data berhasil ditambahkan dalam cache";
        else
            echo "Data tidak bisa ditambahkan";

    }

    // Retrieving items from the cache
    public function retrieveCache() {
        
        // $value = Cache::get('user');
        // $value = Cache::get('user', 'Tidak ada data');

        // Checking for item existence
        // if (Cache::has('angka')) {
        //     $value = Cache::get('angka');
        //     echo $value;
        // }
        // else
        //     echo "Tidak ada data";
        
        // Incrementing or decrementing values
        // $value = Cache::increment('angka');
        // $value = Cache::increment('angka', 5);
        // $value = Cache::decrement('angka');
        // $value = Cache::decrement('angka', 5);

        // Retrieve and store
        // $value = Cache::remember('user', 60, function () {
        //     return 'nada';
        // });
        $value = Cache::rememberForever('email', function () {
            return 'nada@gmail.com';
        });

        // Retrieve and delete
        // $value = Cache::pull('email');

        // Print
        echo $value;

        // $check = Cache::get('email', 'Tidak ada data');
        // echo $check;
    }

    // Removing items from the cache
    public function removeCache() {

        // Metode forget
        // if ( Cache::forget('angka') )
        //     echo "Data telah dihapus dari cache";
        // else
        //     echo "Data gagal dihapus";

        // Metode put
        // if ( Cache::put('user', 'nada', 0) )
        //     echo "Data telah dihapus dari cache";
        // else
        //     echo "Data gagal dihapus";

        // Cache::put('user, 'nada', -5);

        // Metode flush
        if ( Cache::flush() )
            echo "Semua cache data telah dihapus";
        else
            echo "Data gagal dihapus";

    }


    // Cache Helper
    public function cacheHelper() {

        // Store items to cache
        // cache(['username' => 'admin']);

        // Retrieve item
        // $value = cache('username');
        // echo $value;
        
        // Called function without any arguments
        // cache()->rememberForever('mahasiswa', function () {
        //     return DB::table('mahasiswa')->get();
        // });
        // echo cache('mahasiswa');
        // return response()->json(cache('mahasiswa'));

        // $mahasiswa = DB::table('mahasiswa')->get();
        // return response()->json($mahasiswa);

    }

    public function lockCache() {
        
        // $lock = Cache::lock('foo', 30);

        // if ($lock->get()) {
        //     // Lock acquired for 30 seconds...

        //     echo "Masuk lock";
            
        //     // Cache::put('user', 'lock user', 30);
        //     // echo Cache::get('user');

        //     $lock->release();
        // }
        // else {
        //     echo "Gagal masul lock";
        // }

        // Cache::lock('lock-name', 60)->get();

        // Cache::lock('angka', 30)->block(8, function () {
            // Lock acquired after waiting a maximum of 5 seconds...
        //     echo "Lock enabled";
        // });

    }
}
