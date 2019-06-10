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


class amolocalcontroller extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aoclists = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
            $amolocals = DB::SELECT(DB::RAW('SELECT b.id, a.aoc_holder, b.amo_approval_number, a.created_by, b.expiry FROM tbl_ncaa_acos a JOIN tbl_ncaa_amo_locals b ON a.id = b.aoc_holder_id'));
            return view('v1.ncaa.amo.local.create', compact('aoclists', 'amolocals'));
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
                return 'saved';
            }
        }
        return redirect()->route('login');
    }
    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = localamo::findOrFail(base64_decode($id));
            $aoclists = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
            $amolocals = DB::SELECT(DB::RAW('SELECT b.id, a.aoc_holder, b.amo_approval_number, a.created_by, b.expiry FROM tbl_ncaa_acos a JOIN tbl_ncaa_amo_locals b ON a.id = b.aoc_holder_id'));
            return view('v1.ncaa.amo.local.edit', compact('aoclists', 'amolocals', 'recid'));
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
                return 'updated';
            }
        }
        return redirect()->route('login');    
    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $amolocalists = DB::SELECT(DB::RAW('SELECT a.*, b.aoc_holder FROM tbl_ncaa_amo_locals a JOIN tbl_ncaa_acos b ON b.id = a.aoc_holder_id ORDER BY aoc_holder ASC'));
            $checkforamolocalupdates = updateHistory::WHERE('module', 'amo-local')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();
            return view('v1.ncaa.amo.local.show', compact('amolocalists', 'checkforamolocalupdates'));
        }
        return redirect()->route('login');
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
                $amobyactivestatus = DB::SELECT(DB::RAW('SELECT a.*, b.aoc_holder FROM tbl_ncaa_amo_locals a JOIN tbl_ncaa_acos b ON b.id = a.aoc_holder_id ORDER BY aoc_holder ASC'));
                $answer.='<tbody>';
                    if(count($amobyactivestatus)){
                        $count = 0;  
                        foreach($amobyactivestatus as $localAmo){
                            $now = time();
                            $due_date = strtotime($localAmo->expiry);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));

                            if($numberofdays > 90 ){
                                $count++;
                                $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                $status = "Active";
                                $bgcolor = "green";
                                $color = "#fff";

                                $answer.='<tr class='.$css_style.'>
                                    <td style="font-size:11px;">'.$count.'</td>
                                    <td>'.strtoupper($localAmo->aoc_holder).'</td>
                                    <td>
                                        <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                            '.$localAmo->amo_approval_number.'
                                        </a>
                                    </td>
                                    <td>'.$localAmo->ratings_capabilities.'</td>
                                    <td>'.$localAmo->maintenance_locations.'</td>
                                    <td style="line-height:18px" class="center">'.$localAmo->expiry.'</td>
                                    <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                                    <td>
                                        <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                '.$localAmo->amo_pm_aprvl_pg_lep.';
                                            </a>
                                        
                                    </td>
                                    <td>'.$numberofdays.'</td>
                                    <td class="center">'.$localAmo->extention.'</td>
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
                        $amobyexpiredstatus = DB::SELECT(DB::RAW('SELECT a.*, b.aoc_holder FROM tbl_ncaa_amo_locals a JOIN tbl_ncaa_acos b ON b.id = a.aoc_holder_id ORDER BY aoc_holder ASC'));
                        $answer.='<tbody>';
                            if(count($amobyexpiredstatus)){
                                $count = 0;  
                                foreach($amobyexpiredstatus as $localAmo){
                                    $now = time();
                                    $due_date = strtotime($localAmo->expiry);;
                                    $datediff = $due_date - $now;
                                    $numberofdays = round($datediff / (60 * 60 * 24));

                                    if($numberofdays <=0  ){
                                        $count++;
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $status = "Expired";
                                        $bgcolor = "red";
                                        $color = "#000";

                                        $answer.='<tr class='.$css_style.' style="font-family:tahoma; font-size:11px;">
                                            <td style="font-size:11px;">'.$count.'</td>
                                            <td>'.strtoupper($localAmo->aoc_holder).'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                                    '.$localAmo->amo_approval_number.'
                                                </a>
                                            </td>
                                            <td>'.$localAmo->ratings_capabilities.'</td>
                                            <td>'.$localAmo->maintenance_locations.'</td>
                                            <td style="line-height:18px;" class="center">'.$localAmo->expiry.'</td>
                                            <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                        '.$localAmo->amo_pm_aprvl_pg_lep.';
                                                    </a>
                                                
                                            </td>
                                            <td>'.$numberofdays.'</td>
                                            <td class="center">'.$localAmo->extention.'</td>
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
                        $amobyexpiredstatus = DB::SELECT(DB::RAW('SELECT a.*, b.aoc_holder FROM tbl_ncaa_amo_locals a JOIN tbl_ncaa_acos b ON b.id = a.aoc_holder_id ORDER BY aoc_holder ASC'));
                        $answer.='<tbody>';
                            if(count($amobyexpiredstatus)){
                                $count = 0;  
                                foreach($amobyexpiredstatus as $localAmo){
                                    $now = time();
                                    $due_date = strtotime($localAmo->expiry);;
                                    $datediff = $due_date - $now;
                                    $numberofdays = round($datediff / (60 * 60 * 24));

                                    if($numberofdays >=1 && $numberofdays <= 89){
                                        $count++;
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $status = "Expiring Soon";
                                        $bgcolor = "#ffbf00";
                                        $color = "#000";

                                        $answer.='<tr class='.$css_style.' style="font-family:tahoma; font-size:11px;">
                                            <td style="font-size:11px;">'.$count.'</td>
                                            <td>'.strtoupper($localAmo->aoc_holder).'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'\')}}" target="_blank">
                                                    '.$localAmo->amo_approval_number.'
                                                </a>
                                            </td>
                                            <td>'.$localAmo->ratings_capabilities.'</td>
                                            <td>'.$localAmo->maintenance_locations.'</td>
                                            <td style="font-size:11px; line-height:18px; text-align:center">'.$localAmo->expiry.'</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center; background:'.$bgcolor.'; color:'.$color.'">'.$status.'</td>
                                            <td>
                                                <a href="/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'" target="_blank">
                                                        '.$localAmo->amo_pm_aprvl_pg_lep.';
                                                    </a>
                                                
                                            </td>
                                            <td>'.$numberofdays.'</td>
                                            <td class="center">'.$localAmo->extention.'</td>
                                        </tr>';
                                    }
                                }                  
                            }
                            else{
                                $answer.='<tr>
                                    <td class="table-danger" colspan="11" style="font-size:11px;">You do not have any soon to expiry Local AMO</td>
                                </tr>';
                            }
                        $answer.='</tbody>
                    </table>';

            return $answer;
        }
        return redirect()->route('login');
    }
}
