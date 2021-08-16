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
                    $this->uploader($request, $request->dacl_no, 'aoc_opspec', 'aoc-ops-spec/', 'confidentials/foreign-airline/ops-spec',  $recid, 'aoc_opspec');
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
        $dacllistings = ForeignAirlineDacl::GET();
        $checkfordaclupdates = updateHistory::WHERE('module', 'foreign-airline-dacl')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        return view('v1.ncaa.dacl.show', compact('dacllistings', 'checkfordaclupdates'));
    }

    public function showByOperator(Request $request) {
        $orderby = $request->direction;
        $atoListings = DB::SELECT(
            DB::RAW(
                'SELECT a.*, b.training_organization FROM tbl_ncaa_atos a JOIN `tbl_ncaa_training_organizations` b ON a.training_organization_id = b.id ORDER BY training_organization '.$orderby.' '  
            )
        );

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Training Organization</b></th>
                <th><b>Approval No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($atoListings)) {
            $counter = 0;
                foreach($atoListings as $ato){
                    $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($ato->date_of_expiry);;
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
                    $issuedDate = strtotime($ato->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($ato->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($ato->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($ato->training_organization).'</td>
                    <td>
                        <a href="confidentials/economic-licence/'.$ato->ato_certificate.'" target="_blank">
                            '.$ato->approval_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                </tr>';
                }
            } else {
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
        </table>';


        return $answer;

    }

    public function activeAto(Request $request) {
        $atoListings = DB::SELECT(
            DB::RAW(
                'SELECT a.*, b.training_organization FROM tbl_ncaa_atos a JOIN `tbl_ncaa_training_organizations` b ON a.training_organization_id = b.id ORDER BY training_organization ASC '  
            )
        );

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Training Organization</b></th>
                <th><b>Approval No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($atoListings)) {
            $counter = 0;
                foreach($atoListings as $ato){
                     
                    $now = time();
                    $due_date = strtotime($ato->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));
                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";

                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                   
                    $issuedDate = strtotime($ato->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($ato->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($ato->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($ato->training_organization).'</td>
                    <td>
                        <a href="confidentials/economic-licence/'.$ato->ato_certificate.'" target="_blank">
                            '.$ato->approval_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                </tr>';
                    }
                }
            } else {
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
        </table>';


        return $answer;
    }

    public function expiredAto(Request $request) {
        $atoListings = DB::SELECT(
            DB::RAW(
                'SELECT a.*, b.training_organization FROM tbl_ncaa_atos a JOIN `tbl_ncaa_training_organizations` b ON a.training_organization_id = b.id ORDER BY training_organization ASC '  
            )
        );

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Training Organization</b></th>
                <th><b>Approval No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($atoListings)) {
            $counter = 0;
                foreach($atoListings as $ato){
                     
                    $now = time();
                    $due_date = strtotime($ato->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <= 0 ){
                        $bgcolor = "red";
                        $color = "#fff";
                        $remarks = "Expired";

                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                   
                    $issuedDate = strtotime($ato->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($ato->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($ato->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($ato->training_organization).'</td>
                    <td>
                        <a href="confidentials/economic-licence/'.$ato->ato_certificate.'" target="_blank">
                            '.$ato->approval_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                </tr>';
                    }
                }
            } else {
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
        </table>';


        return $answer;

    }

    public function expiringSoonAto(Request $request) {
        $atoListings = DB::SELECT(
            DB::RAW(
                'SELECT a.*, b.training_organization FROM tbl_ncaa_atos a JOIN `tbl_ncaa_training_organizations` b ON a.training_organization_id = b.id ORDER BY training_organization ASC '  
            )
        );

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Training Organization</b></th>
                <th><b>Approval No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($atoListings)) {
            $counter = 0;
                foreach($atoListings as $ato){
                     
                    $now = time();
                    $due_date = strtotime($ato->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0) && ($numberofdays <= 90) ){
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = "Expiring Soon";

                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                   
                    $issuedDate = strtotime($ato->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($ato->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($ato->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($ato->training_organization).'</td>
                    <td>
                        <a href="confidentials/economic-licence/'.$ato->ato_certificate.'" target="_blank">
                            '.$ato->approval_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                </tr>';
                    }
                }
            } else {
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
        </table>';


        return $answer;

    }

    public function destroy(Request $request, $id) {
        $recid = ato::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'ato', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->approval_no
        ]);
        return 'deleted';
    }
}