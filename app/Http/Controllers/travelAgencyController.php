<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\travelAgency;
use App\Http\Requests;
use App\Http\Requests\stateOfRegistryRequest;
use Illuminate\Http\HttpResponse;

class travelAgencyController extends Controller
{
    public function index() {
        $travelAgencyListings = travelAgency::PAGINATE(15);
        return view('v1.ncaa.gop.travel-agency.create', compact('travelAgencyListings'));
    }

    public function store(Request $request) {
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

    public function edit($id) {
        $recid = travelAgency::findOrFail($id);
        $travelAgencyListings = travelAgency::PAGINATE(15);
        return view('v1.ncaa.gop.travel-agency.create', compact('travelAgencyListings', 'recid'));
    }

    public function update(Request $request, $id) {
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

    public function destroy() {
        
    }
}
