<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aircrafts;
use App\aoc;
use App\foreignamo;
use App\localamo;
use App\focc;
use App\Tac;
use Auth;
use App\aop;
use App\atl;
use App\pncf;
use App\paas;
use App\atol;
use App\ato;
use App\Fcop;
use App\CertificationTracker;
use App\ForeignAirlineDacl;

class loginController extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function dashboard(){
        if(Auth::check() && Auth::user()->role){
            $aircraftslistings = aircrafts::SELECT('id', 'c_of_a_status')->ORDERBY('c_of_a_status')->GET();
            $aoclist = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
            $foreignamolist = foreignamo::ORDERBY('amo_holder', 'ASC')->GET();
            $localamolist = localamo::ORDERBY('aoc_holder_id')->GET();
            $focclist = focc::ORDERBY('aoc_holder_id', 'ASC')->GET();
            $tacList = Tac::GET()->COUNT();
            $aopCount = aop::GET()->COUNT();
            $atlCount = atl::GET()->COUNT();
            $pncfCount = pncf::GET()->COUNT();
            $paasCount = paas::GET()->COUNT();
            $atolCount = atol::GET()->COUNT();
            $atoCount = ato::GET()->COUNT();
            $fcopCount = Fcop::GET()->COUNT();
            $certificationTrackerCount = CertificationTracker::GET()->COUNT();
            $foreignAirlineDaclCount = ForeignAirlineDacl::GET()->COUNT();

            return view('v1.ncaa.dashboard', compact(
                'aircraftslistings', 'aoclist', 'foreignamolist', 'localamolist', 'focclist', 'tacList',
                'aopCount', 'atlCount', 'pncfCount', 'paasCount', 'atolCount', 'atoCount', 'fcopCount', 'certificationTrackerCount',
                'foreignAirlineDaclCount'
                )
            );
        }
        else{
            return redirect()->route('login');
        }
        
    }
    
}
