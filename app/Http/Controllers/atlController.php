<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\aoc;
use App\generalAviation;
use Auth;
use App\updateHistory;
use App\atl;

class atlController extends Controller
{
    public function index()
    {
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $atllistings = atl::ORDERBY('operator', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.atl.create', compact('aoclists', 'generalaviations', 'atllistings'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'atl_certificate' => 'required|max:2048',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        $check = atl::WHERE('licence_no', $request->licence_no)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = atl::CREATE($request->all());
            $id = $recid->id;
            if($request->hasFile('atl_certificate')){
                    $licenceNumberCerficate = $request->file('atl_certificate');
        			$name = "atl/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/economic-licence/atl');
        			$atlPath = $destination_path."/".$name;
        			$licenceNumberCerficate->move($destination_path, $name);
        			$recid->atl_certificate = $name;
        			$recid->save();
            }
            return 'saved';
        }
    }

    public function edit($id) {
        $recid = atl::findOrFail($id);
        $aoclists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
        $generalaviations = generalAviation::SELECT('id', 'general_aviation_name')->ORDERBY('general_aviation_name', 'ASC')->GET();
        $atllistings = atl::ORDERBY('operator', 'ASC')->PAGINATE(15);
        return view('v1.ncaa.economics-licence.atl.create', compact('aoclists', 'generalaviations', 'recid', 'atllistings')); 
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'operator' => 'required | string',
            'licence_no' => 'required|string',
            'date_of_first_issue' => 'required|string',
            'date_of_renewal' => 'required|string',
            'date_of_expiry' => 'required|string'
        ]);

        if($request->hasFile('atl_certificate')) {
            $this->validate($request, [
                'atl_certificate' => 'required|max:2048',
            ]);
        }

        $check = atl::WHERE('licence_no', $request->licence_no)->WHERE('id', '<>', $id)->exists();
        if($check)
        {
            return 'exists';
        }   
        else {
            $recid = atl::findOrFail($id);
            $recid->UPDATE($request->all());
            if($request->hasFile('atl_certificate')){
                $licenceNumberCerficate = $request->file('atl_certificate');
                $name = "atl/".str_slug($request->licence_no).'.'.$licenceNumberCerficate->getClientOriginalExtension();
                $destination_path = public_path('confidentials/economic-licence/atl');
                $atlPath = $destination_path."/".$name;
                $licenceNumberCerficate->move($destination_path, $name);
                $recid->atl_certificate = $name;
                $recid->save();
            }
            return 'updated';
        }
    }

    public function show() 
    {
        $atllistings = atl::ORDERBY('operator', 'ASC')->GET();
        return view('v1.ncaa.economics-licence.atl.show', compact('atllistings'));
    }

    public function showByOperator(Request $request) {
        $orderby = $request->direction;
        $atllistings = atl::ORDERBY('operator', $orderby)->GET();

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
            if(count($atllistings)) {
            $counter = 0;
                foreach($atllistings as $atl){
                     $counter++; 
                     $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                     
                    $now = time();
                    $due_date = strtotime($atl->date_of_expiry);;
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
                    $issuedDate = strtotime($atl->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atl->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atl->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($atl->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$atl->atl_certificate.'" target="_blank">
                            '.$atl->licence_no.'
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
            } else{
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
         </table>';

         return $answer;
    }

    public function activeAtl(Request $request) {
        $orderby = $request->direction;
        $atllistings = atl::ORDERBY('operator', $orderby)->GET();

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
            if(count($atllistings)) {
            $counter = 0;
                foreach($atllistings as $atl){
                     
                    $now = time();
                    $due_date = strtotime($atl->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays > 90 ){
                        $counter++;
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $bgcolor = "green";
                        $color = "#fff";
                        $remarks = "Active";
                    
                    
                    $issuedDate = strtotime($atl->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atl->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atl->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($atl->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$atl->atl_certificate.'" target="_blank">
                            '.$atl->licence_no.'
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
            } else{
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
         </table>';

         return $answer;
    }

    public function expiredAtl(Request $request) {
        $orderby = $request->direction;
        $atllistings = atl::ORDERBY('operator', $orderby)->GET();

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
            if(count($atllistings)) {
            $counter = 0;
                foreach($atllistings as $atl){                      
                    $now = time();
                    $due_date = strtotime($atl->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if($numberofdays <=0 ){
                        $counter++;
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                        $bgcolor = "red";
                        $color = "#000";
                        $remarks = "Expired";
                    
                    $issuedDate = strtotime($atl->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atl->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atl->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                
                    $answer.='<tr class="'.$css_style.'">
                        <td>'.$counter.'</td>
                        <td>'.strtoupper($atl->operator).'</td>
                        <td>
                            <a href="/confidentials/economic-licence/'.$atl->atl_certificate.'" target="_blank">
                                '.$atl->licence_no.'
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
            } else{
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
         </table>';

         return $answer;
    }

    public function expiringSoonAtl(Request $request) {
        $orderby = $request->direction;
        $atllistings = atl::ORDERBY('operator', $orderby)->GET();

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
            if(count($atllistings)) {
            $counter = 0;
                foreach($atllistings as $atl){ 
                     
                    $now = time();
                    $due_date = strtotime($atl->date_of_expiry);;
                    $datediff = $due_date - $now;
                    $numberofdays = round($datediff / (60 * 60 * 24));

                    if(($numberofdays >= 0) && ($numberofdays <=90)){
                        $counter++;
                        $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';

                        $bgcolor = "#ffbf00";
                        $color = "#000";
                        $remarks = "Expiring soon";
                    
                   
                    $issuedDate = strtotime($atl->date_of_first_issue); 
                    $dateIssued = date('d/m/Y', $issuedDate);
                    
                    $renewalDate = strtotime($atl->date_of_renewal); 
                    $renewed = date('d/m/Y', $renewalDate);

                    $dateExpired = strtotime($atl->date_of_expiry); 
                    $expired = date('d/m/Y', $dateExpired);
                
                $answer.='<tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($atl->operator).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$atl->atl_certificate.'" target="_blank">
                            '.$atl->licence_no.'
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
            } else{
                $answer.='<tr>
                    <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                </tr>';
            }
           
        $answer.='</tbody>
         </table>';

         return $answer;
    }

    public function destroy(Request $request, $id) {
        $recid = atl::findOrFail($id);
        $recid->DELETE();
        return 'deleted';
    }
}
