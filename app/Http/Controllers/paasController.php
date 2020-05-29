<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\generalAviation;
use Auth;
use App\updateHistory;
use App\paas;

class paasController extends Controller
{
    public function index()
    {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $paaslistings = paas::ORDERBY('operator', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.paas.create', compact('aoclists', 'generalaviations', 'paaslistings'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'paas_certificate' => 'required|max:2048',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        $check = paas::WHERE('licence_no', $request->licence_no)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = paas::CREATE($request->all());
            $id = $recid->id;
            if($request->hasFile('paas_certificate')){
                    $licenceNumberCerficate = $request->file('paas_certificate');
        			$name = "paas/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/economic-licence/paas');
        			$paasPath = $destination_path."/".$name;
        			$licenceNumberCerficate->move($destination_path, $name);
        			$recid->paas_certificate = $name;
        			$recid->save();
            }
            return 'saved';
        }
    }

    public function edit($id) {
        $recid = paas::findOrFail($id);
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $paaslistings = paas::ORDERBY('operator', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.paas.create', compact('aoclists', 'generalaviations', 'recid', 'paaslistings')); 
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        if($request->hasFile('paas_certificate')) {
            $this->validate($request, [
                'paas_certificate' => 'required|max:2048',
            ]);
        }

        $check = paas::WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = paas::findOrFail($id);
            $recid->UPDATE($request->all());
            if($request->hasFile('paas_certificate')){
                $licenceNumberCerficate = $request->file('paas_certificate');
                $name = "paas/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/paas');
                $paasPath = $destination_path."/".$name;
                $licenceNumberCerficate->move($destination_path, $name);
                $recid->paas_certificate = $name;
                $recid->save();
            }
            return 'updated';
        }
    }

    public function show() 
    {
        $paaslistings = paas::ORDERBY('operator', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.paas.show', compact('paaslistings'));
    }

    public function showByOperator(Request $request) {
        $orderby = $request->direction;
        $paaslistings = paas::ORDERBY('operator', $orderby)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($paaslistings)) {
            $counter = 0;
                foreach($paaslistings as $paas) {
                     $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($paas->date_of_expiry);;
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
                    $issuedDate = strtotime($paas->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($paas->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($paas->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($paas->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$paas->paas_certificate.'" target="_blank">
                            '.$paas->licence_no.'
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

    public function activePaas(Request $request) {
        $paaslistings = paas::ORDERBY('operator', 'ASC')->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($paaslistings)) {
            $counter = 0;
                foreach($paaslistings as $paas) {                     
                    $now = time();
                    $due_date = strtotime($paas->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                       
                    
                    $issuedDate = strtotime($paas->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($paas->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($paas->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($paas->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$paas->paas_certificate.'" target="_blank">
                            '.$paas->licence_no.'
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

    public function expiredPaas(Request $request) {
        $paaslistings = paas::ORDERBY('operator', 'ASC')->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($paaslistings)) {
            $counter = 0;
                foreach($paaslistings as $paas) {                     
                    $now = time();
                    $due_date = strtotime($paas->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <= 0 ){
                        $bgcolor = "red";
                        $color = "#fff";
                        $remarks = "Expired";
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                       
                    
                    $issuedDate = strtotime($paas->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($paas->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($paas->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($paas->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$paas->paas_certificate.'" target="_blank">
                            '.$paas->licence_no.'
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

    public function expiringSoonPaas(Request $request) {
        $paaslistings = paas::ORDERBY('operator', 'ASC')->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($paaslistings)) {
            $counter = 0;
                foreach($paaslistings as $paas) {                     
                    $now = time();
                    $due_date = strtotime($paas->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0) && ($numberofdays <= 90) ){
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = "Expiring Soon";
                        $counter++; 
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                       
                    
                    $issuedDate = strtotime($paas->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($paas->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($paas->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                    
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($paas->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$paas->paas_certificate.'" target="_blank">
                            '.$paas->licence_no.'
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
}
