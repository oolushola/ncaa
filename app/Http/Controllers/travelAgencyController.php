<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\travelAgency;
use App\Http\Requests;
use App\Http\Requests\stateOfRegistryRequest;
use Illuminate\Http\HttpResponse;
use App\atol;

class travelAgencyController extends Controller
{
    public function index() {
        if(Auth::check() && Auth::user()->role) {
            $travelAgencyListings = travelAgency::PAGINATE(15);
            return view('v1.ncaa.gop.travel-agency.create', compact('travelAgencyListings'));
        }
        return redirect()->route('login');
    }

    public function store(Request $request) {
        if(Auth::check() && Auth::user()->role) {
            $this->validate($request, [
                'travel_agency_name' => 'required|string',
            ]);

            $check = travelAgency::WHERE('travel_agency_name', $request->travel_agency_name)->exists();
            if($check) {
                return 'exists';
            }
            else {
                travelAgency::CREATE($request->all());
                return 'saved';
            }
        }
        return redirect()->route('login');
    }

    public function edit($id) {
        if(Auth::check() && Auth::user()->role) {
            $recid = travelAgency::findOrFail($id);
            $travelAgencyListings = travelAgency::PAGINATE(15);
            return view('v1.ncaa.gop.travel-agency.create', compact('travelAgencyListings', 'recid'));
        }
        return redirect()->route('login');
    }

    public function update(Request $request, $id) {
        if(Auth::check() && Auth::user()->role) {
            $this->validate($request, [
                'travel_agency_name' => 'required|string',
            ]);

            $check = travelAgency::WHERE('travel_agency_name', $request->travel_agency_name)->WHERE('id', '<>', $id)->exists();
            if($check) {
                return 'exists';
            }
            else {
                $recid = travelAgency::findOrFail($id);
                $recid->UPDATE($request->all());
                return 'updated';
            }
        }
        return redirect()->route('login');
    }

    public function destroy(Request $request, $id) {
        if(Auth::check() && Auth::user()->role) {
            $nameofTravelAgency = travelAgency::findOrFail($id);
            $checker = atol::WHERE('operator_type', $nameofTravelAgency->travel_agency_name)->GET()->FIRST();
            if($checker) {
                return 'cant_delete';
            }
            else{
                $nameofTravelAgency->DELETE();
                return 'deleted';
            }
        }
        return redirect()->route('login');
    }
}
