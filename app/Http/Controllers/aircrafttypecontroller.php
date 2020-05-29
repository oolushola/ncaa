<?php

namespace App\Http\Controllers;

use App\aircrafttype;
use App\aircraftMaker;
use App\Http\Requests;
use App\Http\Requests\aircraftTypeRequest;
use App\Http\Requests\aircraftMakerRequest;
use Illuminate\Http\Request;
use Auth;
use App\aircrafts;

class aircrafttypecontroller extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aircraftmakelists = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
            $aircrafttypelists = aircrafttype::ORDERBY('aircraft_type', 'ASC')->PAGINATE(20);
            return view('v1.ncaa.gop.aircraft-type.create', compact('aircraftmakelists', 'aircrafttypelists'));
        }
        return redirect()->route('login');
    }

    public function store(aircraftTypeRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = aircrafttype::WHERE('aircraft_maker_id', $request->get('aircraft_maker_id'))->WHERE('aircraft_type', $request->get('aircraft_type'))->exists();
            if($check){
                return 'exist';
            }
            aircrafttype::CREATE($request->all());
            return 'saved';
        }
        return redirect()->route('login');
    }

    public function edit($id){
        $aircraftmakelists = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $aircrafttypelists = aircrafttype::ORDERBY('aircraft_type', 'ASC')->PAGINATE(20);
        $recid = aircrafttype::findOrFail($id);
        return view('v1.ncaa.gop.aircraft-type.create', compact('aircraftmakelists', 'aircrafttypelists', 'recid'));
    }

    public function update(aircraftTypeRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = aircrafttype::WHERE('aircraft_maker_id', $request->get('aircraft_maker_id'))->WHERE('aircraft_type', $request->get('aircraft_type'))->WHERE('id', '<>', $id)->exists();
            if($check){
                return 'exist';
            }
            $recid = aircrafttype::findOrFail($id);
            $recid->UPDATE($request->all());
            return 'updated';
        }
        return redirect()->route('login'); 
    }

    public function destroy($name){
        if(Auth::check() && Auth::user()->role){
            $str_arr = explode("-", $name);
            $aircraft_type = $str_arr[0];
            $aircraft_type_id = $str_arr[1];

            $checkAircraftStatus = aircrafts::WHERE('aircraft_type', $aircraft_type)->exists();
            if($checkAircraftStatus)
            {
                return 'cant_delete';
            }
            $recid = aircrafttype::findOrFail($aircraft_type_id);
            $recid->DELETE();
            return 'deleted';

        }
        return redirect()->route('login'); 
    }
}
