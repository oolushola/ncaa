<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\stateOfRegistry;
use App\Http\Requests;
use App\Http\Requests\stateOfRegistryRequest;
use Illuminate\Http\HttpResponse;

class stateOfRegistryController extends Controller
{
    public function index(){
        $stateOfRegistryListings = stateOfRegistry::ORDERBY('state_of_registry', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.state-of-registry.create', compact('stateOfRegistryListings'));
    }

    public function store(stateOfRegistryRequest $request){
        $checkifstateofregistryexists = stateOfRegistry::WHERE('state_of_registry', $request->get('state_of_registry'))->exists();
        if($checkifstateofregistryexists) {
            return 'exist';
        }
        stateOfRegistry::CREATE($request->all());
        return 'saved';

    }
    public function edit($id){
        $stateOfRegistryListings = stateOfRegistry::ORDERBY('state_of_registry', 'ASC')->PAGINATE(15);
        $recid = stateOfRegistry::findOrFail($id);
        return view('v1.ncaa.gop.state-of-registry.create', compact('stateOfRegistryListings', 'recid'));
    }
    public function update(stateOfRegistryRequest $request, $id){
        $checkifstateofregistryexists = stateOfRegistry::WHERE('state_of_registry', $request->get('state_of_registry'))->WHERE('id', '<>', $id)->exists();
        if($checkifstateofregistryexists) {
            return 'exist';
        }
        $recid = stateOfRegistry::findOrFail($id);
        $recid->UPDATE($request->all());
        return 'updated';
    }
    public function destroy($id) {
        $recid = stateOfRegistry::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
