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

    public function fcopByStatus(Request $request) {
        $fcop_status = $request->fcopStatus;
        $fcopListings = Fcop::WHERE('fcop_status', $fcop_status)->GET();

        $answer = '<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%">#</th>
                <th><b>Foreign Airline</b></th>
                <th><b>Licence No.</b></th>
                <th class="text-center"><b>FCOP Issue Date</b></th>
                <th class="center"><b>Part 18</b></th>
                <th class="center"><b>Part 10</b></th>
                <th class="center"><b>Part 17</b></th>
                <th class="center"><b>FCOP Status</b></th>
                <th class="font-weight-bold">Comments</th>
            </tr>
        </thead>
        <tbody>';
        if(count($fcopListings)) {
        $counter = 0;
            foreach($fcopListings as $fcop) {
                $counter++; 
                $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary'; 
                $issuedDate = strtotime($fcop->date_fcop_issued); 
                $dateIssued = date('d/m/Y', $issuedDate);
                if($fcop->part_18 == 0) {
                    $part18 = 'No';
                    $bgColorP18 = 'red';
                }
                else {
                    $part18 = 'Yes';
                    $bgColorP18 = 'green';
                }
                if($fcop->part_10 == 0) {
                    $part10 = 'No';
                    $bgColorP10 = 'red';
                }
                else {
                     $part10 = 'Yes';
                     $bgColorP10 = 'green';
                }

                if($fcop->part_17 == 0) {
                    $part17 = 'No';
                    $bgColorP17 = 'red';
                }
                else {
                     $part17 = 'Yes';
                     $bgColorP17 = 'green';
                }
                if($fcop->fcop_status == 0 ) {
                    $status = 'Inactive';
                    $bgColor = "red";
                }
                else {
                    $status = 'Active'; 
                    $bgColor = 'green';
                }   
                    
                $answer.='
                <tr class="'.$css_style.'">
                    <td>'.$counter.'</td>
                    <td>'.strtoupper($fcop->foreign_airline).'</td>
                    <td>
                        <a href="/confidentials/economic-licence/'.$fcop->fcop_certificate.'" target="_blank">
                            '.$fcop->licence_no.'
                        </a>
                    </td>
                    <td class="text-center">'.$dateIssued.'</td>
                    <td class="text-center" style="background: '.$bgColorP18.'; color: #fff">'.$part18.'</td>
                    <td class="center" style="background: '.$bgColorP10.'; color: #fff">'.$part10.'</td>
                    <td class="center" style="background: '.$bgColorP17.'; color: #fff">'.$part17.'</td>
                    <td class="center" style="background: '.$bgColor.'; color: #fff">'.$status.'</td>
                    <td>'.$fcop->comments.'</td>
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

    public function destroy(Request $request, $id) {
        $recid = Fcop::findOrFail($id);
        $recid->DELETE();
        updateHistory::CREATE([
            'name' => Auth::user()->name, 'module' => 'fcop', 'record_id' => $id, 'actual' => 'DELETED:'.$recid->licence_no
        ]);
        return 'deleted';
    }
}
