<?php

namespace App\Http\Controllers;

use Auth;
use App\foreignAirline;
use App\Http\Requests;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Validator;

class ForeignAirlineController extends Controller
{
    public function index(){
        $foreignAirlineListings = foreignAirline::ORDERBY('foreign_airline', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.foreign-airline.create', compact('foreignAirlineListings'));
    }

    public function store(Request $request){
        $checkifforeignAirlineexists = foreignAirline::WHERE('foreign_airline', $request->get('foreign_airline'))->exists();
        if($checkifforeignAirlineexists) {
            return 'exist';
        }
        foreignAirline::CREATE($request->all());
        return 'saved';
    }
    public function edit($id){
        $foreignAirlineListings = foreignAirline::ORDERBY('foreign_airline', 'ASC')->PAGINATE(15);
        $recid = foreignAirline::findOrFail($id);
        return view('v1.ncaa.gop.foreign-airline.create', compact('foreignAirlineListings', 'recid'));
    }

    public function update(Request $request, $id){
        $checkifforeignAirlineexists = foreignAirline::WHERE('foreign_airline', $request->get('foreign_airline'))->WHERE('id', '<>', $id)->exists();
        if($checkifforeignAirlineexists) {
            return 'exist';
        }
        $recid = foreignAirline::findOrFail($id);
        $recid->UPDATE($request->all());
        return 'updated';
    }
    public function destroy($id) {
        $recid = foreignAirline::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}