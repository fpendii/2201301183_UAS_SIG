<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\PomMiniModel;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(){
        $pomMini = PomMiniModel::all();

        return view('pengguna/landing_page',compact('pomMini'));
    }

    
}
