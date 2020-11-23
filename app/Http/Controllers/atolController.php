<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\generalAviation;
use Auth;
use App\updateHistory;
use App\travelAgency;
use App\atol;

class atolController extends Controller
{
    public function index() {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $travelAgencies = travelAgency::SELECT('id', 'travel_agency_name')->ORDERBY('travel_agency_name', 'ASC')->GET();

        $atolListings = atol::paginate(15);

        return view('v1.ncaa.economics-licence.atol.create', compact('aoclists', 'generalaviations', 'travelAgencies', 'atolListings'));
    }

    public function store(Request $request) {
        $check = atol::WHERE('operator_type', $request->operator_type)->WHERE('licence_no', $request->licence_no)->exists();
        if($check) {
            return 'exists';
        }
        else{
            $atolRecord = atol::CREATE($request->all());
            $id = $atolRecord->id;
    		$recid=atol::findOrFail($id);
            if($request->hasFile('file')){
                $atol_certificate = $request->file('file');
                $name = "atolcertificate-".str_slug($request->operator_type).'.'.$atol_certificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/atol/');
                $atolPath = $destination_path."/".$name;
                $atol_certificate->move($destination_path, $name);
                $recid->atol_certificate = $name;
                $recid->save();
            }
        }

        return 'saved';
       
    }

    public function edit($id) {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $travelAgencies = travelAgency::SELECT('id', 'travel_agency_name')->ORDERBY('travel_agency_name', 'ASC')->GET();

        $atolListings = atol::paginate(15);

        $recid = atol::findOrFail($id);
        return view('v1.ncaa.economics-licence.atol.create', compact('aoclists', 'generalaviations', 'travelAgencies', 'atolListings', 'recid'));
    }

    public function update(Request $request, $id) {
        $check = atol::WHERE('operator_type', $request->operator_type)->WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check) {
            return 'exists';
        }
        else{
            $recid = atol::findOrFail($id);
            $atolRecord = $recid->UPDATE($request->all());
            if($request->hasFile('file')){
                $atol_certificate = $request->file('file');
                $name = "atolcertificate-".str_slug($request->operator_type).'.'.$atol_certificate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/atol/');
                $atolPath = $destination_path."/".$name;
                $atol_certificate->move($destination_path, $name);
                $recid->atol_certificate = $name;
                $recid->save();
            }
        }

        return 'updated';
    }

    public function show() {
        $atollisting = atol::ORDERBY('operator_type', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.atol.show', compact('atollisting'));
    }

    public function filterByTrainingOrg(Request $request) {
        $trainingOrganization = $request->trainingOrganization;
        $atollisting = atol::ORDERBY('operator_type', $trainingOrganization)->GET();

        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operator Type</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>';
            if(count($atollisting)){
            $counter = 0;
                foreach($atollisting as $atol) {
                     $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($atol->date_of_expiry);;
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
                    $issuedDate = strtotime($atol->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atol->renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atol->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $result.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($atol->operator_type).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/atol/'.$atol->atol_certificate.'" target="_blank">
                            '.$atol->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$atol->comments.'</td>
                </tr>';
                }
            } else {
            $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $result.='</tbody>
    </table>';
    return $result;
    }

    public function filterActive(Request $request) {
        $trainingOrganization = $request->trainingOrganization;
        $atollisting = atol::ORDERBY('operator_type', $trainingOrganization)->GET();

        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operator Type</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>';
            if(count($atollisting)){
            $counter = 0;
                foreach($atollisting as $atol) {
                    $counter++; 
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    
                    $now = time();
                    $due_date = strtotime($atol->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                    
                    $issuedDate = strtotime($atol->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atol->renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atol->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $result.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($atol->operator_type).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/atol/'.$atol->atol_certificate.'" target="_blank">
                            '.$atol->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$atol->comments.'</td>
                </tr>';
                    }
                }
            } else {
            $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        
        $result.='</tbody>
        </table>';
        return $result;
    }

    public function filterExpiringSoon(Request $request) {

        $trainingOrganization = $request->trainingOrganization;
        $atollisting = atol::ORDERBY('operator_type', $trainingOrganization)->GET();

        $result = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operator Type</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>';
            if(count($atollisting)){
            $counter = 0;
                foreach($atollisting as $atol) {
                    $counter++; 
                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    
                    $now = time();
                    $due_date = strtotime($atol->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0) && ($numberofdays <=90)){
                    $bgcolor = "#ffbf00";
                    $color = "#000";
                    $remarks = "Expiring soon";
                    
                    $issuedDate = strtotime($atol->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atol->renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atol->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                    $result.='<tr class="'.$css_style.'">
                        <td>'.$counter.'</td>
                        <td>'.strtoupper($atol->operator_type).'</td>
                        <td>
                            <a href="/confidentials/economic-licence/atol/'.$atol->atol_certificate.'" target="_blank">
                                '.$atol->licence_no.'
                            </a>
                        </td>
                        <td class="text-center">'.$dateIssued.'</td>
                        <td class="text-center">'.$renewed.'</td>
                        <td class="center">'.$expired.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            '.$remarks.'
                        </td>
                        <td>'.$atol->comments.'</td>
                    </tr>';
                    }
                }
            } 
            else {
            $result.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
        
        $result.='</tbody>
        </table>';
        return $result;
    }

    public function filterExpired(Request $request) {

            $trainingOrganization = $request->trainingOrganization;
            $atollisting = atol::ORDERBY('operator_type', $trainingOrganization)->GET();

            $result = '<table class="table table-bordered" id="exportTableData">
            <thead>
                <tr class="table-warning">
                    <th width="5%">#</th>
                    <th><b>Operator Type</b></th>
                    <th><b>Licence No.</b></th>
                    <th class="text-center"><b>Date of Initial Issue</b></th>
                    <th class="center"><b>Date of Last Renewal</b></th>
                    <th class="center"><b>Date of Expiry</b></th>
                    <th class="center"><b>Status</b></th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>';
                if(count($atollisting)){
                $counter = 0;
                    foreach($atollisting as $atol) {
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        
                        $now = time();
                        $due_date = strtotime($atol->date_of_expiry);;
                        $datediff = $due_date - $now;
                        $numberofdays = round($datediff / (60 * 60 * 24));

                        if($numberofdays < 0 ){
                            $bgcolor = "red";
                            $color = "#000";
                            $remarks = "Expired";
                        
                       
                        $issuedDate = strtotime($atol->date_of_first_issue); 
                        $dateIssued = date('d/m/Y', $issuedDate);
                        
                        $renewalDate = strtotime($atol->renewal); 
                        $renewed = date('d/m/Y', $renewalDate);

                        $dateExpired = strtotime($atol->date_of_expiry); 
                        $expired = date('d/m/Y', $dateExpired);
                        
                    $result.='<tr class="'.$css_style.'">
                        <td>'.$counter.'</td>
                        <td>'.strtoupper($atol->operator_type).'</td>
                        <td>
                            <a href="/confidentials/economic-licence/atol/'.$atol->atol_certificate.'" target="_blank">
                                '.$atol->licence_no.'
                            </a>
                        </td>
                        <td class="text-center">'.$dateIssued.'</td>
                        <td class="text-center">'.$renewed.'</td>
                        <td class="center">'.$expired.'</td>
                        <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                            '.$remarks.'
                        </td>
                        <td>'.$atol->comments.'</td>
                    </tr>';
                        }
                    }
                } else {
                $result.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
                }
            
            $result.='</tbody>
        </table>';
        return $result;
    }

    public function destroy(Request $request, $id) {
        $recid = atol::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'atol', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->operator.' '.$recid->licence_no
        ]);
        return 'deleted';
    }
}
