<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarProductionDate;
use App\Models\Headquarter;
use App\Models\Engine;
use App\Models\Product;


use Illuminate\Support\Facades\DB;


class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // select * from table cars
        // $cars = Car::all();
        // dd($cars);

        // $cars = Car::all()->toArray();
        // $cars = Car::all()->toJson();
        // $cars = json_decode($cars);

        

        // var_dump($cars);
        
        
        $cars = DB::table('cars')->get();
        // dd($cars);
        

        // $cars = DB::table('cars')->pluck('name');


        // $cars = DB::table('cars')->orderBy('id')->chunk(2, function ($cars) {
        //     foreach ($cars as $car) {
        //         print_r($car);
        //     }
        // });


        // $cars = DB::table('cars')->count();
        // $cars = DB::table('cars')->max('founded');

        // if (DB::table('cars')->where('id', 1)->exists()) {
        //     return view('index' , ['cars' => 1 ]);;
        // }

        // $cars = DB::table('cars')
        // ->select('name', 'founded')
        // ->get();

        // $cars = DB::table('cars')->distinct()
        //                 ->select('name')
        //                 ->get();

        // $query = DB::table('cars')->select('name');
        // $cars = $query->addSelect('founded')->get();
        

        // RAW
        // $cars = DB::table('cars')
        //      ->select(DB::raw('count(*) as founded_count, founded'))
        //      ->where('founded', '<', 1999)
        //      ->groupBy('founded')
        //      ->get();
        
        // $cars = DB::select(DB::raw('select * from cars'));
        // $cars = DB::select(DB::raw('select * from cars'))->get();
        // $cars = DB::select(DB::raw('SELECT * FROM cars WHERE founded = .9999'));
        
        // $cars = DB::table('cars')
        //         ->selectRaw('((founded - ?) * (-1)) as berdiri_selama', [2021])
        //         ->get();

        // $cars = DB::table('cars')
        // ->selectRaw('((founded - ?) * (-1)) as berdiri_selama', [2021])
        // ->get();

        // stackoverflow
        // $cars = DB::table('cars')
        //     ->selectRaw('COUNT(*) AS result')
        //     ->get();


            // Returns a collection of PHP objects,
            // You can call collections method fluently on the result
            // It is cleaner.

        // $cars = DB::select(DB::raw("SELECT COUNT(*) AS result FROM cars"));

            // Returns an array of Php object

        // $cars = DB::table('cars')
        //         ->whereRaw('founded > IF(state = "TX", ?, 100)', [200])
        //         ->get();
        
        // $cars = DB::table('cars')
        //         ->whereRaw('id%2 <> 0 ')
        //         ->get();
        
        
        // $cars = DB::table("cars")
        //         ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d %H:%i:%s'))"),'2021-05-28 15:07:35')
        //         ->get();

        // $cars = DB::table("cars")
        //         ->whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d %H:%i:%s') = '2021-05-28 15:07:35'")
        //         ->get();


        // $cars = DB::table('cars')
        //         ->select('name', DB::raw('SUM(founded as lama_tahun'))
        //         ->groupBy('name')
        //         ->havingRaw('SUM(founded > ?', [2500])
        //         ->get();


        // $cars = DB::table('cars')
        //         // ->orderByRaw('updated_at - created_at DESC')
        //         ->orderByRaw('name asc')
        //         ->get();

        // $cars = DB::table('cars')
        //         ->select('name', 'founded')
        //         ->groupByRaw('name, founded')
        //         ->get();

        // $cars = DB::table('cars')
        //         ->select(DB::raw('founded , COUNT(id) as JUMLAH'))
        //         ->groupBy(DB::raw('founded'))
        //         ->get();

        // JOIN
        
        // $cars = DB::table('cars')
        //     ->join('car_models', 'cars.id', '=', 'car_models.id')
        //     ->join('car_production_dates', 'car_models.id', '=', 'car_production_dates.id')
        //     ->select('car_models.model_name', 'car_production_dates.created_at')
        //     ->get();

        // $cars = DB::table('car_models')
        //     ->leftJoin('engines', 'car_models.id', '=', 'engines.id')
        // ->get();

        // $cars = DB::table('car_models')
        //     ->rightJoin('engines', 'car_models.id', '=', 'engines.id')
        // ->get();

        // $cars = DB::table('car_models')
        //     ->crossJoin('engines')
        //     ->get();

        // $cars = DB::table('car_models')
        //             ->join('engines', function ($join) {
        //                 $join->on('car_models.id', '=', 'engines.id')
        //                      ->where('engines.id', '<', 3);
        //             })
        //         ->get();


        // $first = DB::table('car_models')
        //         ->whereNotNull('model_name');
        // dd($first);

        // $cars = DB::table('engines')
        //         ->whereNotNull('id')
        //         ->union($first)
        //         ->select('car_models.model_name', 'engines.created_at')
        //         ->get();

        // dd($cars);

        // $cars = DB::table('cars')
        //     ->orderBy('name', 'desc')
        //     // ->orderBy('name', 'asc')
        //     ->get();
  
        // $cars = DB::table('cars')
        //     ->latest()
        //     ->latest('id')
        //     ->first();

        // $cars = DB::table('cars')->latest()->get();

        // $cars = Car::oldest()->get();

        // $cars = DB::table('cars')
        //         ->inRandomOrder()
        //         ->first();

        // $query = DB::table('cars')->orderBy('name');
        // $cars = $query->reorder()->get();
        


        // 'strict' => false database.php
        // $cars = DB::table('cars')
        //         ->groupBy('founded')
        //         ->having('founded', '>', 2000)
        //         ->get();

        // $cars = DB::table('cars')->skip(4)->take(1)->get();


        // $cars = DB::table('cars')
        // ->groupBy('name', 'founded')
        // ->having('id', '>', 3)
        // ->get();

        // $cars = DB::table('cars')
        //     ->join('contacts', 'cars.id', '=', 'contacts.user_id')
        //     ->join('orders', 'cars.id', '=', 'orders.user_id')
        //     ->select('cars.*', 'contacts.phone', 'orders.price')
        //     ->get();



        // $cars = DB::table('cars')
        //         ->where('id', '<', 3)
        //         ->where('founded', '<', 2000)
        //         ->get();

        // $cars = DB::table('cars')->where('id', 3)->get();

        // $cars = DB::table('cars')
        //         ->where('founded', '>=', 2000)
        //         ->get();

        // $cars = DB::table('cars')
        //                 ->where('founded', '<>', 2000)
        //                 ->get();

        // t kecil ttp terbaca
        // $cars = DB::table('cars')
        //                 ->where('name', 'like', 'T%')
        //                 ->get();

        // $cars = DB::table('cars')->where([
        //     ['id', '=', '1'],
        //     ['founded', '<>', '2000'],
        // ])->get();

        // $cars = DB::table('cars')
        // ->where('id', '>', 4)
        // ->orWhere('name', 'audi')
        // ->get();

        // $cars = DB::table('cars')
        //     ->where('id', '<', 3)
        //     ->orWhere(function($query) {
        //         $query->where('name', 'tyotaa');
        //     })
        //     ->get();

                // $cars = DB::table('cars')
                //         ->where('preferences->dining->meal', 'salad')
                //         ->get();

                // $cars = DB::table('cars')
                //         ->whereJsonContains('options->languages', 'en')
                //         ->get();

                // $cars = DB::table('cars')
                // ->whereJsonContains('options->languages', ['en', 'de'])
                // ->get();

                // $cars = DB::table('cars')
                //         ->whereJsonLength('options->languages', 0)
                //         ->get();

                // $cars = DB::table('cars')
                //         ->whereJsonLength('options->languages', '>', 1)
                //         ->get();

        // $cars = DB::table('cars')
        //         ->whereBetween('founded', [1800, 1900])
        //         ->get();       
                
        // $cars = DB::table('cars')
        //     ->whereNotBetween('founded', [1800, 1900])
        //     ->get();

        // $cars = DB::table('cars')
        //         ->whereIn('id', [1, 2, 3])
        //         ->whereIn('id', [1, 2, 3, 99])
        //         ->get();

        // $cars = DB::table('cars')
        //     ->whereNotIn('id', [1, 2, 3])
        //     ->get();
                
        // $cars = DB::table('cars')
        //         ->whereNull('updated_at')
        //         ->get();

        // $cars = DB::table('cars')
        //         ->whereNotNull('updated_at')
        //         ->get();
        
        // $cars = DB::table('cars')
        //         ->whereDate('created_at', '2021-05-28')                 
        //         ->get();

        // $cars = DB::table('cars')
        //             ->whereMonth('created_at', '5')
        //             ->get();

        // $cars = DB::table('cars')
        //         ->whereDay('created_at', '28')
        //         ->get();

        // $cars = DB::table('cars')
        //             ->whereYear('created_at', '2021')
        //             ->get();

        // $cars = DB::table('cars')
        //         ->whereTime('created_at', '=', '15:07:35')
        //         ->get();

        // $cars = DB::table('cars')
        //             ->whereColumn('name', 'founded')
        //             ->get();

        // $cars = DB::table('cars')
        //         ->whereColumn('updated_at', '>', 'created_at')
        //         ->get();

        // $cars = DB::table('cars')
        // ->whereColumn([
            // ['name', '=', 'founded'],
            // ['updated_at', '>', 'created_at'],
        // ])->get();


        // $cars =  ();

        
            // $cars = DB::table('cars')
            // ->whereExists(function ($query) {
            //     $query->select(DB::raw(1))
            //             ->from('tabe; lain')
            //             ->whereColumn('cars.id', 'cars.founded');
            // })
            // ->get();


            // $users = User::where(function ($query) {
            //     $query->select('type')
            //         ->from('membership')
            //         ->whereColumn('membership.user_id', 'users.id')
            //         ->orderByDesc('membership.start_date')
            //         ->limit(1);
            // }, 'Pro')->get();    

            // $incomes = Income::where('amount', '<', function ($query) {
            //     $query->selectRaw('avg(i.amount)')->from('incomes as i');
            // })->get();


        // first of fail -> if abort404
        // $cars = Car::where('name', '=', 'mercedes')->firstOrFail();

        // $cars = Car::find(10);

        // $cars = Car::where('name','mercedes')->get();;

        
        
        return view('cars.index' , ['cars' => $cars ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('cars.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd('ok');




        // queries
        // DB::table('cars')->insert([
        //     'name' => 'szuki',
        //     'founded' => 2203
        // ]);
        
        // DB::table('cars')->insert([
        //     ['id' => 10,'name' => 'szuki', 'founded' => 2201, 'description' => 'desc'],
        //     ['id' => 11,'name' => 'szukii', 'founded' => 2202, 'description' => 'desc'],
        // ]);

        // DB::table('cars')->insertOrIgnore([
        //     // ke 9 masuk
        //     ['id' => 9,'name' => 'szuki', 'founded' => 2201, 'description' => 'desc'],
        //     // ke 11 engga
        //     ['id' => 11,'name' => 'tyotaa', 'founded' => 2202, 'description' => 'desc'],
        // ]);

        // $id = DB::table('cars')->insertGetId(
        //     ['name' => 'guling', 'founded' => 1998]
        // );

        // DB::table('cars')->upsert([
        //     ['name' => 'supra', 'founded' => 9998, 'description' => 'Desc'],
        //     ['name' => 'supraa', 'founded' => 9999, 'description' => 'Desc']
        // ], ['name', 'founded'], ['description']);

        // DB::table('cars')
        //     ->updateOrInsert(
        //         ['name' => 'supra'],
        //         ['founded' => 1799, 'description' => 'desc for supraaaa']
        // );

        // $affected = DB::table('cars')
        //               ->where('id', 1)
        //               ->update(['options->enabled' => true]);
        

        // DB::table('cars')->increment('name','4');

        

                      
        // eloquent
            // $car = New Car;
            // $car->name = $request->input('name');
            // $car->founded = $request->input('founded');
            // $car->description = $request->input('description');
            // $car->save();

            // make fillable di model
            // $car = Car::create([
            //     'name' => $request->input('name'),
            //     'founded' => $request->input('founded'),
            //     'description' => $request->input('description'),
            // ]);

            return redirect('/cars');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd($id);        
        $car = Car::find($id);
        // dd($car->headquarter);     
        
        // $car = CarProductionDate::find($id);
        // dd($car->carmodel);     
        
        // $car = CarModel::find($id);
        // dd($car->carProductionDate);     
         
        // dd($car->product);
 
        // $products = Product::find($id);
        // dd($products);


        // model one to one
        
        // manggil headquarter dari model car
        // $hq = $car->headquarter; 
        // dd($hq);

        // manggil dari model use App\Models\Headquarter;
        // $hq = Headquarter::find($id);
        // dd($hq->car);
        
        // model one to many
        // manggil carmodel dari model car
        // $cm = $car->carModel;
        // dd($cm);
        
        // manggil car dari model use App\Models\Carmodel;
        // $cm = cm::find($id);
        // dd($cm);

        // model hasManythrough
        
        // manggil dari model engine;
        // $engine = $car->engine;
        // dd($engine);

        // manggil dari model use App\Models\Headquarter;
        // $engine = Engine::find($id);
        // dd($engine);


        // model hasOnethrough
        
        // manggil dari model engine;
        // $pDate = $car->productionDate;
        // dd($pDate);
        

        return view('cars.show' , ['car' => $car ]);
        // return view('cars.show')->with('car',$car);
    }

    // public function show($id, $ model_car ){
        // $engine = Engine::find($id); sing model car  e
        // dd($engine);


        // return view('cars.show' , ['car' => $car ]);
        
    // }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::find($id);
        // dd($car);
        // dd($id);
        return view('cars.edit' , ['car' => $car ]);
        // return view ('cars.edit')->with('car',$car);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //queries
        // $affected = DB::table('users')
        //       ->where('id', 1)
        //       ->update(['votes' => 1]);


        // DB::table('cars')
        //     ->updateOrInsert(
        //         ['name' => 'bugeti'],
        //         ['founded' => 1890],
        //         ['description' => 'desc']
        // );

        // Eloquent

        $car = Car::where('id',$id)
        ->update([
            'name' => $request->input('name'),
            'founded' => $request->input('founded'),
            'description' => $request->input('description'),
        ]);

        return redirect('/cars');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // DB::table('cars')->delete();
        // DB::table('users')->truncate();

        // DB::table('cars')->where('founded', '>', 3000)->delete();

        // dd($id);
        $car = Car::find($id);
        // dd($car);
        $car->delete();
        return redirect('/cars');

    }
}
