<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\human;

class HumansController extends Controller
{
    public function index(Request $request)
    {
        $humans = human::all();
        return view('show', compact('humans'));
    }
    
    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
        ]);

        $human = new human;
        $human->nama = $request->nama;
        $human->alamat = $request->alamat;

        $human->save();

        return redirect('/humans')->with('status', 'Human has been added!');
    }
}
