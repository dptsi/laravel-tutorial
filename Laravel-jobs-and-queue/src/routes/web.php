<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('testingJob',function(){
    dispatch(new App\Jobs\JobSingkat());
});
Route::get('biasa',function(){
    dispatch(new App\Jobs\PekerjaanBiasa());
});
Route::get('error',function(){
    dispatch(new App\Jobs\PekerjaanError());
});
Route::get('lama',function(){
    dispatch(new App\Jobs\PekerjaanLama());
});

Route::get('langsungbiasa',function(){
    dispatchSync(new App\Jobs\PekerjaanBiasa());
});
Route::get('langsungerror',function(){
    dispatchSync(new App\Jobs\PekerjaanError());
});
Route::get('langsunglama',function(){
    dispatchSync(new App\Jobs\PekerjaanLama());
});

Route::get('biasaqueuea',function(){
    dispatch(new App\Jobs\PekerjaanBiasa())->onQueue('Sekunder');
});
Route::get('biasaqueueb',function(){
    dispatch(new App\Jobs\PekerjaanBiasa())->onQueue('Penting');
});


Route::get('allin',function(){
    dispatch(new App\Jobs\PekerjaanBiasa());
    dispatch(new App\Jobs\PekerjaanError());
    dispatch(new App\Jobs\PekerjaanLama());
});

Route::get('rantai',function(){
    Illuminate\Support\Facades\Bus::chain([
        new App\Jobs\Job1(),
        new App\Jobs\Job2(),
        new App\Jobs\Job4(),
    ])->dispatch();
});
Route::get('rantaierror',function(){
    Illuminate\Support\Facades\Bus::chain([
        new App\Jobs\Job1(),
        new App\Jobs\Job2(),
        new App\Jobs\Job3(),
        new App\Jobs\Job4(),
    ])->dispatch();
});























Route::get('batch',function(){
    $batch = Illuminate\Support\Facades\Bus::batch([
        new App\Jobs\Job1(),
        new App\Jobs\Job2(),
        new App\Jobs\Job4(),
    ])->then(function (Illuminate\Bus\Batch $batch) {

        // All jobs completed successfully...
    })->catch(function (Illuminate\Bus\Batch $batch, Throwable $e) {

        // First batch job failure detected...
    })->finally(function (Illuminate\Bus\Batch $batch) {

        // The batch has finished executing...
    })->dispatch();
    echo($batch->id);
});