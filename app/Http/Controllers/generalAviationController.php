<?php

namespace App\Http\Controllers;

use Auth;
use App\generalAviation;
use App\Http\Requests;
use App\Http\Requests\generalAviationRequest;
use Illuminate\Http\HttpResponse;

class generalAviationController extends Controller
{
    public function index(){
        $generalAviationListings = generalAviation::ORDERBY('general_aviation_name', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.general-aviation.create', compact('generalAviationListings'));
    }

    public function store(generalAviationRequest $request){
        $checkifgeneralAviationexists = generalAviation::WHERE('general_aviation_name', $request->get('general_aviation_name'))->exists();
        if($checkifgeneralAviationexists) {
            return 'exist';
        }
        generalAviation::CREATE($request->all());
        return 'saved';

    }
    public function edit($id){
        $generalAviationListings = generalAviation::ORDERBY('general_aviation_name', 'ASC')->PAGINATE(15);
        $recid = generalAviation::findOrFail($id);
        return view('v1.ncaa.gop.general-aviation.create', compact('generalAviationListings', 'recid'));
    }

    public function update(generalAviationRequest $request, $id){
        $checkifgeneralAviationexists = generalAviation::WHERE('general_aviation_name', $request->get('general_aviation_name'))->WHERE('id', '<>', $id)->exists();
        if($checkifgeneralAviationexists) {
            return 'exist';
        }
        $recid = generalAviation::findOrFail($id);
        $recid->UPDATE($request->all());
        return 'updated';
    }
    public function destroy($id) {
        $recid = generalAviation::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
