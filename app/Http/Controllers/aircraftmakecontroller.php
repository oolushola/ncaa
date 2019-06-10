<?php

namespace App\Http\Controllers;
use App\aircraftMaker;
use App\Http\Requests;
use App\Http\Requests\aircraftMakerRequest;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;

class aircraftmakecontroller extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aircraftMakerLists = aircraftMaker::ORDERBY('created_at')->GET();
            return view('v1.ncaa.gop.create', compact('aircraftMakerLists'));    
        }
        else{
            return redirect()->route('login');
        }
    }
    public function store(aircraftMakerRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = aircraftMaker::WHERE('aircraft_maker', $request->get('aircraft_maker'))->exists();
    		if($check){
    			return 'exists';
    		}
    		else{
    			$blogpost = aircraftMaker::CREATE($request->all());
                return "save";
    		}
        }
        else{
            return redirect()->route('login');
        }
    }
    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = aircraftMaker::findOrFail(base64_decode($id));
            $aircraftMakerLists = aircraftMaker::ORDERBY('created_at')->GET();
            return view('v1.ncaa.gop.edit', compact('aircraftMakerLists','recid'));
        }
        return redirect()->route('login');
    }
    public function update(aircraftMakerRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = aircraftMaker::WHERE('aircraft_maker', $request->get('aircraft_maker'))->WHERE('id', '!=', $id)->exists();
            if ($check>0){
                return "exists";
            }else{
                $aircraft_maker = aircraftMaker::findOrFail($id);
                $aircraft_maker->update($request->all());
                return "updated";
            }
        }
        else{
            return redirect()->route('login');
        }
    }
}
