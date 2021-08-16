<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Cpm;
use App\Http\Requests;
use Illuminate\Http\HttpResponse;
use Validator;

class CpmController extends Controller
{
    public function index(){
        $cpmListings = Cpm::ORDERBY('last_name', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.cpm.create', compact('cpmListings'));
    }

    public function store(Request $request){
        Cpm::CREATE([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->title.' '.$request->first_name.' '.$request->last_name,
        ]);
        return 'saved';
    }
    public function edit($id){
        $cpmListings = Cpm::ORDERBY('last_name', 'ASC')->PAGINATE(15);
        $recid = Cpm::findOrFail($id);
        return view('v1.ncaa.gop.cpm.create', compact('cpmListings', 'recid'));
    }

    public function update(Request $request, $id){
        $recid = Cpm::findOrFail($id);
        $recid->title = $request->title;
        $recid->first_name = $request->first_name;
        $recid->last_name = $request->last_name;
        $recid->full_name = $request->title.' '.$request->first_name.' '.$request->last_name;
        $recid->save();
        return 'updated';
    }
    public function destroy($id) {
        $recid = Cpm::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
