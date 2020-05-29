<?php

namespace App\Http\Controllers;

use App\foreignamo;
use App\localamo;
use App\Http\Requests;
use App\Http\Requests\foreignAmoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;

class amocontroller extends Controller
{
    public function viewselection(){
        if(Auth::check() && Auth::user()->role){
            $soontoexpireForeignAmos = DB::SELECT(DB::RAW('SELECT a.*, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id'));

            $soontoexpireLocalAmos = DB::SELECT(DB::RAW('SELECT a.*, b.aoc_holder FROM tbl_ncaa_amo_locals a JOIN tbl_ncaa_acos b ON b.aoc_holder = a.aoc_holder_id'));
    
            return view('v1.ncaa.amo.view-selection', compact('soontoexpireForeignAmos', 'soontoexpireLocalAmos'));
        }
        return redirect()->route('login');
    }
}
