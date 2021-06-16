<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aircrafttype;
use App\aircraftMaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use App\Tac;
use App\TacAircraftMaker;
use App\updateHistory;
use Auth;

class tacController extends Controller
{
    public function index() {
        $aircrafts = aircraftMaker::ALL();
        $tacs = Tac::GET();
        return view('v1.ncaa.tac.create', array(
            'aircraftMaker' => $aircrafts,
            'tacs' => $tacs
        ));
    }

    public function aircraftModelsListing(Request $request) {
        $aircraft_maker_id = $request->aircraft_maker_id;
        $getAircraftTypes = aircrafttype::WHERE('aircraft_maker_id', $aircraft_maker_id)->ORDERBY('aircraft_type', 'ASC')->GET();

        $response = '<label class="labelholder">Choose Aircraft Model</label>';

        $response.='<div class="row" style="max-height:200px; overflow:auto; font-size:11px;">';

        foreach($getAircraftTypes as $aircraftTypes) {
            $response.='<div class="col-md-6" style="margin-bottom:10px;">
                            <input type="checkbox" value="'.$aircraftTypes->id.'" name="aircraftTypes[]">
                            '.$aircraftTypes->aircraft_type.' 
                </div>';
        }

        $response.='</div>';

        return $response;
    }

    public function store(Request $request) {
        $check = Tac::WHERE('tc_acceptance_approval', $request->tc_acceptance_approval)->exists();
        if($check){
            return 'exists';
        }
        else{
            $tacRecord = Tac::CREATE($request->all());
            $id = $tacRecord->id;
            if(count($request->aircraftTypes)){
                foreach($request->aircraftTypes as $aircraft_type_id){
                    $record = TacAircraftMaker::firstOrNew(['aircraft_type_id' => $aircraft_type_id, 'tac_id' => $id, 'aircraft_maker_id' => $tacRecord->aircraft_maker_id]);
                    $record->save();
                }
            }

            $recid = Tac::findOrFail($id);
            if($request->hasFile('file')){
                $certificate_no = $request->file('file');
                $name = str_slug($request->get('tc_acceptance_approval')).'.'.$certificate_no->getClientOriginalExtension();
                $destination_path = public_path('confidentials/tac/');
                $tac_certificate_no = $destination_path."/".$name;
                $certificate_no->move($destination_path, $name);
                $recid->certificate_no = $name;
                $recid->save();
            }
            return 'saved';
        }
        
    }

    public function edit($id) {
        $aircrafts = aircraftMaker::ALL();
        $recid = Tac::findOrFail($id);
        
        $aircraftTypes = aircrafttype::WHERE('aircraft_maker_id', $recid->aircraft_maker_id)->GET();

        $selectedAircraftMaker = TacAircraftMaker::WHERE('aircraft_maker_id', $recid->aircraft_maker_id)->GET();


        $tacs = Tac::GET();
        return view('v1.ncaa.tac.create', array(
            'aircraftMaker' => $aircrafts,
            'recid' => $recid,
            'tacs' => $tacs,
            'aircraftTypeLists' => $aircraftTypes,
            'selectedAircraftMakers' => $selectedAircraftMaker
        ));
    }

    public function update(Request $request, $id) {
        
        $check = Tac::WHERE('tc_acceptance_approval', $request->tc_acceptance_approval)->WHERE('id', '!=', $id)->exists();
        if($check){
            return 'exists';
        }
        else{
            $recid = Tac::findOrFail($id);
            $tacRecord = $recid->UPDATE($request->all());
            
            if(count($request->aircraftTypes)){
                foreach($request->aircraftTypes as $aircraft_type_id){
                    $record = TacAircraftMaker::firstOrNew(['aircraft_type_id' => $aircraft_type_id, 'tac_id' => $id, 'aircraft_maker_id' => $recid->aircraft_maker_id]);
                    $record->save();
                }
            }

            if($request->hasFile('file')){
                $certificate_no = $request->file('file');
                $name = str_slug($request->get('tc_acceptance_approval')).'.'.$certificate_no->getClientOriginalExtension();
                $destination_path = public_path('confidentials/tac/');
                $tac_certificate_no = $destination_path."/".$name;
                $certificate_no->move($destination_path, $name);
                $recid->certificate_no = $name;
                $recid->save();
            }
            $update = updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'tac', 'record_id' => $id, 'actual' => $recid->tc_acceptance_approval
            ]);
            return 'saved';
        }
    }

    public function destroy($id) {
        $recid = Tac::findOrFail($id);
        $recid->delete();

        DB::DELETE(
            DB::RAW(
                'DELETE FROM tbl_ncaa_tac_aircraft_makers WHERE tac_id = "'.$id.'" '
            )
        );
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'tac', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->tc_acceptance_approval
        ]);

        return 'deleted';
    }

    public function showAllTac() {
        $aircraftMakers = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $allTacs = DB::SELECT(
            DB::RAW(
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id '
            )
        );

        $aircraftModels = DB::SELECT(
            DB::RAW(
                'SELECT * from tbl_ncaa_tac_aircraft_makers a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id'
            )
        );
        $checkforaocupdates = updateHistory::WHERE('module', 'tac')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        return view('v1.ncaa.tac.show', compact('allTacs', 'aircraftMakers', 'aircraftModels', 'checkforaocupdates'));
    }
}
