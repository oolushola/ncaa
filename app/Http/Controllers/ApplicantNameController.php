<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\ApplicantName;
use App\Http\Requests;
use Illuminate\Http\HttpResponse;
use Validator;

class ApplicantNameController extends Controller
{
    public function index(){
        $applicantNameListings = ApplicantName::ORDERBY('applicant_name', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.applicant-name.create', compact('applicantNameListings'));
    }

    public function store(Request $request){
        ApplicantName::CREATE([
            'applicant_name' => $request->applicant_name,
        ]);
        return 'saved';
    }
    public function edit($id){
        $applicantNameListings = ApplicantName::ORDERBY('applicant_name', 'ASC')->PAGINATE(15);
        $recid = ApplicantName::findOrFail($id);
        return view('v1.ncaa.gop.applicant-name.create', compact('applicantNameListings', 'recid'));
    }

    public function update(Request $request, $id){
        $recid = ApplicantName::findOrFail($id);
        $recid->applicant_name = $request->applicant_name;
        $recid->save();
        return 'updated';
    }
    public function destroy($id) {
        $recid = ApplicantName::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
