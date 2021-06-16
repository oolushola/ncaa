<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\updateHistory;
use App\ato;
use App\trainingOrganization;
use Illuminate\Support\Facades\DB;


class atoController extends Controller
{
    public function index() 
    {
        $trainingOrganizationListings = trainingOrganization::ORDERBY('training_organization', 'ASC')->GET();
        $atoListings = ato::ORDERBY('training_organization_id')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.ato.create', compact('atoListings', 'trainingOrganizationListings'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'training_organization_id' => 'required | integer',
            'approval_no' => 'required|string',
            'ato_certificate' => 'required|max:2048',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        $check = ato::WHERE('approval_no', $request->approval_no)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = ato::CREATE($request->all());
            $id = $recid->id;
            if($request->hasFile('ato_certificate')){
                $approvalNumberCerficate = $request->file('ato_certificate');
                $name = "ato/".str_slug($request->approval_no).'.'.$approvalNumberCerficate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/ato');
                $atoPath = $destination_path."/".$name;
                $approvalNumberCerficate->move($destination_path, $name);
                $recid->ato_certificate = $name;
                $recid->save();
            }
            return 'saved';
        }
    }

    public function edit($id) {
        $recid = ato::findOrFail($id);
        $trainingOrganizationListings = trainingOrganization::ORDERBY('training_organization', 'ASC')->GET();
        $atoListings = ato::ORDERBY('training_organization_id')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.ato.create', compact( 'recid', 'atoListings', 'trainingOrganizationListings')); 
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'training_organization_id' => 'required | integer',
            'approval_no' => 'required|string',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        if($request->hasFile('ato_certificate')) {
            $this->validate($request, [
                'ato_certificate' => 'required|max:2048',
            ]);
        }

        $check = ato::WHERE('approval_no', $request->approval_no)->WHERE('id', '<>', $id)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = ato::findOrFail($id);
            $recid->UPDATE($request->all());
            if($request->hasFile('ato_certificate')){
                $approvalNumberCerficate = $request->file('ato_certificate');
                $name = "ato/".str_slug($request->approval_no).'.'.$approvalNumberCerficate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/ato');
                $atoPath = $destination_path."/".$name;
                $approvalNumberCerficate->move($destination_path, $name);
                $recid->ato_certificate = $name;
                $recid->save();
            }
            updateHistory::CREATE([
                'name' => Auth::user()->name, 'module' => 'ato', 'record_id' => $id, 'actual' => $recid->approval_no
            ]);
            return 'updated';
        }
    }

    public function show() 
    {
        $atoListings = DB::SELECT(
            DB::RAW(
                'SELECT a.*, b.training_organization FROM tbl_ncaa_atos a JOIN `tbl_ncaa_training_organizations` b ON a.training_organization_id = b.id'
            )
        );
        $checkforaocupdates = updateHistory::WHERE('module', 'tac')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

        return view('v1.ncaa.economics-licence.ato.show', compact('atoListings', 'checkforaocupdates'));
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
