<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public static int $counter = 0;
    public function input()
    {
        return view('formulir');
    }

    public function proses(Request $request, int $id)
    {
        $message = "</br>Path = " . $request->path();

        $message .= "</br>request patern == proses* ? " . ($request->is("proses*") ? 'true' : 'false');
        $message .= "</br>request route name ==  proses-form-guest? " . ($request->routeIs("proses-form-guest") ? 'true' : 'false');

        $message .= "</br>url = " . $request->url();
        $message .= "</br>full url = " . $request->fullUrl();

        $message .= "</br>Query string warna = " . $request->query('warna');

        $message .= "</br>Method = " . $request->method();
        $message .= "</br>Method == post? " . ($request->isMethod('post') ? 'true' : 'false');


        $message .= "</br>Name = " . $request->input('name');
        $message .= "</br>City = " . $request->input('city');

        $message .= "</br>Hobby:";
        for ($i = 0; $i < count($request->input('hobby')); $i++) {
            $message .= '</br>' . $request->input("hobby.$i");
        }

        return $message;
    }
}
