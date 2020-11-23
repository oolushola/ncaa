<?php

namespace App\Http\Controllers;
use App\aoc;
use App\generalAviation;
use App\aircrafts;
use App\focc_and_mcc;
use App\Http\Requests;
use App\Http\Requests\foccAndMccRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\stateOfRegistry;
use App\aircraftMaker;
use App\foreignRegistrationMarks;
use App\aircrafttype;

class foccAndMccController extends Controller
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

    public function index(){
        if(Auth::check() && Auth::user()->role){
            $focclists = focc_and_mcc::SELECT(
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

    public function store(foccAndMccRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = focc_and_mcc::WHERE('aircraft_reg_no_id', $request->get('aircraft_reg_no_id'))->WHERE('amo_holder_status', '=', '1')->exists();
                if($check){
                    return 'exists';
                }
                else{
                    focc_and_mcc::CREATE($request->all());
                    return 'saved';
                }
        }
        return redirect()->route('login');
    }

    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = focc_and_mcc::findOrFail($id);
            $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
            $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
            $stateofregistries = stateOfRegistry::SELECT('id', 'state_of_registry')->ORDERBY('state_of_registry', 'ASC')->GET();
            $aircraftmakers = aircraftMaker::SELECT('id', 'aircraft_maker')->ORDERBY('aircraft_maker', 'ASC')->GET();
            $foreignregmarks = foreignRegistrationMarks::SELECT('id', 'foreign_registration_marks')->ORDERBY('foreign_registration_marks', 'ASC')->GET();
            $aircraft_types = aircrafttype::ORDERBY('aircraft_type', 'ASC')->GET();

            $focclists = focc_and_mcc::SELECT(
                'id', 
                'focc_no', 
                'date_of_first_issue', 
                'renewal', 'created_by')->ORDERBY('date_of_first_issue', 'ASC')->PAGINATE(15);
            return view('v1.ncaa.focc.edit',
             compact(
                'aoclists', 
                'focclists', 
                'generalaviations', 
                'stateofregistries', 
                'aircraftmakers',
                'foreignregmarks',
                'recid',
                'aircraft_types'
                )
            );
        }
        return redirect()->route('login');
    }

    public function update(foccAndMccRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = focc_and_mcc::WHERE('aircraft_reg_no_id', $request->get('aircraft_reg_no_id'))->WHERE('amo_holder_status', $request->amo_holder_status)->WHERE('id', '<>', $id)->exists();
                if($check){
                    return 'exists';
                }
                else{
                    $recid = focc_and_mcc::findOrFail($id);
                    $recid->UPDATE($request->all());
                    updateHistory::CREATE($request->all());
                    return 'updated';
                }
        }
        return redirect()->route('login');
    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $allfoccs = DB::SELECT(DB::RAW('SELECT a.*, b.state_of_registry, c.aircraft_maker, d.aircraft_type, e.foreign_registration_marks FROM tbl_ncaa_focc_and_mccs a JOIN tbl_ncaa_state_of_registries b JOIN tbl_ncaa_aircraft_makers c JOIN tbl_ncaa_aircraft_types d JOIN tbl_ncaa_foreign_registration_marks e ON a.state_of_registry_id = b.id AND a.aircraft_maker_id = c.id AND a.aircraft_type_id = d.id AND a.aircraft_reg_no_id = e.id'));
            $checkforfoccupdates = updateHistory::WHERE('module', 'focc')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();
            return view('v1.ncaa.focc.show', compact('allfoccs', 'checkforfoccupdates'));
        }
        return redirect()->route('login');
    }

    public function destroy($id){
        if(Auth::check() && Auth::user()->role){
            $recid = focc_and_mcc::findOrFail($id);
            $recid->DELETE();
            updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'focc', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->focc_no
            ]);
            return 'deleted';
        }
        return redirect()->route('login');
    }

    public function sorts(Request $request){

        $oderbycriteria = $request->criteria;
        $direction = $request->direction;
        
        $allfoccs = DB::SELECT(DB::RAW('SELECT a.*, b.state_of_registry, c.aircraft_maker, d.aircraft_type, e.foreign_registration_marks FROM tbl_ncaa_focc_and_mccs a JOIN tbl_ncaa_state_of_registries b JOIN tbl_ncaa_aircraft_makers c JOIN tbl_ncaa_aircraft_types d JOIN tbl_ncaa_foreign_registration_marks e ON a.state_of_registry_id = b.id AND a.aircraft_maker_id = c.id AND a.aircraft_type_id = d.id AND a.aircraft_reg_no_id = e.id ORDER BY '.$oderbycriteria.' '.$direction.' '));

       $answer='<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b></th>
                    <th><b>Operator</b></th>
                    <th width="10%"><b>FOCC No.</b></th>
                    <th width="10%"><b>MCC No.</b></th>
                    <th width="10%"><b>State of Registry</b></th>
                    <th width="10%"><b>Registered Owner</b></th>
                    <th width="10%"><b>A/C Type</b></th>
                    <th width="10%"><b>Aircraft Reg. No</b></th>
                    <th width="10%" class="center"><b>Date of first Issue</b></th>
                    <th width="10%" class="center"><b>Renewal Date</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b>Status</b></th>

                </tr>
            </thead>
            <tbody>';
                if(count($allfoccs)) {
                    $count = 0;
                    foreach($allfoccs as $focc) {
                        $count++;
                            $now = time();
                            $due_date = strtotime($focc->valid_till);;
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

                            if($focc->amo_holder_status){
                                $additionStyle = "";
                            }
                            else{
                                $additionStyle = "<span class='mdi mdi-close-octagon-outline' style='font-size:25px;color:red; margin-top:0px; float:right' title='This Operator Licence has been revoked'></span>";
                            }
                            
                            $converdatetotimeoffirstissue = strtotime($focc->date_of_first_issue); 
                            $date_of_first_issue = date('j/m/Y', $converdatetotimeoffirstissue);

                            $converdatetotimeofrenewal = strtotime($focc->renewal); 
                            $renewal = date('j/m/Y', $converdatetotimeofrenewal);

                            $converdatetotimeofvalidtill = strtotime($focc->valid_till); 
                            $valid_till = date('j/m/Y', $converdatetotimeofvalidtill);
                            
                        $answer.='<tr style="font-family:tahoma; padding:" class="'.$css_style.'">
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'. strtoupper($focc->operator) .' '.$additionStyle.'</td>
                            <td>'. $focc->focc_no .'</td>
                            <td>'. $focc->mcc_no .'</td>
                            <td>'. $focc->state_of_registry .'</td>
                            <td>'. $focc->registered_owner .'</td>
                            <td>'. $focc->aircraft_type .'</td>
                            <td class="center">'. $focc->foreign_registration_marks .'</td>
                            <td class="center">'. $date_of_first_issue .'</td>
                            <td class="center">'. $renewal .'</td>
                            <td class="center">'. $valid_till .'</td>
                            <td style="background:'.$bgcolor.'; color:'.$color.'">'. $status .'</td>
                        </tr>';
                    }
                }
                else{
                        $answer.='<tr>
                            <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                        </tr>';
                }
            $answer.='</tbody>
        </table>';

        return $answer;
    }

    public function activeStatus() {
        $allfoccs = DB::SELECT(DB::RAW('SELECT a.*, b.state_of_registry, c.aircraft_maker, d.aircraft_type, e.foreign_registration_marks FROM tbl_ncaa_focc_and_mccs a JOIN tbl_ncaa_state_of_registries b JOIN tbl_ncaa_aircraft_makers c JOIN tbl_ncaa_aircraft_types d JOIN tbl_ncaa_foreign_registration_marks e ON a.state_of_registry_id = b.id AND a.aircraft_maker_id = c.id AND a.aircraft_type_id = d.id AND a.aircraft_reg_no_id = e.id '));

       $answer='<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b></th>
                    <th><b>Operator</b></th>
                    <th width="10%"><b>FOCC No.</b></th>
                    <th width="10%"><b>MCC No.</b></th>
                    <th width="10%"><b>State of Registry</b></th>
                    <th width="10%"><b>Registered Owner</b></th>
                    <th width="10%"><b>A/C Type</b></th>
                    <th width="10%"><b>Aircraft Reg. No</b></th>
                    <th width="10%" class="center"><b>Date of first Issue</b></th>
                    <th width="10%" class="center"><b>Renewal Date</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b>Status</b></th>

                </tr>
            </thead>
            <tbody>';
                if(count($allfoccs)) {
                    $count = 0;
                    foreach($allfoccs as $focc) {

                            $now = time();
                            $due_date = strtotime($focc->valid_till);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));
                            if($focc->amo_holder_status){
                                $additionStyle = "";
                            }
                            else{
                                $additionStyle = "<span class='mdi mdi-close-octagon-outline' style='font-size:25px;color:red; margin-top:0px; float:right' title='This Operator Licence has been revoked'></span>";
                            }

                            if($numberofdays > 90 ){
                                $count++;
                                $status = "Active";
                                $bgcolor = "green";
                                $color = "#fff";
                                

                            date_default_timezone_set("Africa/Lagos");
                            $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                            
                            $converdatetotimeoffirstissue = strtotime($focc->date_of_first_issue); 
                            $date_of_first_issue = date('j/m/Y', $converdatetotimeoffirstissue);

                            $converdatetotimeofrenewal = strtotime($focc->renewal); 
                            $renewal = date('j/m/Y', $converdatetotimeofrenewal);

                            $converdatetotimeofvalidtill = strtotime($focc->valid_till); 
                            $valid_till = date('j/m/Y', $converdatetotimeofvalidtill);
                            
                            $answer.='<tr style="font-family:tahoma; padding:" class="'.$css_style.'">
                                <td style="font-size:11px;">'.$count.'</td>
                                <td>'. strtoupper($focc->operator) .' '.$additionStyle.'</td>
                                <td>'. $focc->focc_no .'</td>
                                <td>'. $focc->mcc_no .'</td>
                                <td>'. $focc->state_of_registry .'</td>
                                <td>'. $focc->registered_owner .'</td>
                                <td>'. $focc->aircraft_type .'</td>
                                <td class="center">'. $focc->foreign_registration_marks .'</td>
                                <td class="center">'. $date_of_first_issue .'</td>
                                <td class="center">'. $renewal .'</td>
                                <td class="center">'. $valid_till .'</td>
                                <td style="background:'.$bgcolor.'; color:'.$color.'">'. $status .'</td>
                            </tr>';
                        }
                    }
                }
                else{
                        $answer.='<tr>
                            <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                        </tr>';
                }
            $answer.='</tbody>
        </table>';

        return $answer;
    }

    public function expiringStatus() {
        $allfoccs = DB::SELECT(DB::RAW('SELECT a.*, b.state_of_registry, c.aircraft_maker, d.aircraft_type, e.foreign_registration_marks FROM tbl_ncaa_focc_and_mccs a JOIN tbl_ncaa_state_of_registries b JOIN tbl_ncaa_aircraft_makers c JOIN tbl_ncaa_aircraft_types d JOIN tbl_ncaa_foreign_registration_marks e ON a.state_of_registry_id = b.id AND a.aircraft_maker_id = c.id AND a.aircraft_type_id = d.id AND a.aircraft_reg_no_id = e.id '));

       $answer='<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b></th>
                    <th><b>Operator</b></th>
                    <th width="10%"><b>FOCC No.</b></th>
                    <th width="10%"><b>MCC No.</b></th>
                    <th width="10%"><b>State of Registry</b></th>
                    <th width="10%"><b>Registered Owner</b></th>
                    <th width="10%"><b>A/C Type</b></th>
                    <th width="10%"><b>Aircraft Reg. No</b></th>
                    <th width="10%" class="center"><b>Date of first Issue</b></th>
                    <th width="10%" class="center"><b>Renewal Date</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b>Status</b></th>

                </tr>
            </thead>
            <tbody>';
                if(count($allfoccs)) {
                    $count = 0;
                    foreach($allfoccs as $focc) {

                            $now = time();
                            $due_date = strtotime($focc->valid_till);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));

                            if($focc->amo_holder_status){
                                $additionStyle = "";
                            }
                            else{
                                $additionStyle = "<span class='mdi mdi-close-octagon-outline' style='font-size:25px;color:red; margin-top:0px; float:right' title='This Operator Licence has been revoked'></span>";
                            }
                            
                            if(($numberofdays >= 1) && ($numberofdays <=90)){
                                $count++;
                                $status = "Expiring soon";
                                $bgcolor = "#ffbf00";
                                $color = "#000";
                            
                            date_default_timezone_set("Africa/Lagos");
                            $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                            
                            $converdatetotimeoffirstissue = strtotime($focc->date_of_first_issue); 
                            $date_of_first_issue = date('j/m/Y', $converdatetotimeoffirstissue);

                            $converdatetotimeofrenewal = strtotime($focc->renewal); 
                            $renewal = date('j/m/Y', $converdatetotimeofrenewal);

                            $converdatetotimeofvalidtill = strtotime($focc->valid_till); 
                            $valid_till = date('j/m/Y', $converdatetotimeofvalidtill);
                            
                            $answer.='<tr style="font-family:tahoma; padding:" class="'.$css_style.'">
                                <td style="font-size:11px;">'.$count.'</td>
                                <td>'. strtoupper($focc->operator).' '.$additionStyle.'</td>
                                <td>'. $focc->focc_no .'</td>
                                <td>'. $focc->mcc_no .'</td>
                                <td>'. $focc->state_of_registry .'</td>
                                <td>'. $focc->registered_owner .'</td>
                                <td>'. $focc->aircraft_type .'</td>
                                <td class="center">'. $focc->foreign_registration_marks .'</td>
                                <td class="center">'. $date_of_first_issue .'</td>
                                <td class="center">'. $renewal .'</td>
                                <td class="center">'. $valid_till .'</td>
                                <td style="background:'.$bgcolor.'; color:'.$color.'">'. $status .'</td>
                            </tr>';
                        }
                    }
                }
                else{
                        $answer.='<tr>
                            <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                        </tr>';
                }
            $answer.='</tbody>
        </table>';

        return $answer;
    }

    public function expiredtatus() {
        $allfoccs = DB::SELECT(DB::RAW('SELECT a.*, b.state_of_registry, c.aircraft_maker, d.aircraft_type, e.foreign_registration_marks FROM tbl_ncaa_focc_and_mccs a JOIN tbl_ncaa_state_of_registries b JOIN tbl_ncaa_aircraft_makers c JOIN tbl_ncaa_aircraft_types d JOIN tbl_ncaa_foreign_registration_marks e ON a.state_of_registry_id = b.id AND a.aircraft_maker_id = c.id AND a.aircraft_type_id = d.id AND a.aircraft_reg_no_id = e.id '));

        $answer='<table class="table table-bordered" id="exportTableData">
             <thead>
                 <tr class="table-warning">
                     <th><b>#</b></th>
                     <th><b>Operator</b></th>
                     <th width="10%"><b>FOCC No.</b></th>
                     <th width="10%"><b>MCC No.</b></th>
                     <th width="10%"><b>State of Registry</b></th>
                     <th width="10%"><b>Registered Owner</b></th>
                     <th width="10%"><b>A/C Type</b></th>
                     <th width="10%"><b>Aircraft Reg. No</b></th>
                     <th width="10%" class="center"><b>Date of first Issue</b></th>
                     <th width="10%" class="center"><b>Renewal Date</b></th>
                     <th width="10%" class="center"><b>Expiry</b></th>
                     <th><b>Status</b></th>
 
                 </tr>
             </thead>
             <tbody>';
                 if(count($allfoccs)) {
                     $count = 0;
                     foreach($allfoccs as $focc) {
 
                        $now = time();
                        $due_date = strtotime($focc->valid_till);;
                        $datediff = $due_date - $now;
                        $numberofdays = round($datediff / (60 * 60 * 24));

                        if($focc->amo_holder_status){
                            $additionStyle = "";
                        }
                        else{
                            $additionStyle = "<span class='mdi mdi-close-octagon-outline' style='font-size:25px;color:red; margin-top:0px; float:right' title='This Operator Licence has been revoked'></span>";
                        }

                        if($numberofdays <=0 ){
                            $count++;
                            $status = "Expired";
                            $bgcolor = "red";
                            $color = "#000";
                        
                            date_default_timezone_set("Africa/Lagos");
                            $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                            
                            $converdatetotimeoffirstissue = strtotime($focc->date_of_first_issue); 
                            $date_of_first_issue = date('j/m/Y', $converdatetotimeoffirstissue);

                            $converdatetotimeofrenewal = strtotime($focc->renewal); 
                            $renewal = date('j/m/Y', $converdatetotimeofrenewal);

                            $converdatetotimeofvalidtill = strtotime($focc->valid_till); 
                            $valid_till = date('j/m/Y', $converdatetotimeofvalidtill);
                            
                            $answer.='<tr style="font-family:tahoma; padding:" class="'.$css_style.'">
                                <td style="font-size:11px;">'.$count.'</td>
                                <td>'. strtoupper($focc->operator) .' '.$additionStyle.'</td>
                                <td>'. $focc->focc_no .'</td>
                                <td>'. $focc->mcc_no .'</td>
                                <td>'. $focc->state_of_registry .'</td>
                                <td>'. $focc->registered_owner .'</td>
                                <td>'. $focc->aircraft_type .'</td>
                                <td class="center">'. $focc->foreign_registration_marks .'</td>
                                <td class="center">'. $date_of_first_issue .'</td>
                                <td class="center">'. $renewal .'</td>
                                <td class="center">'. $valid_till .'</td>
                                <td style="background:'.$bgcolor.'; color:'.$color.'">'. $status .'</td>
                            </tr>';
                        }
                     }
                 }
                 else{
                         $answer.='<tr>
                             <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
                         </tr>';
                 }
             $answer.='</tbody>
         </table>';
 
         return $answer;
        
    }
}
