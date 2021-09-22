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
        $tacs = DB::SELECT(
            DB::RAW(
                'SELECT b.`aircraft_maker`, a.* FROM tbl_ncaa_tacs a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker ASC'
            )
        );
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
        $tacs = DB::SELECT(
            DB::RAW(
                'SELECT b.`aircraft_maker`, a.* FROM tbl_ncaa_tacs a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker ASC'
            )
        );
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
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker ASC '
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


    public function sortBy(Request $request) {
        $sort = $request->sort;
        return $this->displayRecords($sort);
    }

    public function filterTacStatus(Request $request) {
        if($request->status == "active") {
           return $this->displayActiveRecords($request->sort);
        }
        if($request->status == 'expiring-soon') {
            return $this->displayExpiringSoonRecords($request->sort);
        }
        if($request->status == 'expired') {
            return $this->displayExpiredRecords($request->sort);
        }
    }

    public function displayRecords($sort) {
        $aircraftMakers = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $allTacs = DB::SELECT(
            DB::RAW(
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker '.$sort.' '
            )
        );

        $aircraftModels = DB::SELECT(
            DB::RAW(
                'SELECT * from tbl_ncaa_tac_aircraft_makers a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id'
            )
        );

        $records = '
        <table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="">
                    <th><b>#</b></th>
                    <th><b>Aircraft Maker</b></th>
                    <th><b>Aircraft Model</b></th>
                    <th><b>TAC Acceptance Cert. No.</b></th>
                    <th><b>Date Issued</b></th>
                    <th><b>TC Holder</b></th>
                    <th><b>Orginal Issued by</b></th>
                    <th><b>TC NO.</b></th>
                    <th><b>Remark</b></th>
                    <th><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';
            if(count($allTacs)) {
                $count = 0;
                foreach($allTacs as $tac) {
                    $count++;
                    $now = time();
                    $due_date = strtotime($tac->date_issued);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $status = "Active";
                        $bgcolor = "green";
                        $color = "#fff";
                    }
                    else if(($numberofdays >= 1) && ($numberofdays <=90)){
                        $status = "Expiring soon";
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                    }
                    else{
                        $status = "Expired";
                        $bgcolor = "red";
                        $color = "#000";
                    }

                    date_default_timezone_set("Africa/Lagos");
                    $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    
                    $dateIssued = strtotime($tac->date_issued);
                    $date_issued = date('j/m/Y', $dateIssued);
                
                $records.='<tr style="font-family:tahoma;" class="'.$css_style.'">
                    <td style="font-size:11px;">'.$count.'</td>
                    <td>'.strtoupper($tac->aircraft_maker).'</td>
                    <td>';
                        foreach($aircraftModels as $aircraft_type) {
                            if($tac->id == $aircraft_type->tac_id) {
                                $records.= $aircraft_type->aircraft_type.'<br />';
                            }
                        }
                    $records.='</td>
                    <td>'.$tac->tc_acceptance_approval.'</td>
                    <td>'.$date_issued.'</td>
                    <td>';
                        foreach($aircraftMakers as $maker) {
                            if($maker->id == $tac->tc_holder) {
                                $records.= $maker->aircraft_maker;
                            }
                        }
                    $records.='</td>
                    <td>'.$tac->original_tc_issued_by.'</td>
                    <td class="center">'.$tac->tc_no.'</td>
                    <td class="center">'.$tac->remarks.'</td>
                    
                    <td style="background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                </tr>';
                }
            }
            else {
                $records.='
                <tr>
                    <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            $records.='
            </tbody>
        </table>';
        return $records;
    }

    public function displayActiveRecords($sort) {
        $aircraftMakers = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $allTacs = DB::SELECT(
            DB::RAW(
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker '.$sort.' '
            )
        );

        $aircraftModels = DB::SELECT(
            DB::RAW(
                'SELECT * from tbl_ncaa_tac_aircraft_makers a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id'
            )
        );

        $records = '
        <table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="">
                    <th><b>#</b></th>
                    <th><b>Aircraft Maker</b></th>
                    <th><b>Aircraft Model</b></th>
                    <th><b>TAC Acceptance Cert. No.</b></th>
                    <th><b>Date Issued</b></th>
                    <th><b>TC Holder</b></th>
                    <th><b>Orginal Issued by</b></th>
                    <th><b>TC NO.</b></th>
                    <th><b>Remark</b></th>
                    <th><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';
            if(count($allTacs)) {
                $count = 0;
                foreach($allTacs as $tac) {
                    $count++;
                    $now = time();
                    $due_date = strtotime($tac->date_issued);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $status = "Active";
                        $bgcolor = "green";
                        $color = "#fff";
                        date_default_timezone_set("Africa/Lagos");
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                        $dateIssued = strtotime($tac->date_issued);
                        $date_issued = date('j/m/Y', $dateIssued);
                    
                        $records.='
                        <tr style="font-family:tahoma;" class="'.$css_style.'">
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'.strtoupper($tac->aircraft_maker).'</td>
                            <td>';
                                foreach($aircraftModels as $aircraft_type) {
                                    if($tac->id == $aircraft_type->tac_id) {
                                        $records.= $aircraft_type->aircraft_type.'<br />';
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->tc_acceptance_approval.'</td>
                            <td>'.$date_issued.'</td>
                            <td>';
                                foreach($aircraftMakers as $maker) {
                                    if($maker->id == $tac->tc_holder) {
                                        $records.= $maker->aircraft_maker;
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->original_tc_issued_by.'</td>
                            <td class="center">'.$tac->tc_no.'</td>
                            <td class="center">'.$tac->remarks.'</td>
                            
                            <td style="background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                        </tr>';
                    }
                }
            }
            else {
                $records.='
                <tr>
                    <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            $records.='
            </tbody>
        </table>';
        return $records;
    }

    public function displayExpiringSoonRecords($sort) {
        $aircraftMakers = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $allTacs = DB::SELECT(
            DB::RAW(
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker '.$sort.' '
            )
        );

        $aircraftModels = DB::SELECT(
            DB::RAW(
                'SELECT * from tbl_ncaa_tac_aircraft_makers a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id'
            )
        );

        $records = '
        <table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="">
                    <th><b>#</b></th>
                    <th><b>Aircraft Maker</b></th>
                    <th><b>Aircraft Model</b></th>
                    <th><b>TAC Acceptance Cert. No.</b></th>
                    <th><b>Date Issued</b></th>
                    <th><b>TC Holder</b></th>
                    <th><b>Orginal Issued by</b></th>
                    <th><b>TC NO.</b></th>
                    <th><b>Remark</b></th>
                    <th><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';
            if(count($allTacs)) {
                $count = 0;
                foreach($allTacs as $tac) {
                    $now = time();
                    $due_date = strtotime($tac->date_issued);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 1) && ($numberofdays <90)){
                        $count++;
                        $status = "Expiring soon";
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        date_default_timezone_set("Africa/Lagos");
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                        $dateIssued = strtotime($tac->date_issued);
                        $date_issued = date('j/m/Y', $dateIssued);
                    
                        $records.='
                        <tr style="font-family:tahoma;" class="'.$css_style.'">
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'.strtoupper($tac->aircraft_maker).'</td>
                            <td>';
                                foreach($aircraftModels as $aircraft_type) {
                                    if($tac->id == $aircraft_type->tac_id) {
                                        $records.= $aircraft_type->aircraft_type.'<br />';
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->tc_acceptance_approval.'</td>
                            <td>'.$date_issued.'</td>
                            <td>';
                                foreach($aircraftMakers as $maker) {
                                    if($maker->id == $tac->tc_holder) {
                                        $records.= $maker->aircraft_maker;
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->original_tc_issued_by.'</td>
                            <td class="center">'.$tac->tc_no.'</td>
                            <td class="center">'.$tac->remarks.'</td>
                            
                            <td style="background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                        </tr>';
                    }
                }
            }
            else {
                $records.='
                <tr>
                    <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            $records.='
            </tbody>
        </table>';
        return $records;
    }

    public function displayExpiredRecords($sort) {
        $aircraftMakers = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
        $allTacs = DB::SELECT(
            DB::RAW(
                'SELECT b.aircraft_maker, a.* FROM tbl_ncaa_tacs a JOIN `tbl_ncaa_aircraft_makers` b ON a.aircraft_maker_id = b.id ORDER BY b.aircraft_maker '.$sort.' '
            )
        );

        $aircraftModels = DB::SELECT(
            DB::RAW(
                'SELECT * from tbl_ncaa_tac_aircraft_makers a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id'
            )
        );

        $records = '
        <table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="">
                    <th><b>#</b></th>
                    <th><b>Aircraft Maker</b></th>
                    <th><b>Aircraft Model</b></th>
                    <th><b>TAC Acceptance Cert. No.</b></th>
                    <th><b>Date Issued</b></th>
                    <th><b>TC Holder</b></th>
                    <th><b>Orginal Issued by</b></th>
                    <th><b>TC NO.</b></th>
                    <th><b>Remark</b></th>
                    <th><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';
            if(count($allTacs)) {
                $count = 0;
                foreach($allTacs as $tac) {
                    $count++;
                    $now = time();
                    $due_date = strtotime($tac->date_issued);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays < 1){
                        $status = "Expired";
                        $bgcolor = "red";
                        $color = "#000";
                        date_default_timezone_set("Africa/Lagos");
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                        $dateIssued = strtotime($tac->date_issued);
                        $date_issued = date('j/m/Y', $dateIssued);
                    
                        $records.='
                        <tr style="font-family:tahoma;" class="'.$css_style.'">
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'.strtoupper($tac->aircraft_maker).'</td>
                            <td>';
                                foreach($aircraftModels as $aircraft_type) {
                                    if($tac->id == $aircraft_type->tac_id) {
                                        $records.= $aircraft_type->aircraft_type.'<br />';
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->tc_acceptance_approval.'</td>
                            <td>'.$date_issued.'</td>
                            <td>';
                                foreach($aircraftMakers as $maker) {
                                    if($maker->id == $tac->tc_holder) {
                                        $records.= $maker->aircraft_maker;
                                    }
                                }
                            $records.='</td>
                            <td>'.$tac->original_tc_issued_by.'</td>
                            <td class="center">'.$tac->tc_no.'</td>
                            <td class="center">'.$tac->remarks.'</td>
                            
                            <td style="background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                        </tr>';
                    }
                }
            }
            else {
                $records.='
                <tr>
                    <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            $records.='
            </tbody>
        </table>';
        return $records;
    }
}
