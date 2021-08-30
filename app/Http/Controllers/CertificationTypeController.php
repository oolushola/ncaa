<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\CertificationType;
use App\Http\Requests;
use Illuminate\Http\HttpResponse;
use Validator;

class CertificationTypeController extends Controller
{
    public function index(){
        $certificationTypeListings = CertificationType::ORDERBY('certification_type', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.certification-type.create', compact('certificationTypeListings'));
    }

    public function store(Request $request){
        CertificationType::CREATE([
            'certification_type' => $request->certification_type,
        ]);
        return 'saved';
    }
    public function edit($id){
        $certificationTypeListings = CertificationType::ORDERBY('certification_type', 'ASC')->PAGINATE(15);
        $recid = CertificationType::findOrFail($id);
        return view('v1.ncaa.gop.certification-type.create', compact('certificationTypeListings', 'recid'));
    }

    public function update(Request $request, $id){
        $recid = CertificationType::findOrFail($id);
        $recid->certification_type = $request->certification_type;
        $recid->save();
        return 'updated';
    }
    public function destroy($id) {
        $recid = CertificationType::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
