<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\trainingOrganization;
use App\Http\Requests;
use App\Http\Requests\stateOfRegistryRequest;
use Illuminate\Http\HttpResponse;
use App\ato;

class trainingOrganizationController extends Controller
{
    public function index() {
        if(Auth::check() && Auth::user()->role) {
            $trainingOrganizationListings = trainingOrganization::PAGINATE(15);
            return view('v1.ncaa.gop.training-org.create', compact('trainingOrganizationListings'));
        }
        return redirect()->route('login');
    }

    public function store(Request $request) {
        if(Auth::check() && Auth::user()->role) {
            $this->validate($request, [
                'training_organization' => 'required|string',
            ]);

            $check = trainingOrganization::WHERE('training_organization', $request->training_organization)->exists();
            if($check) {
                return 'exists';
            }
            else {
                trainingOrganization::CREATE($request->all());
                return 'saved';
            }
        }
        return redirect()->route('login');
    }

    public function edit($id) {
        if(Auth::check() && Auth::user()->role) {
            $recid = trainingOrganization::findOrFail($id);
            $trainingOrganizationListings = trainingOrganization::PAGINATE(15);
            return view('v1.ncaa.gop.training-org.create', compact('trainingOrganizationListings', 'recid'));
        }
        return redirect()->route('login');
    }

    public function update(Request $request, $id) {
        if(Auth::check() && Auth::user()->role) {
            $this->validate($request, [
                'training_organization' => 'required|string',
            ]);

            $check = trainingOrganization::WHERE('training_organization', $request->training_organization)->WHERE('id', '<>', $id)->exists();
            if($check) {
                return 'exists';
            }
            else {
                $recid = trainingOrganization::findOrFail($id);
                $recid->UPDATE($request->all());
                return 'updated';
            }
        }
        return redirect()->route('login');
    }

    public function destroy(Request $request, $id) {
        if(Auth::check() && Auth::user()->role) {
            $checker = ato::WHERE('training_organization_id', $id)->GET()->FIRST();
            if($checker) {
                return 'cant_delete';
            }
            else{
                $recid = trainingOrganization::findOrFail($id);
                $recid->DELETE();
                return 'deleted';
            }
        }
        return redirect()->route('login');
    }
}
