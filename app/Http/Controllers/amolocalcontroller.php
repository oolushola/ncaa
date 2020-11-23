<?php

namespace App\Http\Controllers;
use App\aoc;
use App\localamo;
use App\Http\Requests;
use App\Http\Requests\localAmoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\aircraftMaker;
use App\aircrafttype;


class amolocalcontroller extends Controller
{
    public function getaircraftype(Request $request, $aircraft_maker_id) {
        $getaircraftmakeid = aircraftMaker::SELECT('id')->WHERE('aircraft_maker', $aircraft_maker_id)->GET();
        $id = $getaircraftmakeid[0]['id'];
        $getaircrafts = aircrafttype::WHERE('aircraft_maker_id', $id)->ORDERBY('aircraft_type', 'ASC')->GET();

        $aircrafttypeselect = '<select class="form-control" name="aircraft_type_id" id="aircraft_type_id">
            <option value="">Select Aircraft Type</option>';

            foreach($getaircrafts as $aircrafttype) {
                $aircrafttypeselect.='<option value="'.$aircrafttype->aircraft_type.'">
                    '.$aircrafttype->aircraft_type.'
                </option>';
            }
        
        $aircrafttypeselect.='</select>';

        return $aircrafttypeselect;
        
        
    }
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aoclists = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
            $amolocals = localamo::ORDERBY('aoc_holder_id', 'ASC')->PAGINATE(15);
            $aircraftmakerlist = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
            return view('v1.ncaa.amo.local.create', compact('aoclists', 'amolocals', 'aircraftmakerlist'));
        }
        return redirect()->route('login');
    }
    public function store(localAmoRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = localamo::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->exists();
    		if($check){
    			return 'exists';
    		}
    		else{
    			$localamo = localamo::CREATE($request->all());
    			$id = $localamo->id;
    			$recid=localamo::findOrFail($id);
        		    if($request->hasFile('amo_approval_number_file')){
        			$amo_approval_number_file = $request->file('amo_approval_number_file');
        			$name = "amo-local-".str_slug($request->get('expiry'))."-".str_slug($request->get('aoc_holder_id')).'.'.$amo_approval_number_file->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/amo/local/');
        			$path = $destination_path."/".$name;
        			$amo_approval_number_file->move($destination_path, $name);
        			$recid->amo_approval_number_file = $name;
        			$recid->save();
                }
                if($request->hasFile('amo_pm_aprvl_pg_lep_file')){
        			$amo_pm_aprvl_pg_lep_file = $request->file('amo_pm_aprvl_pg_lep_file');
        			$name = "amo-local-amo-aprvl-".str_slug($request->get('expiry'))."-".str_slug($request->get('aoc_holder_id')).'.'.$amo_pm_aprvl_pg_lep_file->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/amo/local/');
        			$path = $destination_path."/".$name;
        			$amo_pm_aprvl_pg_lep_file->move($destination_path, $name);
        			$recid->amo_pm_aprvl_pg_lep_file = $name;
        			$recid->save();
                }
                return 'saved`'.$id;
            }
        }
        return redirect()->route('login');
    }
    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = localamo::findOrFail(base64_decode($id));
            $aoclists = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
            $amolocals = localamo::ORDERBY('aoc_holder_id', 'ASC')->PAGINATE(15);
            $aircraftmakerlist = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();
            $aircrafttypelists = aircrafttype::ORDERBY('aircraft_type', 'ASC')->GET();
            return view('v1.ncaa.amo.local.edit', compact('aoclists', 'amolocals', 'aircraftmakerlist', 'aircrafttypelists', 'recid'));
        }
        return redirect()->route('login');
    }
    public function update(localAmoRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $recid = localamo::findOrFail($id);
            $check = localamo::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('id', '<>', $id)->exists();
            if($check){
                return 'exists';
            }
            else{
                $recid->UPDATE($request->all());
                    if($request->hasFile('amo_approval_number_file')){
                    $amo_approval_number_file = $request->file('amo_approval_number_file');
                    $name = "amo-local-".str_slug($request->get('expiry'))."-".str_slug($request->get('aoc_holder_id')).'.'.$amo_approval_number_file->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/amo/local/');
                    $path = $destination_path."/".$name;
                    $amo_approval_number_file->move($destination_path, $name);
                    $recid->amo_approval_number_file = $name;
                    $recid->save();
                }
                if($request->hasFile('amo_pm_aprvl_pg_lep_file')){
                    $amo_pm_aprvl_pg_lep_file = $request->file('amo_pm_aprvl_pg_lep_file');
                    $name = "amo-local-amo-aprvl-".str_slug($request->get('expiry'))."-".str_slug($request->get('aoc_holder_id')).'.'.$amo_pm_aprvl_pg_lep_file->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/amo/local/');
                    $path = $destination_path."/".$name;
                    $amo_pm_aprvl_pg_lep_file->move($destination_path, $name);
                    $recid->amo_pm_aprvl_pg_lep_file = $name;
                    $recid->save();
                }
                updateHistory::CREATE($request->all());
                return 'updated`'.$id;
            }
        }
        return redirect()->route('login');    
    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $amolocalists = localamo::ORDERBY('aoc_holder_id', 'ASC')->GET();
            $checkforamolocalupdates = updateHistory::WHERE('module', 'amo-local')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

            $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.local_amo_id, b.* FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
            $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

            $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
            $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

            return view('v1.ncaa.amo.local.show', compact(
                    'amolocalists', 
                    'checkforamolocalupdates',
                    'aircraftMakerRatingsLists',
                    'aircraftTypeList'
                )
            );
        }
        return redirect()->route('login');
    }

    public function getsorts(Request $request) {
        $sortCriteria = $request->criteria;
        $direction = $request->direction;

        $amolocalists = localamo::ORDERBY($sortCriteria, $direction)->GET();
        $checkforamolocalupdates = updateHistory::WHERE('module', 'amo-local')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.local_amo_id, b.* FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
        $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

        $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
        $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

        $result='<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning" style="border-top:1px solid #ccc">
                    <th>#</th>
                    <th width="12%"><b>AMO Holder</b></th>
                    <th width="11%"><b>Amo Approval</b></th>
                    <th width="30%"><b>Ratings/Capabilities</b></th>
                    <th><b>Maintenance Locations</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th class="center"><b>Status</b></th>
                    <th width="12%" class="center"><b>APRVL PG & LEP</b></th>
                    <th><b>Days Left</b></th>
                    <th class="center"><b>Extention</b></th>
                </tr>
            </thead>
            <tbody>';
                if(count($amolocalists)){
                    $count = 0;
                    foreach($amolocalists as $localAmo){
                    $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $now = time();
                        $due_date = strtotime($localAmo->expiry);
                        $datediff = $due_date - $now;
                        $numberofdays = round($datediff / (60 * 60 * 24));

                        if($numberofdays > 90 ){
                            $status = "Active";
                            $bgcolor = "green";
                            $color = "#fff";
                        }
                        else if(($numberofdays >= 0) && ($numberofdays <=90)){
                            $status = "Expiring soon";
                            $bgcolor = "#ffbf00";
                            $color = "#000";
                        }
                        else{
                            $status = "Expired";
                            $bgcolor = "red";
                            $color = "#000";
                        }
                    
                        $expiry = date('d/m/Y', $due_date);
                        
                        if($localAmo->extention !=''){
                        $convertExtentionTotime = strtotime($localAmo->extention);
                            $extention = date('d/m/Y', $convertExtentionTotime);
                        }
                        else{
                            $extention = '';
                        }

                    $result.='<tr class='.$css_style.'>
                        <td>'.$count.'</td>
                        <td>'.strtoupper($localAmo->aoc_holder_id).'</td>
                        <td>
                            <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'" target="_blank">
                                '.$localAmo->amo_approval_number.'
                            </a>
                        </td>
                        <td>
                            <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                    if($aircraftMaker->local_amo_id == $localAmo->id){
                                       $result.='<li style="color:green; text-decoration:underline">
                                            '.$aircraftMaker->aircraft_maker.':
                                            <ul style="padding:0; margin:0; font-size:11px;">';
                                                foreach($aircraftTypeList as $aircraftType){
                                                        if($aircraftMaker->local_amo_id == $localAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                        $result.='<li style=\'display:inline-block; color:#333\'>
                                                            '.$aircraftType->aircraft_type.',</li>';
                                                    }
                                                }
                                            $result.='</ul>
                                        </li>';
                                    }
                                }
                            $result.='</ul>
                        </td>
                        <td >'.$localAmo->maintenance_locations.'</td>
                        <td style="line-height:18px" class="center">'.$expiry.'</td>
                        <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                        <td class="center">
                            <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                    '.$localAmo->amo_pm_aprvl_pg_lep.'
                                </a>
                            
                        </td>
                        <td >'.$numberofdays.'</td>
                        <td class="center">'.$extention.'</td>
                    </tr>';
                    }
                }
                else{
                $result.='<tr>
                    <td class="table-danger" colspan="11" style="font-size:11px;">No local AMO has been added yet.</td>
                </tr>';
                }
            $result.='</tbody>
        </table>';

        return $result;
    }

    public function getlocalamobystatus(Request $request){
       if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered" id="exportTableData">
                <thead>
                    <tr class="table-warning">
                        <th>#</th>
                        <th width="12%"><b>AMO Holder</b></th>
                        <th width="11%"><b>Amo Approval</b></th>
                        <th><b>Ratings/Capabilities</b></th>
                        <th><b>Maintenance Locations</b></th>
                        <th width="10%" class="center"><b>Expiry</b></th>
                        <th class="center"><b>Status</b></th>
                        <th width="12%" class="center"><b>APRVL PG & LEP</b></th>
                        <th><b>Days Left</b></th>
                        <th class="center"><b>Extention</b></th>
                    </tr>
                </thead>';

                $amobyactivestatus = localamo::ORDERBY('aoc_holder_id', 'ASC')->GET();
                $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.local_amo_id, b.* FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
                $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

                $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
                $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

                $answer.='<tbody>';
                    if(count($amobyactivestatus)){
                        $count = 0;  
                        foreach($amobyactivestatus as $localAmo){
                            $now = time();
                            $due_date = strtotime($localAmo->expiry);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));
                            $expiry = date('d/m/Y', $due_date);

                            if($localAmo->extention !=''){
                            $convertExtentionTotime = strtotime($localAmo->extention);
                                $extention = date('d/m/Y', $convertExtentionTotime);
                            }
                            else{
                                $extention = '';
                            }

                            if($numberofdays > 90 ){
                                $count++;
                                $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                $status = "Active";
                                $bgcolor = "green";
                                $color = "#fff";

                                $answer.='<tr class='.$css_style.'>
                                    <td style="font-size:11px;">'.$count.'</td>
                                    <td>'.strtoupper($localAmo->aoc_holder_id).'</td>
                                    <td>
                                        <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                            '.$localAmo->amo_approval_number.'
                                        </a>
                                    </td>
                                    <td>
                                        <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                        foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                            if($aircraftMaker->local_amo_id == $localAmo->id){
                                            $answer.='<li style="color:green; text-decoration:underline">
                                                    '.$aircraftMaker->aircraft_maker.':
                                                    <ul style="padding:0; margin:0; font-size:11px;">';
                                                        foreach($aircraftTypeList as $aircraftType){
                                                                if($aircraftMaker->local_amo_id == $localAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                                $answer.='<li style=\'display:inline-block; color:#333\'>
                                                                    '.$aircraftType->aircraft_type.',</li>';
                                                            }
                                                        }
                                                    $answer.='</ul>
                                                </li>';
                                            }
                                        }
                                    $answer.='</ul>
                                    </td>
                                    <td>'.$localAmo->maintenance_locations.'</td>
                                    <td style="line-height:18px" class="center">'.$expiry.'</td>
                                    <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                                    <td>
                                        <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                '.$localAmo->amo_pm_aprvl_pg_lep.';
                                            </a>
                                        
                                    </td>
                                    <td>'.$numberofdays.'</td>
                                    <td class="center">'.$extention.'</td>
                                </tr>';
                            }
                        }                  
                    }
                        
                    else{
                        $answer.='<tr>
                            <td class="table-danger" colspan="11" style="font-size:11px;">No local AMO has been added yet.</td>
                        </tr>';
                    }
                    
                $answer.='</tbody>
            </table>';

            return $answer;
        }
        return redirect()->route('login');
    }

    public function getlocalamobyexpiredstatus(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered" id="exportTableData">
                        <thead>
                            <tr class="table-warning" style="border-top:1px solid #ccc">
                                <th>#</th>
                                <th width="12%"><b>AMO Holder</b></th>
                                <th width="11%"><b>Amo Approval</b></th>
                                <th><b>Ratings/Capabilities</b></th>
                                <th><b>Maintenance Locations</b></th>
                                <th width="10%" class="center"><b>Expiry</b></th>
                                <th class="center"><b>Status</b></th>
                                <th width="12%" class="center"><b>APRVL PG & LEP</b></th>
                                <th><b>Days Left</b></th>
                                <th class="center"><b>Extention</b></th>
                            </tr>
                        </thead>';
                        $amobyexpiredstatus = localamo::ORDERBY('aoc_holder_id', 'ASC')->GET();
                        $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.local_amo_id, b.* FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
                        $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

                        $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
                        $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

                        $answer.='<tbody>';
                            if(count($amobyexpiredstatus)){
                                $count = 0;  
                                foreach($amobyexpiredstatus as $localAmo){
                                    $now = time();
                                    $due_date = strtotime($localAmo->expiry);;
                                    $datediff = $due_date - $now;
                                    $numberofdays = round($datediff / (60 * 60 * 24));

                                    $expiry = date('d/m/Y', $due_date);

                                    if($localAmo->extention !=''){
                                    $convertExtentionTotime = strtotime($localAmo->extention);
                                        $extention = date('d/m/Y', $convertExtentionTotime);
                                    }
                                    else{
                                        $extention = '';
                                    }

                                    if($numberofdays <=0){
                                        $count++;
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $status = "Expired";
                                        $bgcolor = "red";
                                        $color = "#000";

                                        $answer.='<tr class='.$css_style.' style="font-family:tahoma; font-size:11px;">
                                            <td style="font-size:11px;">'.$count.'</td>
                                            <td>'.strtoupper($localAmo->aoc_holder_id).'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                                    '.$localAmo->amo_approval_number.'
                                                </a>
                                            </td>
                                            <td>
                                                <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                                foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                                    if($aircraftMaker->local_amo_id == $localAmo->id){
                                                    $answer.='<li style="color:green; text-decoration:underline">
                                                            '.$aircraftMaker->aircraft_maker.':
                                                            <ul style="padding:0; margin:0; font-size:11px;">';
                                                                foreach($aircraftTypeList as $aircraftType){
                                                                        if($aircraftMaker->local_amo_id == $localAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                                        $answer.='<li style=\'display:inline-block; color:#333\'>
                                                                            '.$aircraftType->aircraft_type.',</li>';
                                                                    }
                                                                }
                                                            $answer.='</ul>
                                                        </li>';
                                                    }
                                                }
                                            $answer.='</ul>
                                            </td>
                                            <td>'.$localAmo->maintenance_locations.'</td>
                                            <td style="line-height:18px;" class="center">'.$expiry.'</td>
                                            <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                        '.$localAmo->amo_pm_aprvl_pg_lep.';
                                                    </a>
                                                
                                            </td>
                                            <td>'.$numberofdays.'</td>
                                            <td class="center">'.$extention.'</td>
                                        </tr>';
                                    }
                                }                  
                            }
                            else{
                                $answer.='<tr>
                                    <td class="table-danger" colspan="11" style="font-size:11px;">No local AMO has been added yet.</td>
                                </tr>';
                            }
                            
                        $answer.='</tbody>
                </table>';

            return $answer;
        }
        return redirect()->route('login');       
    }

    public function getlocalamobyexpiringstatus(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered" id="exportTableData">
                        <thead>
                            <tr class="table-warning" style="border-top:1px solid #ccc">
                                <th>#</th>
                                <th width="12%"><b>AMO Holder</b></th>
                                <th width="11%"><b>Amo Approval</b></th>
                                <th><b>Ratings/Capabilities</b></th>
                                <th><b>Maintenance Locations</b></th>
                                <th width="10%" class="center"><b>Expiry</b></th>
                                <th class="center"><b>Status</b></th>
                                <th width="12%" class="center"><b>APRVL PG & LEP</b></th>
                                <th><b>Days Left</b></th>
                                <th class="center"><b>Extention</b></th>
                            </tr>
                        </thead>';
                        $amobyexpiredstatus = localamo::ORDERBY('aoc_holder_id', 'ASC')->GET();
                        $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.local_amo_id, b.* FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
                        $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

                        $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_local_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
                        $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

                        $answer.='<tbody>';
                            if(count($amobyexpiredstatus)){
                                $count = 0;  
                                foreach($amobyexpiredstatus as $localAmo){
                                    $now = time();
                                    $due_date = strtotime($localAmo->expiry);;
                                    $datediff = $due_date - $now;
                                    $numberofdays = round($datediff / (60 * 60 * 24));

                                    $expiry = date('d/m/Y', $due_date);

                                    if($localAmo->extention !=''){
                                        $convertExtentionTotime = strtotime($localAmo->extention);
                                            $extention = date('d/m/Y', $convertExtentionTotime);
                                        }
                                        else{
                                            $extention = '';
                                        }

                                    if($numberofdays >=1 && $numberofdays <= 89){
                                        $count++;
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $status = "Expiring Soon";
                                        $bgcolor = "#ffbf00";
                                        $color = "#000";

                                        $answer.='<tr class='.$css_style.' style="font-family:tahoma; font-size:11px;">
                                            <td style="font-size:11px;">'.$count.'</td>
                                            <td>'.strtoupper($localAmo->aoc_holder_id).'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                                    '.$localAmo->amo_approval_number.'
                                                </a>
                                            </td>
                                            <td>
                                                <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                                foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                                    if($aircraftMaker->local_amo_id == $localAmo->id){
                                                    $answer.='<li style="color:green; text-decoration:underline">
                                                            '.$aircraftMaker->aircraft_maker.':
                                                            <ul style="padding:0; margin:0; font-size:11px;">';
                                                                foreach($aircraftTypeList as $aircraftType){
                                                                        if($aircraftMaker->local_amo_id == $localAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                                        $answer.='<li style=\'display:inline-block; color:#333\'>
                                                                            '.$aircraftType->aircraft_type.',</li>';
                                                                    }
                                                                }
                                                            $answer.='</ul>
                                                        </li>';
                                                    }
                                                }
                                            $answer.='</ul>
                                            </td>
                                            <td>'.$localAmo->maintenance_locations.'</td>
                                            <td style="font-size:11px; line-height:18px; text-align:center">'.$expiry.'</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center; background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                        '.$localAmo->amo_pm_aprvl_pg_lep.';
                                                    </a>
                                                
                                            </td>
                                            <td>'.$numberofdays.'</td>
                                            <td class="center">'.$extention.'</td>
                                        </tr>';
                                    }
                                }                  
                            }
                            else{
                                $answer.='<tr>
                                    <td class="table-danger" colspan="11" style="font-size:11px;">No record found.</td>
                                </tr>';
                            }
                        $answer.='</tbody>
                    </table>';

            return $answer;
        }
        return redirect()->route('login');
    }

    public function destroy($id)
    {
        if(Auth::check() && Auth::user()->role)
        {
            $recid = localamo::findOrFail($id);
            $recid->DELETE();
            updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'amo-local', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->amo_approval_number
            ]);
            return 'deleted';
        }
        return redirect()->route('login');
    }
}
