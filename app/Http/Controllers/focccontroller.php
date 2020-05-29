<?php

namespace App\Http\Controllers;
use App\aoc;
use App\generalAviation;
use App\aircrafts;
use App\focc;
use App\Http\Requests;
use App\Http\Requests\foccRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\stateOfRegistry;
use App\aircraftMaker;
use App\foreignRegistrationMarks;
use App\aircrafttype;

class focccontroller extends Controller
{
    public function getaircrafttypebymaker(Request $request){
        if(Auth::check() && Auth::user()->role){
            $aircraft_maker_id = $request->get('aircraft_maker_id');
            $aircrafts = '<select class="form-control" name="aircraft_type_id" id="aircraft_type_id">
                <option value="0">Choose Aircraft Type *</option>';

            $aircraft_types = aircrafttype::SELECT('id', 'aircraft_type')->WHERE('aircraft_maker_id', $aircraft_maker_id)->ORDERBY('aircraft_type', 'ASC')->GET();

            foreach($aircraft_types as $aircraft){
                $aircrafts.= '<option value='.$aircraft->id.'>'.$aircraft->aircraft_type.'</option>';
            }
            
            $aircrafts.='</select>';

            return $aircrafts;
        }
        return redirect()->route('login');
    }

    public function getaircraftregnumber(Request $request){
        if(Auth::check() && Auth::user()->role){
            $getRegistration = aircrafts::SELECT('registration_marks')->WHERE('id', $request->get('aircraft_type'))->GET();
            return $getRegistration[0];
        }
        return redirect()->route('login');
    }

    public function index(){
        if(Auth::check() && Auth::user()->role){
            $focclists = focc::SELECT(
                'id', 
                'focc_no', 
                'date_of_first_issue', 
                'renewal', 'created_by')->ORDERBY('date_of_first_issue', 'ASC')->PAGINATE(15);
            $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
            $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
            $stateofregistries = stateOfRegistry::SELECT('id', 'state_of_registry')->ORDERBY('state_of_registry', 'ASC')->GET();
            $aircraftmakers = aircraftMaker::SELECT('id', 'aircraft_maker')->ORDERBY('aircraft_maker', 'ASC')->GET();
            $foreignregmarks = foreignRegistrationMarks::SELECT('id', 'foreign_registration_marks')->ORDERBY('foreign_registration_marks', 'ASC')->GET();

            return view(
                'v1.ncaa.focc.create', 
                compact(
                    'aoclists', 
                    'focclists', 
                    'generalaviations', 
                    'stateofregistries', 
                    'aircraftmakers',
                    'foreignregmarks'
                )
            );
        }
        return redirect()->route('login');
    }

    public function store(foccRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = focc::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('focc_no', $request->get('focc_no'))->WHERE('aircraft_reg_no', $request->get('aircraft_reg_no'))->WHERE('aircraft_type', $request->get('aircraft_type'))->exists();
                if($check){
                    return 'exists';
                }
                else{
                    focc::CREATE($request->all());
                    return 'saved';
                }
        }
        return redirect()->route('login');
    }

    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = focc::findOrFail(base64_decode($id));
            $aoc_holder_id = $recid->aoc_holder_id;
            $focclists = focc::SELECT('id', 'focc_no', 'date_of_first_issue', 'renewal', 'created_by')->ORDERBY('date_of_first_issue', 'ASC')->PAGINATE(15);
            $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
            $aircrafts = aircrafts::SELECT('id', 'aircraft_type')->WHERE('aoc_holder_id', $aoc_holder_id)->ORDERBY('aircraft_type', 'ASC')->GET();
            return view('v1.ncaa.focc.edit', compact('aoclists', 'focclists', 'recid', 'aircrafts'));
        }
        return redirect()->route('login');
    }

    public function update(foccRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = focc::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('focc_no', $request->get('focc_no'))->WHERE('aircraft_reg_no', $request->get('aircraft_reg_no'))->WHERE('aircraft_type', $request->get('aircraft_type'))->WHERE('id', '<>', $id)->exists();
                if($check){
                    return 'exists';
                }
                else{
                    $recid = focc::findOrFail($id);
                    $recid->UPDATE($request->all());
                    updateHistory::CREATE($request->all());
                    return 'updated';
                }
        }
        return redirect()->route('login');

    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $allfoccs = DB::SELECT(DB::RAW('SELECT a.aoc_holder, c.*, b.aircraft_type FROM tbl_ncaa_acos a JOIN tbl_ncaa_aircrafts b JOIN tbl_ncaa_foccs c ON a.id = c.aoc_holder_id AND b.id = c.aircraft_type'));
            $checkforfoccupdates = updateHistory::WHERE('module', 'focc')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();
            return view('v1.ncaa.focc.show', compact('allfoccs', 'checkforfoccupdates'));
        }
        return redirect()->route('login');
    }

    public function destroy($id){
        if(Auth::check() && Auth::user()->role){
            $recid = focc::findOrFail($id);
            $recid->DELETE();
            return 'deleted';
        }
        return redirect()->route('login');
    }
}
