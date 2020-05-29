<?php

namespace App\Http\Controllers;
use App\aoc;
use App\aircrafts;
use App\aircraftMaker;
use App\aocAircrafts;
use App\operations;
use App\assignOperationSpecAoc;
use App\localamo;
use App\focc;
use App\Http\Requests;
use App\Http\Requests\aocRequest;
use App\Http\Requests\aocAircraftRequest;
use App\Http\Requests\assignOperationSpecsAocRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;



class aoccontroller extends Controller
{
    public function viewallaoc(){
        if(Auth::check() && Auth::user()->role){
            $aoclists = aoc::ORDERBY('aoc_holder', 'ASC')->GET();
        
            $aocAircrafts = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.aircraft_maker_id, b.aircraft_maker FROM tbl_ncaa_aoc_aircrafts a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id '));

            $oprspecslists = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.operation_type_id, b.operation_type FROM tbl_ncaa_assign_operation_spec_aocs a JOIN tbl_ncaa_operations b ON a.operation_type_id = b.id'));

            $operations = operations::ORDERBY('operation_type', 'ASC')->GET();

            $checkforaocupdates = updateHistory::WHERE('module', 'aoc')->ORDERBY('updated_at', 'DESC')->LIMIT(1)->GET();

            return view('v1.ncaa.aoc.view-all', compact('aoclists', 'aocAircrafts', 'oprspecslists', 'checkforaocupdates', 'operations'));

        }
        return redirect()->route('login');
        
    }
    public function index(){
        if(Auth::check() && Auth::user()->role){
            $aoclistings = aoc::ORDERBY('aoc_holder', 'ASC')->PAGINATE(10);
            return view('v1.ncaa.aoc.aoc', compact('aoclistings'));    
        }
        return redirect()->route('login');
    }
    public function store(aocRequest $request){
        if(Auth::check() && Auth::user()->role){
            $check = aoc::WHERE('aoc_holder', $request->get('aoc_holder'))->WHERE('aoc_certificate_no', $request->get('aoc_certificate_no'))->exists();
    		if($check){
    			return 'exists';
    		}
    		else{
    			$aoc = aoc::CREATE($request->all());
    			$id = $aoc->id;
    			$recid=aoc::findOrFail($id);
        		    if($request->hasFile('aoc_certificate')){
        			$aoc_certificate = $request->file('aoc_certificate');
        			$name = "aoccertificate/".str_slug($request->get('aoc_holder')).'.'.$aoc_certificate->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/aoccertificate/');
        			$aocPath = $destination_path."/".$name;
        			$aoc_certificate->move($destination_path, $name);
        			$recid->aoc_certificate = $name;
        			$recid->save();
                  }
                  if($request->hasFile('ops_specs')){
        			$ops_specs = $request->file('ops_specs');
        			$name = "opsspecs/".str_slug($request->get('aoc_holder')).'.'.$ops_specs->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/opsspecs/');
        			$opsspecsPath = $destination_path."/".$name;
        			$ops_specs->move($destination_path, $name);
        			$recid->ops_specs = $name;
        			$recid->save();
                  }
                  if($request->hasFile('part_g')){
        			$part_g = $request->file('part_g');
        			$name = "partg/".str_slug($request->get('aoc_holder')).'.'.$part_g->getClientOriginalExtension();
        			$destination_path = public_path('confidentials/partg/');
        			$partgPath = $destination_path."/".$name;
        			$part_g->move($destination_path, $name);
        			$recid->part_g = $name;
        			$recid->save();
        		  }
                return "save";
    		}
        }
        return redirect()->route('login');
    }

    public function edit($id){
        if(Auth::check() && Auth::user()->role){
            $aoclistings = aoc::ORDERBY('aoc_holder', 'ASC')->PAGINATE(10);
            $recid = aoc::findOrFail(base64_decode($id));
            return view('v1.ncaa.aoc.editaoc', compact('aoclistings', 'recid'));    
        }
        return redirect()->route('login');
    }

    public function update(Request $request, $id){
        if(Auth::check() && Auth::user()->role){
            $check = aoc::WHERE('aoc_holder', $request->get('aoc_holder'))->WHERE('id', '!=', base64_decode($id))->exists();   
            if($check){
                return 'exists';
            }
            else{
                $recid = aoc::findOrFail(base64_decode($id));
                $recid->update($request->all());
                if($request->hasFile('aoc_certificate')){
                    $aoc_certificate = $request->file('aoc_certificate');
                    $name = "aoccertificate/".str_slug($request->get('aoc_holder')).'.'.$aoc_certificate->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/aoccertificate/');
                    $aocPath = $destination_path."/".$name;
                    $aoc_certificate->move($destination_path, $name);
                    $recid->aoc_certificate = $name;
                    $recid->save();
                }
                if($request->hasFile('ops_specs')){
                    $ops_specs = $request->file('ops_specs');
                    $name = "opsspecs/".str_slug($request->get('aoc_holder')).'.'.$ops_specs->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/opsspecs/');
                    $opsspecsPath = $destination_path."/".$name;
                    $ops_specs->move($destination_path, $name);
                    $recid->ops_specs = $name;
                    $recid->save();
                }
                if($request->hasFile('part_g')){
                    $part_g = $request->file('part_g');
                    $name = "partg/".str_slug($request->get('aoc_holder')).'.'.$part_g->getClientOriginalExtension();
                    $destination_path = public_path('confidentials/partg/');
                    $partgPath = $destination_path."/".$name;
                    $part_g->move($destination_path, $name);
                    $recid->part_g = $name;
                    $recid->save();
                }
                $update = updateHistory::CREATE($request->all());
                return 'updated';
            }
        }
        return redirect()->route('login');        
    }

    public function assignAirCraftType($name, $id){
        if(Auth::check() && Auth::user()->role){
            $getAircraftMakerLists = aircraftMaker::LATEST('created_at')->GET();
        
            $aircraftlistings = DB::SELECT(DB::RAW('SELECT a.id, b.*, c.* FROM tbl_ncaa_acos a JOIN tbl_ncaa_aircraft_makers b JOIN tbl_ncaa_aoc_aircrafts c ON a.id = c.aoc_holder_id AND b.id= c.aircraft_maker_id AND a.id='.$id.' ' ));

            return view('v1.ncaa.aoc.aircrafttype', compact('getAircraftMakerLists', 'name', 'id', 'aircraftlistings'));
        }
        return redirect()->route('login');
    }
    public function storeAocAircraftMake(aocAircraftRequest $request){
        if(Auth::check()){
            $check = aocAircrafts::WHERE('aoc_holder_id', $request->get('aoc_holder_id'))->WHERE('aircraft_maker_id', $request->get('aircraft_maker_id'))->exists();
            if($check){
                return 'exists';
            }
            else{
                aocAircrafts::CREATE($request->all());
    		    return 'saved';
            }
        }
        return redirect()->route('login');
    }

    public function assignOperationTypeToAoc(){
        if(Auth::check() && Auth::user()->role){
            $aocHolderLists = aoc::SELECT('id', 'aoc_holder')->ORDERBY('aoc_holder', 'ASC')->GET();
            $operationsLists = operations::ORDERBY('operation_type', 'ASC')->GET();
            return view('v1.ncaa.aoc.assign-operation-specs-to-aoc', compact('aocHolderLists', 'operationsLists'));
        }
        return redirect()->route('login');
    }

    public function getListings(Request $request){
        if(Auth::check() && Auth::user()->role){
            $aoc_holder_id = $request->get('aoc_holder_id');
            return $this->assignOperation($aoc_holder_id);    
        }
        return redirect()->route('login');
    }

    public function insertAssignedOperation(assignOperationSpecsAocRequest $request){
        if(Auth::check() && Auth::user()->role){
            $aoc_holder_id = $request->get('aoc_holder_id');
            $operation_type = $request->get('operation_type');
            foreach($operation_type as $operation_type_id){
                DB::INSERT(DB::RAW('INSERT INTO tbl_ncaa_assign_operation_spec_aocs (aoc_holder_id, operation_type_id) VALUES ('.$aoc_holder_id.', '.$operation_type_id.')'));
            }
            return $this->assignOperation($aoc_holder_id);    
        }
        return redirect()->route('login');
    }
    public function removeOperation(assignOperationSpecsAocRequest $request){
        if(Auth::check() && Auth::user()->role){
            $aoc_holder_id = $request->get('aoc_holder_id');
            $operation_type = $request->get('operation_type');
            foreach($operation_type as $operation_type_id){
                DB::DELETE(DB::RAW('DELETE FROM tbl_ncaa_assign_operation_spec_aocs WHERE aoc_holder_id = '.$aoc_holder_id.' AND operation_type_id = '.$operation_type_id.''));
            }
            return $this->assignOperation($aoc_holder_id);
        }
        return redirect()->route('login');
    }
    public function assignOperation($aoc_holder_id){
    $table = '<div class="col-md-5 grid-margin">
        <div class="card" style="border-radius:0">
            <div class="card-body" style="padding:0;">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-info">
                            <td colspan="4" style="font-size:12px; font-weight:bold">All available listings of operation specification</td>
                        </tr>
                        <tr class="table-warning">
                            <td style="font-size:12px; font-weight:bold">#</td>
                            <td><input type="checkbox" id="selectAllLeftchkbx"></td>
                            <td style="font-size:12px; font-weight:bold" colspan="4" id="selectAllLeftTxt">Select all available Operation specifications listings</td>
                        </tr>
                    </thead>
                    <tbody>';
                    $operationsLists = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_operations WHERE id NOT IN (SELECT operation_type_id FROM tbl_ncaa_assign_operation_spec_aocs WHERE aoc_holder_id = '.$aoc_holder_id.')'));
                    $counter = 0;
                    foreach($operationsLists as $operation){
                        $counter+=1;
                        $counter % 2 == 0 ? $css_style = 'table-primary' : $css_style = 'table-secondary';
                            $table.='<tr class='.$css_style.'>
                                <td style="font-size:12px;">'.$counter.'</td>
                                <td style="font-size:12px;">
                                    <input type="checkbox" class="operationTypeLeftChkbx" name="operation_type[]"value='.$operation->id.'>
                                </td>
                                <td style="font-size:12px;">'.$operation->operation_type.'</td>
                            </tr>';
                    }
                    $table.='</tbody>
                </table> 
            </div>
        </div>
    </div>';

    $table.='<div class="col-md-2 grid-margin">
        <div class="card" style="background:none">
        <div class="card-body">
            <button type="button" id="assignoperationSpecs" class="btn btn-gradient-primary btn-rounded btn-icon" style="margin:5px;" title="Add Operation Specification to AOC Holder">
                <i class="mdi mdi-thumb-up"></i>
            </button>
            <button type="button" id="removeOprSpecs" class="btn btn-gradient-danger btn-rounded btn-icon" style="margin:5px;" title="Remove Operation Specification from AOC Holder">
                    <i class="mdi mdi-thumb-down"></i>
            </button>
        </div>
        </div>
    </div>';

    $table.='<div class="col-md-5 grid-margin">
        <div class="card" style="border-radius:0">
            <div class="card-body" style="padding:0;">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-info">
                            <td colspan="4" style="font-size:12px; font-weight:bold">All assigned operation specification</td>
                        </tr>
                        <tr class="table-warning">
                            <td style="font-size:12px; font-weight:bold">#</td>
                            <td><input type="checkbox" id="selectAllRightchkbx"></td>
                            <td style="font-size:12px; font-weight:bold" colspan="4" id="assignedRightLabel">Select all assigned Operation Specification</td>
                        </tr>
                        
                    </thead>
                    <tbody>';
                    $operationsListsRight = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_operations WHERE id IN (SELECT operation_type_id FROM tbl_ncaa_assign_operation_spec_aocs WHERE aoc_holder_id = '.$aoc_holder_id.')'));
                        $counter = 0;
                        if(count($operationsListsRight)>0)
                            foreach($operationsListsRight as $operation){
                                $counter+=1;
                                $counter % 2 == 0 ? $css_style = 'table-primary' : $css_style = 'table-secondary';
                                    $table.='<tr class='.$css_style.'>
                                        <td style="font-size:12px;">'.$counter.'</td>
                                        <td style="font-size:12px;">
                                            <input type="checkbox" class="operationTypeRightChkbx" name="operation_type[]"value='.$operation->id.'>
                                        </td>
                                        <td style="font-size:12px;">'.$operation->operation_type.'</td>
                                    </tr>';
                            }
                        else{
                            $table.='<tr class="table-success">
                                <td style="font-size:12px; line-height:15px;" colspan="4">You\'ve not assigned any operation specification to this AOC Holder</td>
                            </tr>';
                        }
                        $table.='</tbody>';
                        
                $table.='</table> 
            </div>
        </div>
    </div>';
    return $table;
   }

   public function getAocholderAircraftbyMake($aocholder, $aocid, $acmaker, $acmakerid){
       if(Auth::check() && Auth::user()->role){
            $aocrec = aoc::findOrFail($aocid);
            $acmakerrec = aircraftMaker::findOrFail($acmakerid);
            $aircrafthistory = aircrafts::WHERE('aoc_holder_id', $aocid)->WHERE('aircraft_maker_id', $acmakerid)->ORDERBY('year_of_manufacture', 'DESC')->GET();
    
            return view('v1.ncaa.aoc.aocaircrafthistorybymake', compact('aocrec', 'acmakerrec', 'aircrafthistory'));
        }
        return redirect()->route('login');
   }

   public function destroy($id){
    if(Auth::check() && Auth::user()->role){
        // check ID if exists on the following models;
        $aircraftscheck = aircrafts::WHERE('aoc_holder_id', $id)->exists();
        $localamocheck = localamo::WHERE('aoc_holder_id', $id)->exists();
        $focccheck = focc::WHERE('aoc_holder_id', $id)->exists();
        
        if($aircraftscheck || $localamocheck || $focccheck){
            return 'cant_delete';
        }
        $aocrec = aoc::findOrFail($id);
        $aocrec->DELETE();
        return 'deleted';
       
    }
    return redirect()->route('login');
   }



// Return all aocs by remarks.
public function viewallaocbyremarks(Request $request){
    $remark = $request->get('remarks');

    $answer ='<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th><b>AOC Holder</b></th>
                <th width="12%"><b>A/C Type Operated</b></th>
                <th width="13%" class="center"><b>AOC Certificate No.</b></th>
                <th width="13%" class="center"><b>Issued Date</b></th>
                <th width="13%" class="center"><b>Validity Date</b></th>
                <th width="5%" class="center"><b>OPS Spec</b></th>
                <th width="5%" class="center"><b>PART G</b></th>
                <th width="7%"><b>Remarks</b></th>
                <th><b>Operation</b></th>
            </tr>
        </thead>
        <tbody>';
        $aoclists = aoc::WHERE('remarks', $remark)->ORDERBY('aoc_holder', 'ASC')->GET();

        $aocAircrafts = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.aircraft_maker_id, b.aircraft_maker FROM tbl_ncaa_aoc_aircrafts a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id '));

        $oprspecslists = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.operation_type_id, b.operation_type FROM tbl_ncaa_assign_operation_spec_aocs a JOIN tbl_ncaa_operations b ON a.operation_type_id = b.id'));


           $count = 0;
           $oprscounter = 0;
            if(count($aoclists)){
                foreach($aoclists as $aoc){
                    $count++;
                    date_default_timezone_set("Africa/Lagos");
                    $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    if($aoc->remarks == 1){
                        $remarks = 'Active'; $color = 'green';
                    }
                    if($aoc->remarks == 2){
                        $remarks = 'Suspended'; $color = '#ffbf00';
                    }
                    if($aoc->remarks == 3){
                        $remarks = 'Expired'; $color = 'red';
                    }
                    if($aoc->remarks == 4){$remarks = 'Revoked';}

                    $converdatetotimeofvalidity = strtotime($aoc->validity); 
                    $validity = date('d/m/Y', $converdatetotimeofvalidity);

                    $converdatetotimeofissued = strtotime($aoc->issued_date); 
                    $issued_date = date('d/m/Y', $converdatetotimeofissued);

                    $answer.='<tr style="font-family:tahoma;" class='.$css_style.'>
                        <td>'.$count.'</td>
                        <td>'.strtoupper($aoc->aoc_holder).'</td>
                        <td>';
                            foreach($aocAircrafts as $acmaker){
                                if($acmaker->aoc_holder_id == $aoc->id){
                                    $answer.='<a href="/aircraft-list/'.str_slug($aoc->aoc_holder).'/'.$aoc->id.'/'.str_slug($acmaker->aircraft_maker).'/'.$acmaker->aircraft_maker_id.'" target="_blank">
                                        '.strtoupper($acmaker->aircraft_maker).', 
                                    </a>';
                                }
                            }
                        $answer.='</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->aoc_certificate.'" target="_blank">
                                '.$aoc->aoc_certificate_no.'
                            </a>
                        </td>
                        <td class="center">'.$issued_date.'</td>
                        <td class="center">'.$validity.'</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->ops_specs.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' OPS Specs"></i>
                            </a>
                        </td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->part_g.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' Part G"></i>
                            </a>
                        </td>
                        <td style="font-size:10px; line-height:15px; font-weight:bold; color:'.$color.'">'.$remarks.'</td>
                        <td>';
                            foreach($oprspecslists as $oprspecs){
                                if($aoc->id == $oprspecs->aoc_holder_id){
                                    $answer.=strtoupper($oprspecs->operation_type).', ';
                                }
                            }
                        $answer.='</td>
                    </tr>'; 
                }
            }                  
            else{
                    $answer.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
            }
            
        $answer.='</tbody>
    </table>';

    return $answer;
}

public function viewallaocbyissuedate(Request $request) {
    $issuedDate = strtoupper($request->get('issuedDate'));

    $answer ='<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th><b>AOC Holder</b></th>
                <th width="12%"><b>A/C Type Operated</b></th>
                <th width="13%" class="center"><b>AOC Certificate No.</b></th>
                <th width="13%" class="center"><b>Issued Date</b></th>
                <th width="13%" class="center"><b>Validity Date</b></th>
                <th width="5%" class="center"><b>OPS Spec</b></th>
                <th width="5%" class="center"><b>PART G</b></th>
                <th width="7%"><b>Remarks</b></th>
                <th><b>Operation</b></th>
            </tr>
        </thead>
        <tbody>';
        $aoclists = aoc::ORDERBY('issued_date', $issuedDate)->GET();

        $aocAircrafts = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.aircraft_maker_id, b.aircraft_maker FROM tbl_ncaa_aoc_aircrafts a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id '));

        $oprspecslists = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.operation_type_id, b.operation_type FROM tbl_ncaa_assign_operation_spec_aocs a JOIN tbl_ncaa_operations b ON a.operation_type_id = b.id'));

           $count = 0;
           $oprscounter = 0;
            if(count($aoclists)){
                foreach($aoclists as $aoc){
                    $count++;
                    date_default_timezone_set("Africa/Lagos");
                    $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    if($aoc->remarks == 1){
                        $remarks = 'Active'; $color = 'green';
                    }
                    if($aoc->remarks == 2){
                        $remarks = 'Suspended'; $color = '#ffbf00';
                    }
                    if($aoc->remarks == 3){
                        $remarks = 'Expired'; $color = 'red';
                    }
                    if($aoc->remarks == 4){$remarks = 'Revoked';}

                    $converdatetotimeofvalidity = strtotime($aoc->validity); 
                    $validity = date('d/m/Y', $converdatetotimeofvalidity);

                    $converdatetotimeofissued = strtotime($aoc->issued_date); 
                    $issued_date = date('d/m/Y', $converdatetotimeofissued);

                    $answer.='<tr style="font-family:tahoma;" class='.$css_style.'>
                        <td>'.$count.'</td>
                        <td>'.strtoupper($aoc->aoc_holder).'</td>
                        <td>';
                            foreach($aocAircrafts as $acmaker){
                                if($acmaker->aoc_holder_id == $aoc->id){
                                    $answer.='<a href="/aircraft-list/'.str_slug($aoc->aoc_holder).'/'.$aoc->id.'/'.str_slug($acmaker->aircraft_maker).'/'.$acmaker->aircraft_maker_id.'" target="_blank">
                                        '.strtoupper($acmaker->aircraft_maker).', 
                                    </a>';
                                }
                            }
                        $answer.='</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->aoc_certificate.'" target="_blank">
                                '.$aoc->aoc_certificate_no.'
                            </a>
                        </td>
                        <td class="center">'.$issued_date.'</td>
                        <td class="center">'.$validity.'</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->ops_specs.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' OPS Specs"></i>
                            </a>
                        </td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->part_g.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' Part G"></i>
                            </a>
                        </td>
                        <td style="font-size:10px; line-height:15px; font-weight:bold; color:'.$color.'">'.$remarks.'</td>
                        <td>';
                            foreach($oprspecslists as $oprspecs){
                                if($aoc->id == $oprspecs->aoc_holder_id){
                                    $answer.=strtoupper($oprspecs->operation_type).', ';
                                }
                            }
                        $answer.='</td>
                    </tr>'; 
                }
            }                  
            else{
                    $answer.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
            }
            
        $answer.='</tbody>
    </table>';

    return $answer;  
}

public function viewallaocbyoperation(Request $request) {
    $operation_type_id = strtoupper($request->get('operation'));

    $answer ='<table class="table table-bordered" id="exportTableData">
        <thead>
            <tr class="table-warning">
                <th width="5%"><b>#</b></th>
                <th><b>AOC Holder</b></th>
                <th width="12%"><b>A/C Type Operated</b></th>
                <th width="13%" class="center"><b>AOC Certificate No.</b></th>
                <th width="13%" class="center"><b>Issued Date</b></th>
                <th width="13%" class="center"><b>Validity Date</b></th>
                <th width="5%" class="center"><b>OPS Spec</b></th>
                <th width="5%" class="center"><b>PART G</b></th>
                <th width="7%"><b>Remarks</b></th>
                <th><b>Operation</b></th>
            </tr>
        </thead>
        <tbody>';
        //$aoclists = aoc::ORDERBY('issued_date', $issuedDate)->GET();
        $aoclists = DB::SELECT(DB::RAW('SELECT * FROM tbl_ncaa_assign_operation_spec_aocs a JOIN tbl_ncaa_acos b JOIN tbl_ncaa_operations c ON a.aoc_holder_id = b.id AND a.operation_type_id = c.id WHERE a.operation_type_id = '.$operation_type_id.''));

        $aocAircrafts = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.aircraft_maker_id, b.aircraft_maker FROM tbl_ncaa_aoc_aircrafts a JOIN tbl_ncaa_aircraft_makers b ON a.aircraft_maker_id = b.id '));

        $oprspecslists = DB::SELECT(DB::RAW('SELECT a.aoc_holder_id, a.operation_type_id, b.operation_type FROM tbl_ncaa_assign_operation_spec_aocs a JOIN tbl_ncaa_operations b ON a.operation_type_id = b.id'));

           $count = 0;
           $oprscounter = 0;
            if(count($aoclists)){
                foreach($aoclists as $aoc){
                    $count++;
                    date_default_timezone_set("Africa/Lagos");
                    $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                    if($aoc->remarks == 1){
                        $remarks = 'Active'; $color = 'green';
                    }
                    if($aoc->remarks == 2){
                        $remarks = 'Suspended'; $color = '#ffbf00';
                    }
                    if($aoc->remarks == 3){
                        $remarks = 'Expired'; $color = 'red';
                    }
                    if($aoc->remarks == 4){$remarks = 'Revoked';}

                    $converdatetotimeofvalidity = strtotime($aoc->validity); 
                    $validity = date('d/m/Y', $converdatetotimeofvalidity);

                    $converdatetotimeofissued = strtotime($aoc->issued_date); 
                    $issued_date = date('d/m/Y', $converdatetotimeofissued);

                    $answer.='<tr style="font-family:tahoma;" class='.$css_style.'>
                        <td>'.$count.'</td>
                        <td>'.strtoupper($aoc->aoc_holder).'</td>
                        <td>';
                            foreach($aocAircrafts as $acmaker){
                                if($acmaker->aoc_holder_id == $aoc->id){
                                    $answer.='<a href="/aircraft-list/'.str_slug($aoc->aoc_holder).'/'.$aoc->id.'/'.str_slug($acmaker->aircraft_maker).'/'.$acmaker->aircraft_maker_id.'" target="_blank">
                                        '.strtoupper($acmaker->aircraft_maker).', 
                                    </a>';
                                }
                            }
                        $answer.='</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->aoc_certificate.'" target="_blank">
                                '.$aoc->aoc_certificate_no.'
                            </a>
                        </td>
                        <td class="center">'.$issued_date.'</td>
                        <td class="center">'.$validity.'</td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->ops_specs.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' OPS Specs"></i>
                            </a>
                        </td>
                        <td class="center">
                            <a href="/confidentials/'.$aoc->part_g.'" target="_blank">
                                <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view '.$aoc->aoc_holder.' Part G"></i>
                            </a>
                        </td>
                        <td style="font-size:10px; line-height:15px; font-weight:bold; color:'.$color.'">'.$remarks.'</td>
                        <td>'.$aoc->operation_type.'</td>
                    </tr>'; 
                }
            }                  
            else{
                    $answer.='<tr>
                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                    </tr>';
            }
            
        $answer.='</tbody>
    </table>';

    return $answer;  
}
















}
