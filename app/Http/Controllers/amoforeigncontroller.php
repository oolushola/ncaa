<?php

namespace App\Http\Controllers;
use App\foreignamo;
use App\Http\Requests;
use App\Http\Requests\foreignAmoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;

class amoforeigncontroller extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $countrylists = DB::SELECT(DB::RAW('SELECT regional_country_id, country FROM tbl_regional_country ORDER BY country ASC'));
            $amoforeignlists = DB::SELECT(DB::RAW('SELECT a.id, a.amo_holder, a.moe_reference, a.expiry, a.created_by, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id
            ORDER BY a.expiry DESC LIMIT 12'));
            return view('v1.ncaa.amo.foreign.create', compact('countrylists', 'amoforeignlists'));
        }
        else{
            return redirect()->route('login');
        }
    }
    public function store(foreignAmoRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = foreignamo::WHERE('amo_holder', $request->get('amo_holder'))->WHERE('regional_country_id', $request->get('regional_country_id'))->exists();
            if($check){
                return 'exists';
            }
            else{
                $foreignamo = foreignamo::CREATE($request->all());
                $id = $foreignamo->id;
    			$recid=foreignamo::findOrFail($id);
        		    if($request->hasFile('amo')){
        			$amo = $request->file('amo');
        			$name = "amo-foreign-".str_slug($request->get('expiry'))."-".str_slug($request->get('regional_country_id')).'.'.$amo->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/amo/foreign/');
        			$path = $destination_path."/".$name;
        			$amo->move($destination_path, $name);
        			$recid->amo = $name;
        			$recid->save();
                }
                return 'saved';
            }
        }
        else{
            return redirect()->route('login');
        }
    }
    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $recid = foreignamo::findOrFail(base64_decode($id));
            $countrylists = DB::SELECT(DB::RAW('SELECT regional_country_id, country FROM tbl_regional_country ORDER BY country ASC'));
            $amoforeignlists = DB::SELECT(DB::RAW('SELECT a.id, a.amo_holder, a.moe_reference, a.expiry, a.created_by, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id
             ORDER BY a.expiry DESC LIMIT 12 '));
            return view('v1.ncaa.amo.foreign.edit', compact('countrylists', 'amoforeignlists', 'recid'));    
        }
        else{
            return redirect()->route('login');
        }
    }
    public function update(foreignAmoRequest $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = foreignamo::WHERE('amo_holder', $request->get('amo_holder'))->WHERE('regional_country_id', $request->get('regional_country_id'))->WHERE('id', '<>', $id)->exists();
            if($check){
                return 'exists';
            }
            else{
                $recid = foreignamo::findOrFail($id);
                $recid->UPDATE($request->all());
        		    if($request->hasFile('amo')){
        			$amo = $request->file('amo');
        			$name = "amo-foreign-".str_slug($request->get('expiry'))."-".str_slug($request->get('regional_country_id')).'.'.$amo->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/amo/foreign/');
        			$path = $destination_path."/".$name;
        			$amo->move($destination_path, $name);
        			$recid->amo = $name;
        			$recid->save();
                }
                updateHistory::CREATE($request->all());
                return 'updated';
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id'));
            $checkforamoforeignlastupdate = updateHistory::WHERE('module', 'amo-foreign')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

            return view('v1.ncaa.amo.foreign.show', compact('allforAmos', 'checkforamoforeignlastupdate'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function getforeignamobyactivestatus(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b>#</th>
                    <th><b>AMO Holder</b></th>
                    <th><b>Country</b></th>
                    <th><b>MOE Reference</b></th>
                    <th class="center"><b>Approvals</b></th>
                    <th><b>Ratings/Capabilities</b></th>
                    <th class="center"><b>AMO Number</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b></b>Days Left</th>
                    <th class="center"><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';

            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id'));

            if(count($allforAmos)){
                $count = 0; 
                foreach($allforAmos as $foreignAmo){
                    $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                    $now = time();
                    $due_date = strtotime($foreignAmo->expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $status = "Active";
                        $bgcolor = "green";
                        $color = "#fff";
                        $answer.='<tr class='.$css_style.' >
                            <td>'.$count.'</td>
                            <td>'.$foreignAmo->amo_holder.'</td>
                            <td>'.$foreignAmo->country.'</td>
                            <td>'.$moe.'</td>
                            <td>'.$foreignAmo->approvals.'</td>
                            <td style="line-height:18px;">'.$foreignAmo->ratings_capabilities.'
                            </td>
                            <td class="center">
                                <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                        '.$foreignAmo->amo_number.'
                                    </a>
                            </td>
                            <td>'.$foreignAmo->expiry.'</td>
                            <td>'.$numberofdays.'</td>
                            <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                        </tr>';
                    }
                }
            }
            else{
                $answer.='<tr>
                <td class="table-danger" colspan="11" style="font-size:11px;">No record found</td>
            </tr>';
            }
        $answer.='</tbody>
        </table>';

        return $answer;
        }
        else{
            return redirect()->route('login');
        }
    }
    

    public function getforeignamobyexpiredstatus(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b>#</th>
                    <th><b>AMO Holder</b></th>
                    <th><b>Country</b></th>
                    <th><b>MOE Reference</b></th>
                    <th class="center"><b>Approvals</b></th>
                    <th><b>Ratings/Capabilities</b></th>
                    <th class="center"><b>AMO Number</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b></b>Days Left</th>
                    <th class="center"><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';

            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id'));

            if(count($allforAmos)){
                $count = 0; 
                foreach($allforAmos as $foreignAmo){
                    $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                    $now = time();
                    $due_date = strtotime($foreignAmo->expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <= 0){
                        $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $status = "Expired";
                        $bgcolor = "red";
                        $color = "#000";
                        $answer.='<tr class='.$css_style.'>
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'.$foreignAmo->amo_holder.'</td>
                            <td>'.$foreignAmo->country.'</td>
                            <td>'.$moe.'</td>
                            <td>'.$foreignAmo->approvals.'</td>
                            <td style="line-height:18px;">'.$foreignAmo->ratings_capabilities.'
                            </td>
                            <td class="center">
                                <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                        '.$foreignAmo->amo_number.'
                                    </a>
                            </td>
                            <td>'.$foreignAmo->expiry.'</td>
                            <td>'.$numberofdays.'</td>
                            <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                        </tr>';
                    }
                }
            }
            else{
                $answer.='<tr>
                <td class="table-danger" colspan="11" style="font-size:11px;">No record found</td>
            </tr>';
            }
        $answer.='</tbody>
        </table>';

        return $answer;    
        }
        else{
            return redirect()->route('login');
        }
    }

    public function getforeignamobyexpiringstatus(Request $request){
        if(Auth::check() && Auth::user()->role){
            $answer = '<table class="table table-bordered">
                    <thead>
                        <tr class="table-warning" style="border-top:1px solid #ccc">
                            <th><b>#</b>#</th>
                            <th><b>AMO Holder</b></th>
                            <th><b>Country</b></th>
                            <th><b>MOE Reference</b></th>
                            <th class="center"><b>Approvals</b></th>
                            <th><b>Ratings/Capabilities</b></th>
                            <th class="center"><b>AMO Number</b></th>
                            <th width="10%" class="center"><b>Expiry</b></th>
                            <th><b></b>Days Left</th>
                            <th class="center"><b>Status</b></th>
                        </tr>
                    </thead>
                    <tbody>';

                    $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.country FROM tbl_ncaa_foreign_amos a JOIN tbl_regional_country b ON a.regional_country_id = b.regional_country_id'));

                    if(count($allforAmos)){
                        $count = 0; 
                        foreach($allforAmos as $foreignAmo){
                            $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                            $now = time();
                            $due_date = strtotime($foreignAmo->expiry);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));

                            if($numberofdays >=1 && $numberofdays <=89){
                                $count++; 
                                $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                $status = "Expiring Soon";
                                $bgcolor = "#ffbf00";
                                $color = "#000";
                                $answer.='<tr class='.$css_style.' style="font-family:tahoma;">
                                    <td style="font-size:11px;">'.$count.'</td>
                                    <td>'.$foreignAmo->amo_holder.'</td>
                                    <td>'.$foreignAmo->country.'</td>
                                    <td>'.$moe.'</td>
                                    <td>'.$foreignAmo->approvals.'</td>
                                    <td style="line-height:18px;">'.$foreignAmo->ratings_capabilities.'
                                    </td>
                                    <td class="center">
                                        <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                                '.$foreignAmo->amo_number.'
                                            </a>
                                    </td>
                                    <td>'.$foreignAmo->expiry.'</td>
                                    <td>'.$numberofdays.'</td>
                                    <td style="font-size:11px; line-height:15px; text-align:center; background:'.$bgcolor.'; color:'.$color.' ">'.$status.'</td>
                                </tr>';
                            }
                        }
                    }
                    else{
                        $answer.='<tr>
                        <td class="table-danger" colspan="11" style="font-size:11px;">No record found</td>
                    </tr>';
                    }
            $answer.='</tbody>
            </table>';

            return $answer; 
        }
        else{
            return redirect()->route('login');
        }   
    }

}
