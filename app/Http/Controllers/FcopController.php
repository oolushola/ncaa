<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\Fcop;
use Illuminate\Support\Facades\DB;

class FcopController extends Controller
{
    public function index() 
    {
        $fcopListings = Fcop::ORDERBY('licence_no')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.fcop.create', compact('fcopListings'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'foreign_airline' => 'required | string',
            'licence_no' => 'required|string',
            'fcop_certificate' => 'required|max:2048',
            'part_18' => 'required|boolean',
            'part_10' => 'required|boolean',
            'part_17' => 'required|boolean',
            'fcop_status' => 'required|boolean',
            'date_fcop_issued' => 'required|string',
            'comments' => 'required|string'
        ]);

        $check = Fcop::WHERE('licence_no', $request->licence_no)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = Fcop::CREATE($request->all());
            $id = $recid->id;
            if($request->hasFile('fcop_certificate')){
                $licenceNoCertificate = $request->file('fcop_certificate');
                $name = "fcop/".str_slug($request->licence_no).'.'.$licenceNoCertificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/fcop');
                $fcopPath = $destination_path."/".$name;
                $licenceNoCertificate->move($destination_path, $name);
                $recid->fcop_certificate = $name;
                $recid->save();
            }
            return 'saved';
        }
    }

    public function edit($id) {
        $recid = Fcop::findOrFail($id);
        $fcopListings = Fcop::ORDERBY('licence_no')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.fcop.create', compact('recid', 'fcopListings', 'recid')); 
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'foreign_airline' => 'required | string',
            'licence_no' => 'required|string',
            'part_18' => 'required|boolean',
            'part_10' => 'required|boolean',
            'part_17' => 'required|boolean',
            'fcop_status' => 'required|boolean',
            'date_fcop_issued' => 'required|string',
        ]);

        if($request->hasFile('fcop_certificate')) {
            $this->validate($request, [
                'fcop_certificate' => 'required|max:2048',
            ]);
        }

        $check = Fcop::WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = Fcop::findOrFail($id);
            $recid->UPDATE($request->all());
            if($request->hasFile('fcop_certificate')){
                $licenceNoCertificate = $request->file('fcop_certificate');
                $name = "fcop/".str_slug($request->licence_no).'.'.$licenceNoCertificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/fcop');
                $fcopPath = $destination_path."/".$name;
                $licenceNoCertificate->move($destination_path, $name);
                $recid->fcop_certificate = $name;
                $recid->save();
            }
            updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'fcop', 'record_id' => $id, 'actual' => $recid->licence_no
            ]);
            return 'updated';
        }
    }

    public function show() 
    {
        $fcopListings = Fcop::GET();
        $checkforfcopupdates = updateHistory::WHERE('module', 'fcop')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        return view('v1.ncaa.economics-licence.fcop.show', compact('fcopListings', 'checkforfcopupdates'));
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
        $recid = Fcop::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'fcop', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->licence_no
        ]);
        return 'deleted';
    }
}
