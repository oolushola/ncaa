<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\foreignAirline;
use App\ForeignAirlineDacl;
use App\trainingOrganization;
use Illuminate\Support\Facades\DB;

class ForeignAirlineDaclController extends Controller
{
    public function index() 
    {
        if (Auth::check()) {
            $countries = DB::SELECT(
                DB::RAW(
                    'SELECT country FROM tbl_regional_country WHERE regional_country_id !="94" ORDER BY country ASC '
                )
            );
            $foreignAirlineListings = foreignAirline::ORDERBY('foreign_airline', 'ASC')->GET();
            $foreignAirlineDacllistings = ForeignAirlineDacl::PAGINATE(20);
            $lastDaclNo = ForeignAirlineDacl::SELECT('dacl_no')->LATEST()->FIRST();
            return view('v1.ncaa.dacl.create', compact('countries', 'foreignAirlineListings', 'foreignAirlineDacllistings', 'lastDaclNo'));
        }
        else {
            return redirect()->route('login');
        }
    }

    public function store(Request $request) {
        if(Auth::check()) {
            $this->validate($request, [
                'airline_name' => 'required|string',
                'dacl_no' => 'required|string',
                'dacl_issue_date' => 'required|string',
                'dacl_certificate' => 'required|max:2048',
                'aoc_opspec' => 'required|max:2048',
                'country' => 'required|string',
                'aoc_expiry_date' => 'required|string',
            ]);

            $check = ForeignAirlineDacl::WHERE('airline_name', $request->approval_no)->exists();
            if($check)
            {
                return 'exists';
            }   
            else {
                $recid = ForeignAirlineDacl::CREATE($request->all());
                $id = $recid->id;
                if($request->hasFile('dacl_certificate')){
                    $this->uploader($request, $request->dacl_no, 'dacl_certificate', 'dacl/', 'confidentials/foreign-airline/dacl',  $recid, 'dacl_certificate');
                }
                if($request->hasFile('aoc_opspec')){
                    $this->uploader($request, $request->dacl_no, 'aoc_opspec', 'ops-spec/', 'confidentials/foreign-airline/ops-spec',  $recid, 'aoc_opspec');
                }
                $recid->save();
                return 'saved';
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function uploader($request, $file, $fileName, $namePrefix, $dir, $obj, $saveTo) {
        $fileName = $request->file($fileName);
        $name = "$namePrefix".str_slug($file).'.'.$fileName->getClientOriginalExtension();
        $destination_path = public_path($dir);
        $path = $destination_path."/".$name;
        $fileName->move($destination_path, $name);
        $obj->$saveTo = $name;
    }

    public function edit($id) {
        if (Auth::check()) {
            $recid = ForeignAirlineDacl::findOrFail($id);
            $countries = DB::SELECT(
                DB::RAW(
                    'SELECT country FROM tbl_regional_country WHERE regional_country_id !="94" ORDER BY country ASC '
                )
            );
            $foreignAirlineListings = foreignAirline::ORDERBY('foreign_airline', 'ASC')->GET();
            $foreignAirlineDacllistings = ForeignAirlineDacl::PAGINATE(20);
            return view('v1.ncaa.dacl.create', compact( 'recid', 'foreignAirlineListings', 'foreignAirlineDacllistings', 'countries')); 
        }
        else {
            return redirect()->route('login');
        }
    }

    public function update(Request $request, $id) {
        if(Auth::check()) {
            $this->validate($request, [
                'airline_name' => 'required|string',
                'dacl_no' => 'required|string',
                'dacl_issue_date' => 'required|string',
                'country' => 'required|string',
                'aoc_expiry_date' => 'required|string',
            ]);

            if($request->hasFile('dacl_certificate')){
                $this->validate($request, [
                    'dacl_certificate' => 'required|max:2048',
                ]);
            }
            if($request->hasFile('aoc_opspec')){
                $this->validate($request, [
                    'aoc_opspec' => 'required|max:2048',
                ]);
            }

            $check = ForeignAirlineDacl::WHERE('airline_name', $request->airline_name)->WHERE('id', '<>', $id)->exists();
            if($check)
            {
                return 'exists';
            }   
            else {
                $recid = ForeignAirlineDacl::findOrFail($id);
                $recid->UPDATE($request->all());
                if($request->hasFile('dacl_certificate')){
                    $this->uploader($request, $request->dacl_no, 'dacl_certificate', 'dacl/', 'confidentials/foreign-airline/dacl', $recid, 'dacl_certificate');
                }
                if($request->hasFile('aoc_opspec')){
                    $this->uploader($request, $request->dacl_no, 'aoc_opspec', 'aoc-ops-spec/', 'confidentials/foreign-airline/ops-spec', $recid, 'aoc_opspec');
                }
                updateHistory::CREATE([
                    'name' => Auth::user()->name, 'module' => 'foreign-airline-dacl', 'record_id' => $id, 'actual' => $recid->dacl_no
                ]);
                $recid->save();
                return 'updated';
            }
        }
        return redirect()->route('login');
    }

    public function show() 
    {
        if(Auth::check()) {
        $dacllistings = ForeignAirlineDacl::GET();
        $checkfordaclupdates = updateHistory::WHERE('module', 'foreign-airline-dacl')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();
        $countries = DB::SELECT(
            DB::RAW(
                'SELECT * FROM tbl_regional_country ORDER BY country ASC'
            )
        );

        return view('v1.ncaa.dacl.show', compact('dacllistings', 'checkfordaclupdates', 'countries'));
        }
        return redirect()->route('login');
    }

    public function sortByCountry(Request $request) {
        $dacllistings = ForeignAirlineDacl::WHERE('country', $request->country)->GET();
        $record = '
        <table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Foreign Airline Name</b></th>
                <th><b>Dacl No.</b></th>
                <th class="text-center"><b>DACL Issue Date</b></th>
                <th class="center"><b>Foreign AOC & Ops Spec</b></th>
                <th class="center"><b>AOC Expiry Date</b></th>
                <th class="center"><b>Country</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($dacllistings)) {
            $counter = 0;
                foreach($dacllistings as $dacl) {
                    $counter++; 
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    $now = time();
                    $due_date = strtotime($dacl->aoc_expiry_date);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                    }
                    else if(($numberofdays >= 0) && ($numberofdays <=90)){
                       $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = "Expiring soon";
                    }
                    else{
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = "Expired";
                    }
                    $issuedDate = strtotime($dacl->dacl_issue_date); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $dateExpired = strtotime($dacl->aoc_expiry_date); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $record.='
                <tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($dacl->airline_name).'</td>
                    <td>
                        <a href="/confidentials/foreign-airline/'.$dacl->dacl_certificate.'" target="_blank">
                            '.$dacl->dacl_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td>
                        <a href="/confidentials/foreign-airline/'.$dacl->aoc_opspec.'" target="_blank">
                        <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$dacl->airline_name.'"></i>
                        </a>
                    </td>
                    <td class="center">'.$expired.'</td>
                    <td>'.$dacl->country.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$dacl->remarks.'</td>
                </tr>';
                }
            }   
            else {
                $record.='
                <tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        $record.='</tbody>
        </table>';
        return $record;
    }

    public function sortByStatus(Request $request) {
        if($request->status == 'active') {
            return $this->displayActiveRecords();
        }
        if($request->status == 'expiringSoon') {
            return $this->displayExpiringSoonRecords();
        }
        if($request->status == 'expired') {
            return $this->displayExpiredRecords();
        }
    }

    public function displayActiveRecords() {
        $dacllistings = ForeignAirlineDacl::GET();
        $record = '
        <table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Foreign Airline Name</b></th>
                <th><b>Dacl No.</b></th>
                <th class="text-center"><b>DACL Issue Date</b></th>
                <th class="center"><b>Foreign AOC & Ops Spec</b></th>
                <th class="center"><b>AOC Expiry Date</b></th>
                <th class="center"><b>Country</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($dacllistings)) {
            $counter = 0;
                foreach($dacllistings as $dacl) {
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    $now = time();
                    $due_date = strtotime($dacl->aoc_expiry_date);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $counter++;
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                   
                    $issuedDate = strtotime($dacl->dacl_issue_date); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $dateExpired = strtotime($dacl->aoc_expiry_date); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                    $record.='
                    <tr class="'.$css_style.'">
                        <td>'.$counter.'</td>
                        <td>'.strtoupper($dacl->airline_name).'</td>
                        <td>
                            <a href="/confidentials/foreign-airline/'.$dacl->dacl_certificate.'" target="_blank">
                                '.$dacl->dacl_no.'
                            </a>
                        </td>
                        <td class="text-center">'.$dateIssued.'</td>
                        <td>
                            <a href="/confidentials/foreign-airline/'.$dacl->aoc_opspec.'" target="_blank">
                            <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$dacl->airline_name.'"></i>
                            </a>
                        </td>
                        <td class="center">'.$expired.'</td>
                        <td>'.$dacl->country.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            '.$remarks.'
                        </td>
                        <td>'.$dacl->remarks.'</td>
                    </tr>';
                    }
                }
            }   
            else {
                $record.='
                <tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        $record.='</tbody>
        </table>';
        return $record;
    }

    public function displayExpiringSoonRecords() {
        $dacllistings = ForeignAirlineDacl::GET();
        $record = '
        <table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Foreign Airline Name</b></th>
                <th><b>Dacl No.</b></th>
                <th class="text-center"><b>DACL Issue Date</b></th>
                <th class="center"><b>Foreign AOC & Ops Spec</b></th>
                <th class="center"><b>AOC Expiry Date</b></th>
                <th class="center"><b>Country</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($dacllistings)) {
            $counter = 0;
                foreach($dacllistings as $dacl) {
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    $now = time();
                    $due_date = strtotime($dacl->aoc_expiry_date);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0) && ($numberofdays <=90)){
                       $counter++; 
                       $bgcolor = "#ffbf00";
                       $color = "#000";
                       $remarks = "Expiring soon";
                        $issuedDate = strtotime($dacl->dacl_issue_date); 
                        $dateIssued = date('d/m/Y', $issuedDate);
                        
                        $dateExpired = strtotime($dacl->aoc_expiry_date); 
                        $expired = date('d/m/Y', $dateExpired);
                        
                        $record.='
                        <tr class="'.$css_style.'">
                            <td>'.$counter.'</td>
                            <td>'.strtoupper($dacl->airline_name).'</td>
                            <td>
                                <a href="/confidentials/foreign-airline/'.$dacl->dacl_certificate.'" target="_blank">
                                    '.$dacl->dacl_no.'
                                </a>
                            </td>
                            <td class="text-center">'.$dateIssued.'</td>
                            <td>
                                <a href="/confidentials/foreign-airline/'.$dacl->aoc_opspec.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$dacl->airline_name.'"></i>
                                </a>
                            </td>
                            <td class="center">'.$expired.'</td>
                            <td>'.$dacl->country.'</td>
                            <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                                '.$remarks.'
                            </td>
                            <td>'.$dacl->remarks.'</td>
                        </tr>';
                    }
                }
            }   
            else {
                $record.='
                <tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        $record.='</tbody>
        </table>';
        return $record;
    }

    public function displayExpiredRecords() {
        $dacllistings = ForeignAirlineDacl::GET();
        $record = '
        <table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Foreign Airline Name</b></th>
                <th><b>Dacl No.</b></th>
                <th class="text-center"><b>DACL Issue Date</b></th>
                <th class="center"><b>Foreign AOC & Ops Spec</b></th>
                <th class="center"><b>AOC Expiry Date</b></th>
                <th class="center"><b>Country</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($dacllistings)) {
            $counter = 0;
                foreach($dacllistings as $dacl) {
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    $now = time();
                    $due_date = strtotime($dacl->aoc_expiry_date);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <= 0 ){
                        $counter++;
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = "Expired";
                        $issuedDate = strtotime($dacl->dacl_issue_date); 
                        $dateIssued = date('d/m/Y', $issuedDate);
                        
                        $dateExpired = strtotime($dacl->aoc_expiry_date); 
                        $expired = date('d/m/Y', $dateExpired);
                        
                        $record.='
                        <tr class="'.$css_style.'">
                            <td>'.$counter.'</td>
                            <td>'.strtoupper($dacl->airline_name).'</td>
                            <td>
                                <a href="/confidentials/foreign-airline/'.$dacl->dacl_certificate.'" target="_blank">
                                    '.$dacl->dacl_no.'
                                </a>
                            </td>
                            <td class="text-center">'.$dateIssued.'</td>
                            <td>
                                <a href="/confidentials/foreign-airline/'.$dacl->aoc_opspec.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$dacl->airline_name.'"></i>
                                </a>
                            </td>
                            <td class="center">'.$expired.'</td>
                            <td>'.$dacl->country.'</td>
                            <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                                '.$remarks.'
                            </td>
                            <td>'.$dacl->remarks.'</td>
                        </tr>';
                    }
                }
            }   
            else {
                $record.='
                <tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        $record.='</tbody>
        </table>';
        return $record;
    }
   
    public function destroy(Request $request, $id) {
        $recid = ForeignAirlineDacl::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'foreign-airline-dacl', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->dacl_no
        ]);
        return 'deleted';
    }
}