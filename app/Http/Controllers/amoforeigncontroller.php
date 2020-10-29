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
use App\aircraftMaker;
use App\foreignAmoHolder;

class amoforeigncontroller extends Controller
{
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $countrylists = DB::SELECT(DB::RAW('SELECT regional_country_id, country FROM tbl_regional_country ORDER BY country ASC'));
            $amoforeignlists = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id ORDER BY expiry DESC'));

            $aircraftMakerList = aircraftMaker::ORDERBY('aircraft_maker', 'ASC')->GET();

            $foreignamoholderlist = foreignAmoHolder::ORDERBY('foreign_amo_holder', 'ASC')->GET();

            return view('v1.ncaa.amo.foreign.create', compact(
                'countrylists', 
                'amoforeignlists', 
                'aircraftMakerList', 
                'foreignamoholderlist'
            ));
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
                    return 'saved`'.$id;
                }
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
            $amoforeignlists = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id ORDER BY expiry DESC'));
             $foreignamoholderlist = foreignAmoHolder::ORDERBY('foreign_amo_holder', 'ASC')->GET();

            return view('v1.ncaa.amo.foreign.edit', compact('countrylists', 'amoforeignlists', 'recid', 'foreignamoholderlist'));    
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
                return 'updated`'.$id;
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function viewall(){
        if(Auth::check() && Auth::user()->role){
            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id ORDER BY foreign_amo_holder ASC'));
            $checkforamoforeignlastupdate = updateHistory::WHERE('module', 'amo-foreign')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

            // $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.foreign_amo_id, b.* FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
            // $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

            // $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
            // $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));
            $ratingsAndCap = DB::SELECT(
                DB::RAW(
                    'SELECT a.*, b.aircraft_maker, c.aircraft_type FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b JOIN tbl_ncaa_aircraft_types c ON a.aircraft_maker_id = b.id AND a.aircraft_type_id = c.id'
                )
            );

            return view('v1.ncaa.amo.foreign.show', compact(
                'allforAmos', 
                'checkforamoforeignlastupdate', 
                'ratingsAndCap'
            ));
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
                    <th><b>#</b></th>
                    <th><b>AMO Holder</b></th>
                    <th><b>Country</b></th>
                    <th><b>MOE Reference</b></th>
                    <th class="center"><b>Approvals</b></th>
                    <th><b>Ratings/Capabilities</b></th>
                    <th class="center"><b>AMO Number</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b>Days Left</b></th>
                    <th class="center"><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';

            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id'));

            $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.foreign_amo_id, b.* FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
            $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));
    
            $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
            $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

            if(count($allforAmos)){
                $count = 0; 
                foreach($allforAmos as $foreignAmo){
                    $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                    $now = time();
                    $due_date = strtotime($foreignAmo->expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));
                    $expiry = date('d/m/Y', $due_date);

                    if($numberofdays > 90 ){
                        $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $status = "Active";
                        $bgcolor = "green";
                        $color = "#fff";
                        $answer.='<tr class='.$css_style.' >
                            <td>'.$count.'</td>
                            <td>'.$foreignAmo->foreign_amo_holder.'</td>
                            <td>'.$foreignAmo->country.'</td>
                            <td>'.$moe.'</td>
                            <td>'.$foreignAmo->approvals.'</td>
                            <td style="line-height:18px;">
                                <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                    foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                        if($aircraftMaker->foreign_amo_id == $foreignAmo->id){
                                            $answer.='<li style="color:green; text-decoration:underline">
                                                '.$aircraftMaker->aircraft_maker.':
                                                <ul style="padding:0; margin:0; font-size:11px;">';
                                                    foreach($aircraftTypeList as $aircraftType){
                                                    if($aircraftMaker->foreign_amo_id == $foreignAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                    $answer.='<li style="display:inline-block; color:#333">'.$aircraftType->aircraft_type .',</li>';
                                                    }
                                                    }
                                                $answer.='</ul>
                                            </li>';
                                        }
                                    }
                                    $answer.='</ul>
                            </td>
                            <td class="center">
                                <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                        '.$foreignAmo->amo_number.'
                                    </a>
                            </td>
                            <td>'.$expiry.'</td>
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
                    <th><b>#</b></th>
                    <th><b>AMO Holder</b></th>
                    <th><b>Country</b></th>
                    <th><b>MOE Reference</b></th>
                    <th class="center"><b>Approvals</b></th>
                    <th><b>Ratings/Capabilities</b></th>
                    <th class="center"><b>AMO Number</b></th>
                    <th width="10%" class="center"><b>Expiry</b></th>
                    <th><b>Days Left</b></th>
                    <th class="center"><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';

            $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id'));

            $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.foreign_amo_id, b.* FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
            $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));
    
            $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
            $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));

            if(count($allforAmos)){
                $count = 0; 
                foreach($allforAmos as $foreignAmo){
                    $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                    $now = time();
                    $due_date = strtotime($foreignAmo->expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));
                    $expiry = date('d/m/Y', $due_date);

                    if($numberofdays <= 0){
                        $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $status = "Expired";
                        $bgcolor = "red";
                        $color = "#000";
                        $answer.='<tr class='.$css_style.'>
                            <td style="font-size:11px;">'.$count.'</td>
                            <td>'.$foreignAmo->foreign_amo_holder.'</td>
                            <td>'.$foreignAmo->country.'</td>
                            <td>'.$moe.'</td>
                            <td>'.$foreignAmo->approvals.'</td>
                            <td style="line-height:18px;">
                                <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                    foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                        if($aircraftMaker->foreign_amo_id == $foreignAmo->id){
                                            $answer.='<li style="color:green; text-decoration:underline">
                                                '.$aircraftMaker->aircraft_maker.':
                                                <ul style="padding:0; margin:0; font-size:11px;">';
                                                    foreach($aircraftTypeList as $aircraftType){
                                                    if($aircraftMaker->foreign_amo_id == $foreignAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                    $answer.='<li style="display:inline-block; color:#333">'.$aircraftType->aircraft_type .',</li>';
                                                    }
                                                    }
                                                $answer.='</ul>
                                            </li>';
                                        }
                                    }
                                    $answer.='</ul>
                            </td>
                            <td class="center">
                                <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                        '.$foreignAmo->amo_number.'
                                    </a>
                            </td>
                            <td>'.$expiry.'</td>
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
                            <th><b>#</b></th>
                            <th><b>AMO Holder</b></th>
                            <th><b>Country</b></th>
                            <th><b>MOE Reference</b></th>
                            <th class="center"><b>Approvals</b></th>
                            <th><b>Ratings/Capabilities</b></th>
                            <th class="center"><b>AMO Number</b></th>
                            <th width="10%" class="center"><b>Expiry</b></th>
                            <th><b>Days Left</b></th>
                            <th class="center"><b>Status</b></th>
                        </tr>
                    </thead>
                    <tbody>';

                    $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id'));

                    $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.foreign_amo_id, b.* FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
                    $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

                    $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
                    $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));
                    
                    if(count($allforAmos)){
                        $count = 0; 
                        foreach($allforAmos as $foreignAmo){
                            $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;
                            $now = time();
                            $due_date = strtotime($foreignAmo->expiry);;
                            $datediff = $due_date - $now;
                            $numberofdays = round($datediff / (60 * 60 * 24));
                            $expiry = date('d/m/Y', $due_date);

                            if($numberofdays >=1 && $numberofdays <=89){
                                $count++; 
                                $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                $status = "Expiring Soon";
                                $bgcolor = "#ffbf00";
                                $color = "#000";
                                $answer.='<tr class='.$css_style.' style="font-family:tahoma;">
                                    <td style="font-size:11px;">'.$count.'</td>
                                    <td>'.$foreignAmo->foreign_amo_holder.'</td>
                                    <td>'.$foreignAmo->country.'</td>
                                    <td>'.$moe.'</td>
                                    <td>'.$foreignAmo->approvals.'</td>
                                    <td style="line-height:18px;">
                                        <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                        foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                            if($aircraftMaker->foreign_amo_id == $foreignAmo->id){
                                                $answer.='<li style="color:green; text-decoration:underline">
                                                    '.$aircraftMaker->aircraft_maker.':
                                                    <ul style="padding:0; margin:0; font-size:11px;">';
                                                        foreach($aircraftTypeList as $aircraftType){
                                                        if($aircraftMaker->foreign_amo_id == $foreignAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                        $answer.='<li style="display:inline-block; color:#333">'.$aircraftType->aircraft_type .',</li>';
                                                        }
                                                        }
                                                    $answer.='</ul>
                                                </li>';
                                            }
                                        }
                                        $answer.='</ul>
                                    </td>
                                    <td class="center">
                                        <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                                '.$foreignAmo->amo_number.'
                                            </a>
                                    </td>
                                    <td>'.$expiry.'</td>
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

    public function getsorts(Request $request) {
        $orderby_value = $request->criteria;
        $direction = $request->direction;

        $allforAmos = DB::SELECT(DB::RAW('SELECT a.*, b.foreign_amo_holder, c.country FROM tbl_ncaa_foreign_amos a JOIN tbl_foreign_amo_holders b JOIN tbl_regional_country c ON  a.amo_holder = b.id AND a.regional_country_id = c.regional_country_id ORDER BY '.$orderby_value.' '.$direction.' '));

        $aircraftMakerRatingsQuery = 'SELECT  DISTINCT a.aircraft_maker_id, a.foreign_amo_id, b.* FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id';
        $aircraftMakerRatingsLists = DB::SELECT(DB::RAW($aircraftMakerRatingsQuery));

        $aircraftTypeQuery = 'SELECT * FROM tbl_ncaa_foreign_amo_ratings a JOIN tbl_ncaa_aircraft_types b ON a.aircraft_type_id = b.id';
        $aircraftTypeList = DB::SELECT(DB::RAW($aircraftTypeQuery));


        $answer = '<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th><b>#</b></th>
                    <th><b>AMO Holder</b></th>
                    <th><b>Country</b></th>
                    <th><b>MOE Reference</b></th>
                    <th class="center"><b>Approvals</b></th>
                    <th><b>Ratings/Capabilities</b></th>
                    <th class="center"><b>AMO Number</b></th>
                    <th width = "10%" class="center"><b>Expiry</b></th>
                    <th><b>Days Left</b></th>
                    <th class="center"><b>Status</b></th>
                </tr>
            </thead>
            <tbody>';
                if(count($allforAmos)){
                    $count = 0; 
                    foreach($allforAmos as $foreignAmo){
                        $count++; 
                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;

                        $amo = '<a href="">'.$foreignAmo->amo_number.'</a>';
                        $now = time();
                        $due_date = strtotime($foreignAmo->expiry);;
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
                    
                        $expiry = date('d/m/Y', $due_date);
                        
                        $answer.='<tr class='.$css_style.'>
                        <td>'.$count.'</td>
                        <td>'.strtoupper($foreignAmo->foreign_amo_holder).'</td>
                        <td>'.$foreignAmo->country.'</td>
                        <td>'.$moe.'</td>
                        <td>'.$foreignAmo->approvals.'</td>
                        <td style="line-height:18px;">
                            <ul style="padding:0; margin:0; font-size:11px; list-style:none;">';
                                foreach($aircraftMakerRatingsLists as $aircraftMaker){
                                    if($aircraftMaker->foreign_amo_id == $foreignAmo->id){
                                        $answer.='<li style="color:green; text-decoration:underline">
                                            '.$aircraftMaker->aircraft_maker.':
                                            <ul style="padding:0; margin:0; font-size:11px;">';
                                                foreach($aircraftTypeList as $aircraftType){
                                                if($aircraftMaker->foreign_amo_id == $foreignAmo->id && $aircraftType->aircraft_maker_id == $aircraftMaker->id){
                                                $answer.='<li style="display:inline-block; color:#333">'.$aircraftType->aircraft_type .',</li>';
                                                }
                                                }
                                            $answer.='</ul>
                                        </li>';
                                    }
                                }
                                $answer.='</ul>
                        </td>
                        <td class="center">
                            <a href="/confidentials/amo/foreign/'.$foreignAmo->amo.'" target="_blank">
                                    '.$foreignAmo->amo_number.'
                                </a>
                        </td>
                        <td>'.$expiry.'</td>
                        <td>'.$numberofdays.'</td>
                        <td style="background:'.$bgcolor.'; color:'.$color.'" class="center">'.$status.'</td>
                        </tr>';
                    }
                }
                else{
                    $answer.='<tr>
                    <td class="table-danger" colspan="11">No foreign AMO has been added yet.</td>
                </tr>';
                }
            $answer.='</tbody>
        </table>';

        return $answer;
    }

    public function destroy($id)
    {
        if(Auth::check() && Auth::user()->role)
        {
            $recid = foreignamo::findOrFail($id);
            $recid->DELETE();
            return 'deleted';
        }
        return redirect()->route('login');
    }

}
