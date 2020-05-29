<?php

namespace App\Http\Controllers;

use Auth;
use App\foreignRegistrationMarks;
use App\Http\Requests;
use App\Http\Requests\foreignRegistrationMarksRequest;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;

class foreignRegistrationMarkcController extends Controller
{
    public function index(){
        $foreignRegistrationMarksListings = foreignRegistrationMarks::ORDERBY('foreign_registration_marks', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.gop.foreign-reg-marks.create', compact('foreignRegistrationMarksListings'));
    }

    public function store(foreignRegistrationMarksRequest $request){
        $checkifforeignRegistrationMarksexists = foreignRegistrationMarks::WHERE('foreign_registration_marks', $request->get('foreign_registration_marks'))->exists();
        if($checkifforeignRegistrationMarksexists) {
            return 'exist';
        }
        foreignRegistrationMarks::CREATE($request->all());
        return 'saved';

    }
    public function edit($id){
        $foreignRegistrationMarksListings = foreignRegistrationMarks::ORDERBY('foreign_registration_marks', 'ASC')->PAGINATE(15);
        $recid = foreignRegistrationMarks::findOrFail($id);
        return view('v1.ncaa.gop.foreign-reg-marks.create', compact('foreignRegistrationMarksListings', 'recid'));
    }

    public function update(foreignRegistrationMarksRequest $request, $id){
        $checkifforeignRegistrationMarksexists = foreignRegistrationMarks::WHERE('foreign_registration_marks', $request->get('foreign_registration_marks'))->WHERE('id', '<>', $id)->exists();
        if($checkifforeignRegistrationMarksexists) {
            return 'exist';
        }
        $recid = foreignRegistrationMarks::findOrFail($id);
        $recid->UPDATE($request->all());
        return 'updated';
    }
    public function destroy($id) {
        $recid = foreignRegistrationMarks::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
