<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\generalAviation;
use Auth;
use App\updateHistory;
use App\aop;

class aopController extends Controller
{
    public function index() {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $aopListings = aop::ORDERBY('operator', 'ASC')->PAGINATE(10);
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.aop.create', compact('aoclists', 'generalaviations', 'aopListings')); 
    }

    public function store(Request $request) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'aop_certificate' => 'required|max:2048',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        $check = aop::WHERE('licence_no', $request->licence_no)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = aop::CREATE($request->all());
            $id = $recid->id;
            if($request->hasFile('aop_certificate')){
                    $licenceNumberCerficate = $request->file('aop_certificate');
        			$name = "aop/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/economic-licence/aop');
        			$aopPath = $destination_path."/".$name;
        			$licenceNumberCerficate->move($destination_path, $name);
        			$recid->aop_certificate = $name;
        			$recid->save();
            }
            return 'saved';
        }
    }

    public function edit($id) {
        $recid = aop::findOrFail($id);
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $aopListings = aop::ORDERBY('operator', 'ASC')->PAGINATE(10);
        return view('v1.ncaa.economics-licence.aop.create', compact('aoclists', 'generalaviations', 'recid', 'aopListings')); 
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        if($request->hasFile('aop_certificate')) {
            $this->validate($request, [
                'aop_certificate' => 'required|max:2048',
            ]);
        }

        $check = aop::WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = aop::findOrFail($id);
            $recid->UPDATE($request->all());
            if($request->hasFile('aop_certificate')){
                $licenceNumberCerficate = $request->file('aop_certificate');
                $name = "aop/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/aop');
                $aopPath = $destination_path."/".$name;
                $licenceNumberCerficate->move($destination_path, $name);
                $recid->aop_certificate = $name;
                $recid->save();
            }
            return 'updated';
        }
    }

    public function show() 
    {
        $aoplistings = aop::ORDERBY('operator', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.aop.show', compact('aoplistings'));
    }

    public function showByOperator(Request $request) {
        $orderbyOption =  $request->direction;
        $aoplistings = aop::ORDERBY('operator', $orderbyOption)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($aoplistings)) {
            $counter = 0;
                foreach($aoplistings as $aop) {
                    $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($aop->date_of_expiry);;
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
                    $issuedDate = strtotime($aop->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($aop->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($aop->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
            
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aop->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$aop->aop_certificate.'" target="_blank">
                            '.$aop->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$aop->comment.'</td>
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

    public function activeAop(Request $request) {
        $orderbyOption =  $request->direction;
        $aoplistings = aop::ORDERBY('operator', $orderbyOption)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($aoplistings)) {
            $counter = 0;
                foreach($aoplistings as $aop) {
                    $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($aop->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                    
                    
                    $issuedDate = strtotime($aop->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($aop->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($aop->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
            
                    $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aop->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$aop->aop_certificate.'" target="_blank">
                            '.$aop->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$aop->comment.'</td>
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

    public function expiredAop(Request $request) {
        $orderbyOption =  $request->direction;
        $aoplistings = aop::ORDERBY('operator', $orderbyOption)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($aoplistings)) {
            $counter = 0;
                foreach($aoplistings as $aop) {
                    $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($aop->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <= 0 ){
                        $bgcolor = "red";
                        $color = "#fff";
                        $remarks = "Expired";
                    
                    $issuedDate = strtotime($aop->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($aop->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($aop->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
            
                    $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aop->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$aop->aop_certificate.'" target="_blank">
                            '.$aop->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$aop->comment.'</td>
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

    public function expiringSoonAop(Request $request) {
        $orderbyOption =  $request->direction;
        $aoplistings = aop::ORDERBY('operator', $orderbyOption)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Operators</b></th>
                <th><b>Licence No.</b></th>
                <th class="center"><b>Date of Initial Issue</b></th>
                <th class="center"><b>Date of Last Renewal</b></th>
                <th class="center"><b>Date of Expiry</b></th>
                <th class="center"><b>Status</b></th>
                <th><b>Comment</b></th>
            </tr>
        </thead>
        <tbody>';
            if(count($aoplistings)) {
            $counter = 0;
                foreach($aoplistings as $aop) {
                    
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($aop->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0)  && ($numberofdays <=90)){
                        $counter++; 
                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = "Expiring soon";
                    
                    $issuedDate = strtotime($aop->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($aop->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($aop->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
            
                    $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($aop->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$aop->aop_certificate.'" target="_blank">
                            '.$aop->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center">'.$renewed.'</td>
                    <td class="center">'.$expired.'</td>
                    <td style="text-align:center; background:'.$bgcolor.'; color:'.$color.';">
                        '.$remarks.'
                    </td>
                    <td>'.$aop->comment.'</td>
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
        $recid = aop::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'aop', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->operator
        ]);
        return 'deleted';
    }


}
