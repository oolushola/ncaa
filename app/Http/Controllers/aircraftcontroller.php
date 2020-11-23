<?php

namespace App\Http\Controllers;
use App\aoc;
use App\aircraftMaker;
use App\aircrafts;
use App\Http\Requests;
use App\Http\Requests\aircraftRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Ilumminate\Pagination\Paginator;
use Auth;
use App\updateHistory;
use App\aircrafttype;
use App\aocAircrafts;
use App\focc;

class aircraftcontroller extends Controller
{
    public function viewallaircraft(){
        if(Auth::check() && Auth::user()->role){
            
            $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY aoc_holder ASC'));
            
            $allaocs = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
            
            $checkforaircraftstatuslastupdate = updateHistory::WHERE('module', 'ac-status')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

            return view('v1.ncaa.ac-status.view-all-ac-status', compact('allAircraftStatus', 'allaocs', 'checkforaircraftstatuslastupdate'));
        }
        return redirect()->route('login');
    }
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aoclists = aoc::SELECT('id', 'aoc_holder', 'created_by')->ORDERBY('aoc_holder', 'ASC')->GET();
            $aircraftMakerLists = aircraftMaker::SELECT('id', 'aircraft_maker')->ORDERBY('aircraft_maker', 'ASC')->GET();
            $allAirCraftLists = DB::SELECT(DB::RAW('SELECT a.id, a.registration_marks, a.aircraft_type, a.created_by, b.aoc_holder FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b ON a.aoc_holder_id = b.id ORDER BY b.aoc_holder ASC'));
            // $allAirCraftLists = Paginator::make($query, count($query), $result_per_page);
    
            return view('v1.ncaa.ac-status.add-new-aircraft', compact('aoclists', 'aircraftMakerLists', 'allAirCraftLists'));    
        }
        return redirect()->route('login');
    }

    public function getAircraftMakebyAoc($id){
        if(Auth::check() && Auth::user()->role){
            $result = '<select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                        <option value="0">Aircraft Make</option>';
                $query = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_aircraft_makers WHERE id IN (SELECT aircraft_maker_id FROM tbl_ncaa_aoc_aircrafts WHERE aoc_holder_id = '.$id.')'));
                    foreach($query as $option){
                        $result.='<option value='.$option->id.'>'.$option->aircraft_maker.'</option>';
                    }
            $result.='</select>';

            return $result;
        }
        return redirect()->route('login');
    }

    public function getAircraftType($id){
        if(Auth::check() && Auth::user()->role){
            $result = '<select class="form-control" name="aircraft_type" id="aircraft_type">
                        <option value="0">Choose aircraft type</option>';
                $query = aircrafttype::WHERE('aircraft_maker_id', $id)->GET();
                    foreach($query as $option){
                        $result.='<option value="'.$option->aircraft_type.'">'.$option->aircraft_type.'</option>';
                    }
            $result.='</select>';

            return $result;
        }
        return redirect()->route('login');
    }


    public function store(aircraftRequest $request){
       if(Auth::check() && Auth::user()->role){
            $check = aircrafts::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('aircraft_maker_id', $request->get('aircraft_maker_id'))->WHERE('registration_marks', $request->get('registration_marks'))->exists();
            if($check){
            return 'exists';
            }
            else{
                $aircraft = aircrafts::CREATE($request->all());
                $id = $aircraft->id;
                $recid=aircrafts::findOrFail($id);
                if($request->hasFile('file')){
                    $c_of_a = $request->file('file');
                    $name = str_slug($request->get('aircraft_type'))."-".str_slug($request->get('registration_marks')).'.'.$c_of_a->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/c-of-a/');
                    $cofoaPath = $destination_path."/".$name;
                    $c_of_a->move($destination_path, $name);
                    $recid->c_of_a = $name;
                    $recid->save();
                }
                return 'saved';
            }
       }
        return redirect()-route('login');
    }

    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $aoclists = aoc::SELECT('id', 'aoc_holder', 'created_by')->ORDERBY('aoc_holder', 'ASC')->GET();
            $recid = aircrafts::findOrFail(base64_decode($id));
            $aocholderid = $recid->aoc_holder_id;
            $aircracfrmakerid = $recid->aircraft_maker_id;
            
            $aircraftMakerLists = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_aircraft_makers WHERE id IN (SELECT aircraft_maker_id FROM tbl_ncaa_aoc_aircrafts WHERE aoc_holder_id = '.$aocholderid.')'));

            $aircraftTypelists = aircrafttype::WHERE('aircraft_maker_id', $aircracfrmakerid)->GET();
            
            $allAirCraftLists = DB::SELECT(DB::RAW('SELECT a.id, a.registration_marks, a.aircraft_type, a.created_by, b.aoc_holder FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b ON a.aoc_holder_id = b.id ORDER BY b.aoc_holder ASC'));
            
            return view('v1.ncaa.ac-status.edit', compact('aoclists', 'aircraftMakerLists', 'allAirCraftLists', 'recid', 'aircraftTypelists'));    
        }
        return redirect()->route('login');
    }

    public function update(aircraftRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = aircrafts::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('aircraft_maker_id', $request->get('aircraft_maker_id'))->WHERE('registration_marks', $request->get('registration_marks'))->WHERE('id', '<>', $id)->exists();
            if($check){
             return 'exists';
            }
            else{
                $recid = aircrafts::findOrFail($id);
                $recid->UPDATE($request->all());
                 if($request->hasFile('file')){
                     $c_of_a = $request->file('file');
                     $name = str_slug($request->get('aircraft_type'))."-".str_slug($request->get('registration_marks')).'.'.$c_of_a->getClientOriginalExtension();
                     $destination_path = public_path('confidentials/c-of-a/');
                     $cofoaPath = $destination_path."/".$name;
                     $c_of_a->move($destination_path, $name);
                     $recid->c_of_a = $name;
                     $recid->save();
                 }
                 $update = updateHistory::CREATE($request->all());
                 return 'updated';
             }
     
        }
        return redirect()->route('login');
    }
    
    public function getallaircraftsbyaoc(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<select name="aircraft_maker_id" id="aircraftMakerId">
                        <option value="0">Aircraft Make</option>';
                $query = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_aircraft_makers WHERE id IN (SELECT aircraft_maker_id FROM tbl_ncaa_aoc_aircrafts WHERE aoc_holder_id = '.$request->get('aoc_holder_id').')'));
                    foreach($query as $option){
                        $answer.='<option value='.$option->id.'>'.$option->aircraft_maker.'</option>';
                    }
            $answer.='</select>';

            $result = '<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th width="5%"><b>#</b></th>
                    <th width="5%"><b>Registration Marks</b></th>
                    <th  width="10%"><b>Aircraft Type</b></th>
                    <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                    <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                    <th width="8%" align="center"><b>Current Registration Date</b></th>
                    <th width="20%"><b>Registered Owner</b></th>
                    <th width="12%" align="center"><b>C of A Status</b></th>
                    <th><b>Remarks</b></th>
                    <th><b>Weight (Kg)</b></th>
                </tr>
            </thead>
            <tbody>';
         $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id AND a.aoc_holder_id = '.$request->get('aoc_holder_id').' '));
        if(count($allAircraftStatus)){
                $counter = 0;
                    foreach($allAircraftStatus as $aircraft){
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                            
                        $now = time();
                        $due_date = strtotime($aircraft->c_of_a_status);;
                        $datediff = $due_date - $now;
                        $numberofdays = round($datediff / (60 * 60 * 24));

                        if($numberofdays > 90 ){
                            $bgcolor = "green";
                            $color = "#fff";
                            $remarks = 'Active';
                        }
                        else if(($numberofdays >= 0) && ($numberofdays <=90)){
                            $bgcolor = "#ffbf00";
                            $color = "#000";
                            $remarks = 'Expiring soon';
                        }
                        else{
                            $bgcolor = "red";
                            $color = "#000";
                            $remarks = 'Expired';
                        }
                        $convertRegDate = strtotime($aircraft->registration_date);
                        $regDate = date('d/m/Y', $convertRegDate);
                        $cofa = date('d/m/Y', $due_date);

                    $result.='<tr class='.$css_style.'>
                        <td>'.$counter.'</td>
                        <td>'.$aircraft->registration_marks.'</td>
                        <td>'.$aircraft->aircraft_type.'</td>
                        <td>'.$aircraft->aircraft_serial_number.'</td>
                        <td align="center">'.$aircraft->year_of_manufacture.'</td>
                        <td align="center">'.$regDate.'</td>
                        <td>'.$aircraft->registered_owner.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                    '.$cofa.'
                                </a>
                        </td>
                        <td>'.$remarks.'</td>
                        <td>'.$aircraft->weight.'</td>
                    </tr>';
                    }
                }
                else{
                    $result.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
                }
                
            $result.='</tbody>
        </table>';
        
        return $answer.'`'.$result;
        }
        return redirect()->route('login');
    }   
    
    public function getallaircraftsbyaocandmake(Request $request){
        if(Auth::check() && Auth::user()->role)
        {
            $aircraft_maker_id = $request->get('aircraft_maker_id');
            $aoc_holder_id = $request->get('aoc_holder_id');

            $result = '<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th width="5%"><b>#</b></th>
                    <th width="5%"><b>Registration Marks</b></th>
                    <th  width="10%"><b>Aircraft Type</b></th>
                    <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                    <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                    <th width="8%" align="center"><b>Current Registration Date</b></th>
                    <th width="20%"><b>Registered Owner</b></th>
                    <th width="12%" align="center"><b>C of A Status</b></th>
                    <th><b>Remarks</b></th>
                    <th><b>Weight (Kg)</b></th>
                </tr>
            </thead>
            <tbody>';
         $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id AND a.aoc_holder_id = '.$aoc_holder_id.' AND a.aircraft_maker_id = '.$aircraft_maker_id.' '));
        if(count($allAircraftStatus)){
                $counter = 0;
                    foreach($allAircraftStatus as $aircraft){
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                            
                        $now = time();
                        $due_date = strtotime($aircraft->c_of_a_status);;
                        $datediff = $due_date - $now;
                        $numberofdays = round($datediff / (60 * 60 * 24));

                        if($numberofdays > 90 ){
                            $bgcolor = "green";
                            $color = "#fff";
                            $remarks = 'Active';
                        }
                        else if(($numberofdays >= 0) && ($numberofdays <=90)){
                            $bgcolor = "#ffbf00";
                            $color = "#000";
                            $remarks = 'Expiring Soon';
                        }
                        else{
                            $bgcolor = "red";
                            $color = "#000";
                            $remarks = 'Expired';
                        }
                        $convertRegDate = strtotime($aircraft->registration_date);
                        $regDate = date('d/m/Y', $convertRegDate);
                        $cofa = date('d/m/Y', $due_date);

                    $result.='<tr class='.$css_style.'>
                        <td>'.$counter.'</td>
                        <td>'.$aircraft->registration_marks.'</td>
                        <td>'.$aircraft->aircraft_type.'</td>
                        <td>'.$aircraft->aircraft_serial_number.'</td>
                        <td align="center">'.$aircraft->year_of_manufacture.'</td>
                        <td align="center">'.$regDate.'</td>
                        <td>'.$aircraft->registered_owner.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                    '.$cofa.'
                                </a>
                        </td>
                        <td>'.$remarks.'</td>
                        <td>'.$aircraft->weight.'</td>
                    </tr>';
                    }
                }
                else{
                    $result.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
                }
                
            $result.='</tbody>
        </table>';
        
        return $result;

        }
        return redirect()->route('login');
    }

    public function viewallactiveacstatus(Request $request) {
        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th width="5%"><b>Registration Marks</b></th>
                <th  width="10%"><b>Aircraft Type</b></th>
                <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                <th width="8%" align="center"><b>Current Registration Date</b></th>
                <th width="20%"><b>Registered Owner</b></th>
                <th width="12%" align="center"><b>C of A Status</b></th>
                <th><b>Remarks</b></th>
                <th><b>Weight (Kg)</b></th>
            </tr>
        </thead>
        <tbody>';
        $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY aoc_holder ASC'));
            if(count($allAircraftStatus)){
                $counter = 0;
                foreach($allAircraftStatus as $aircraft){
                    
                    $now = time();
                    $due_date = strtotime($aircraft->c_of_a_status);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    $convertRegDate = strtotime($aircraft->registration_date);
                    $regDate = date('d/m/Y', $convertRegDate);
                    $cofa = date('d/m/Y', $due_date);

                    if($numberofdays > 90 ){
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = 'Active';

                    $result.='<tr class='.$css_style.'>
                        <td>'.$counter.'</td>
                        <td>'.$aircraft->registration_marks.'</td>
                        <td>'.$aircraft->aircraft_type.'</td>
                        <td>'.$aircraft->aircraft_serial_number.'</td>
                        <td align="center">'.$aircraft->year_of_manufacture.'</td>
                        <td align="center">'.$regDate.'</td>
                        <td>'.$aircraft->registered_owner.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                    '.$cofa.'
                                </a>
                        </td>
                        <td>'.$remarks.'</td>
                        <td>'.$aircraft->weight.'</td>
                    </tr>';
                    }
                }
            }
            else{
                $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            
            $result.='</tbody>
        </table>';

        return $result;

    }

    public function viewallexpiringsoonacstatus(Request $request) {
        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th width="5%"><b>Registration Marks</b></th>
                <th  width="10%"><b>Aircraft Type</b></th>
                <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                <th width="8%" align="center"><b>Current Registration Date</b></th>
                <th width="20%"><b>Registered Owner</b></th>
                <th width="12%" align="center"><b>C of A Status</b></th>
                <th><b>Remarks</b></th>
                <th><b>Weight (Kg)</b></th>
            </tr>
        </thead>
        <tbody>';
        $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY aoc_holder ASC'));
            if(count($allAircraftStatus)){
                $counter = 0;
                foreach($allAircraftStatus as $aircraft){                    
                    $now = time();
                    $due_date = strtotime($aircraft->c_of_a_status);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    $convertRegDate = strtotime($aircraft->registration_date);
                    $regDate = date('d/m/Y', $convertRegDate);
                    $cofa = date('d/m/Y', $due_date);
                
                    if(($numberofdays >= 0) && ($numberofdays <=90)){
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = 'Expiring Soon';
                    
                    $result.='<tr class='.$css_style.'>
                        <td>'.$counter.'</td>
                        <td>'.$aircraft->registration_marks.'</td>
                        <td>'.$aircraft->aircraft_type.'</td>
                        <td>'.$aircraft->aircraft_serial_number.'</td>
                        <td align="center">'.$aircraft->year_of_manufacture.'</td>
                        <td align="center">'.$regDate.'</td>
                        <td>'.$aircraft->registered_owner.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                    '.$cofa.'
                                </a>
                        </td>
                        <td>'.$remarks.'</td>
                        <td>'.$aircraft->weight.'</td>
                    </tr>';
                    }
                }
            }
            else{
                $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            
            $result.='</tbody>
        </table>';

        return $result;
    }

    public function viewallexpiredacstatus(Request $request) {
        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th width="5%"><b>Registration Marks</b></th>
                <th  width="10%"><b>Aircraft Type</b></th>
                <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                <th width="8%" align="center"><b>Current Registration Date</b></th>
                <th width="20%"><b>Registered Owner</b></th>
                <th width="12%" align="center"><b>C of A Status</b></th>
                <th><b>Remarks</b></th>
                <th><b>Weight (Kg)</b></th>
            </tr>
        </thead>
        <tbody>';
        $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY aoc_holder ASC'));
            if(count($allAircraftStatus)){
                $counter = 0;
                foreach($allAircraftStatus as $aircraft){                    
                    $now = time();
                    $due_date = strtotime($aircraft->c_of_a_status);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    $convertRegDate = strtotime($aircraft->registration_date);
                    $regDate = date('d/m/Y', $convertRegDate);
                    $cofa = date('d/m/Y', $due_date);
                
                    if($numberofdays <= 0) {
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = 'Expired';
                    
                    $result.='<tr class='.$css_style.'>
                        <td>'.$counter.'</td>
                        <td>'.$aircraft->registration_marks.'</td>
                        <td>'.$aircraft->aircraft_type.'</td>
                        <td>'.$aircraft->aircraft_serial_number.'</td>
                        <td align="center">'.$aircraft->year_of_manufacture.'</td>
                        <td align="center">'.$regDate.'</td>
                        <td>'.$aircraft->registered_owner.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                    '.$cofa.'
                                </a>
                        </td>
                        <td>'.$remarks.'</td>
                        <td>'.$aircraft->weight.'</td>
                    </tr>';
                    }
                }
            }
            else{
                $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            
            $result.='</tbody>
        </table>';

        return $result;
    }

    public function viewacstatusbyinascdesc(Request $request){
        
        $registration_marks = strtoupper($request->get('registration_marks'));

        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th><b>Registered Operator</b></th>
                <th width="5%"><b>Registration Marks</b></th>
                <th  width="10%"><b>Aircraft Type</b></th>
                <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                <th width="8%" align="center"><b>Current Registration Date</b></th>
                <th width="20%"><b>Registered Owner</b></th>
                <th width="12%" align="center"><b>C of A Status</b></th>
                <th><b>Remarks</b></th>
                <th><b>Weight (Kg)</b></th>
            </tr>
        </thead>
        <tbody>';
        $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY a.registration_marks '.$registration_marks.''));
        if(count($allAircraftStatus)){
            $counter = 0;
                foreach($allAircraftStatus as $aircraft){
                    $counter++; 
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                    $now = time();
                    $due_date = strtotime($aircraft->c_of_a_status);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    $convertRegDate = strtotime($aircraft->registration_date);
                    $regDate = date('d/m/Y', $convertRegDate);
                    $cofa = date('d/m/Y', $due_date);

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = 'Active';
                    }
                    else if(($numberofdays >= 0) && ($numberofdays <=90)){
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = 'Expiring Soon';
                    }
                    else{
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = 'Expired';
                    }
                $result.='<tr class='.$css_style.'>
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aircraft->aoc_holder).'</td>
                    <td>'.$aircraft->registration_marks.'</td>
                    <td>'.$aircraft->aircraft_type.'</td>
                    <td>'.$aircraft->aircraft_serial_number.'</td>
                    <td align="center">'.$aircraft->year_of_manufacture.'</td>
                    <td align="center">'.$regDate.'</td>
                    <td>'.$aircraft->registered_owner.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                '.$cofa.'
                            </a>
                    </td>
                    <td>'.$remarks.'</td>
                    <td>'.$aircraft->weight.'</td>
                </tr>';
                }
            }
            else{
                $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            
            $result.='</tbody>
        </table>';

        return $result;

    }

    public function sortByAircraftTpe(Request $request) {
        $aircraftType = strtoupper($request->aircraftType);
        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th><b>Registered Operator</b></th>
                <th width="5%"><b>Registration Marks</b></th>
                <th  width="10%"><b>Aircraft Type</b></th>
                <th width="5%" align="center"><b>Aircraft Serial Number</b></th>
                <th width="5%" align="center"><b>Year of Mnaufacture</b></th>
                <th width="8%" align="center"><b>Current Registration Date</b></th>
                <th width="20%"><b>Registered Owner</b></th>
                <th width="12%" align="center"><b>C of A Status</b></th>
                <th><b>Remarks</b></th>
                <th><b>Weight (Kg)</b></th>
            </tr>
        </thead>
        <tbody>';
        $allAircraftStatus = DB::SELECT(DB::RAW('SELECT b.aoc_holder, c.aircraft_maker, a.* FROM tbl_ncaa_aircrafts a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_aircraft_makers c ON a.aoc_holder_id=b.id AND a.aircraft_maker_id = c.id ORDER BY c.aircraft_maker '.$aircraftType.''));
        if(count($allAircraftStatus)){
            $counter = 0;
                foreach($allAircraftStatus as $aircraft){
                    $counter++; 
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                    $now = time();
                    $due_date = strtotime($aircraft->c_of_a_status);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    $convertRegDate = strtotime($aircraft->registration_date);
                    $regDate = date('d/m/Y', $convertRegDate);
                    $cofa = date('d/m/Y', $due_date);

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = 'Active';
                    }
                    else if(($numberofdays >= 0) && ($numberofdays <=90)){
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = 'Expiring Soon';
                    }
                    else{
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = 'Expired';
                    }
                $result.='<tr class='.$css_style.'>
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aircraft->aoc_holder).'</td>
                    <td>'.$aircraft->registration_marks.'</td>
                    <td>'.$aircraft->aircraft_type.'</td>
                    <td>'.$aircraft->aircraft_serial_number.'</td>
                    <td align="center">'.$aircraft->year_of_manufacture.'</td>
                    <td align="center">'.$regDate.'</td>
                    <td>'.$aircraft->registered_owner.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        <a href="/confidentials/c-of-a/'.$aircraft->c_of_a.'" target="_blank"style="color:'.$color.'">
                                '.$cofa.'
                            </a>
                    </td>
                    <td>'.$remarks.'</td>
                    <td>'.$aircraft->weight.'</td>
                </tr>';
                }
            }
            else{
                $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
            
            $result.='</tbody>
        </table>';

        return $result;
    }




    public function destroy($id){
        if(Auth::check() && Auth::user()->role)
        {
            $checkfocc = focc::WHERE('aircraft_type', $id)->exists();
            if($checkfocc){
                return 'cant_delete';
            }
            $recid = aircrafts::findOrFail($id);
            $recid->DELETE();
            updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'ac-status', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->registration_marks
            ]);
            return 'deleted';
        }
        return redirect()->route('login');
    }
}


