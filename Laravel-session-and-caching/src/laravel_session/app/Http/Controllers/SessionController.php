<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SessionController extends Controller
{
     // STORE : PUT METHOD
     public function store(Request $request){
        // REQUEST INSTANCE
        // $request->session()->put('nama', ['vania']);
        // $request->session()->put('alamat', ['pasuruan']);

        // $request->session()->put('nama', 'vania');
        // $request->session()->put('alamat', 'pasuruan');

        // GLOBAL HELPER
        $nama = Session (['nama' => ['vania']]);
        $alamat = Session (['alamat' => ['pasuruan']]);

        // $nama = Session (['nama' => 'vania']);
        // $alamat = Session (['alamat' => 'pasuruan']);

        echo "Data telah disimpan";
    }

    // PUSH 
    public function push(Request $request){
        $request->session()->push('nama', 'nada');
        $request->session()->push('alamat', 'banjarmasin');

        echo "Data telah dipush";
    }

    // RETRIEVE : GET
    public function show(Request $request){
        // REQUEST INSTANCE
        $name = $request->session()->get('nama');
        $address = $request->session()->get('alamat');

        // GLOBAL HELPER
        // $name = session ('nama');
        // $address = session ('alamat');

        print_r($name);
        print_r($address);
    }

    // ALL 
    public function all(Request $request){
        $data = $request->session()->all();
        print_r($data);
    }

    // REMOVE ALL DATA : FLUSH
    public function flush(Request $request){
        // menghapus semua data
        $request->session()->flush();
        echo "Data telah dihapus semua";
    }

    // REMOVE DATA : DELETE
    public function delete(Request $request){
        // menghapus key nama
        $request->session()->forget('nama');
        // $request->session()->forget(['nama', 'alamat']);

        echo "Data telah dihapus";
    }

    // HAS & MISSING
    public function hasMis(Request $request){
        // if($request->session()->has('nama')){
        //     print_r($request->session()->get('nama'));
        // }

        // elseif ($request->session()->missing('nama')){
        //     echo "Data/Item tidak ada";
        // }

        if($request->session()->has('alamat')){
            print_r($request->session()->get('alamat'));
        }

        elseif ($request->session()->missing('alamat')){
            echo "Data/Item tidak ada";
        }
    }

    // PULL : retrieve data and remove
    public function pull(Request $request){
        $value = $request->session()->pull('alamat');
        print_r($value);
    }

    // FLASH [pesan]
    public function index(Request $request)
    {
        return view('index',
            ['title'=>'Flash Message']
        );
    }
    
    public function flash(){
        // return redirect('/index')->with(['success' => 'Ini pesan Berhasil~']);
        // return redirect('/index')->with(['error' => 'Ini pesan error!']);
        return redirect('/index')->with(['warning' => 'Ini Pesan warning !!!']);
    }

    // INCREMENT
    public function increment(Request $request){
        // print_r($request->session()->increment('count'));
        print_r($request->session()->increment('count', $incrementBy = 2));
    }

    // ]DECREMENT
    public function decrement(Request $request){
        // print_r($request->session()->decrement('count'));
        print_r($request->session()->decrement('count', $decrementBy = 3));
    }
}
