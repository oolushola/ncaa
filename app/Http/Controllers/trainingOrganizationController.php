<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\trainingOrganization;
use App\Http\Requests;
use App\Http\Requests\stateOfRegistryRequest;
use Illuminate\Http\HttpResponse;

class trainingOrganizationController extends Controller
{
    public function index() {
        $trainingOrganizationListings = trainingOrganization::PAGINATE(15);
        return view('v1.ncaa.gop.training-org.create', compact('trainingOrganizationListings'));
    }

    public function store(Request $request) {
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

    public function edit($id) {
        $recid = trainingOrganization::findOrFail($id);
        $trainingOrganizationListings = trainingOrganization::PAGINATE(15);
        return view('v1.ncaa.gop.training-org.create', compact('trainingOrganizationListings', 'recid'));
    }

    public function update(Request $request, $id) {
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

    public function destroy() {
        
    }
}
