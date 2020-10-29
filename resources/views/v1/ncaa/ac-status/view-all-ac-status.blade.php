@extends('v1.ncaa.design-layout')

@section('title')View all Aircraft Status @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadAircraftStatus" title="Download Aircraft Status into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">A/C Status</a></li>
            <li class="breadcrumb-item active" aria-current="page">view all aircraft status</li>
            </ol>
            <button type="button" class="btn btn-gradient-danger btn-icon-text" id="sendToPrint">
                <i class="mdi mdi-printer" title="Print"></i>
                PRINT
            </button>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    @if(Auth::user()->role==3 || 1)
                        @if(count($checkforaircraftstatuslastupdate))
                            @foreach($checkforaircraftstatuslastupdate as $lastupdatedby)
                            <span style="font-size:11px; font-weight:bold; margin-left:10px; color:blue; float:right; margin-right:20px;">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif
                    
                    <form name="frmgetAircraftByAoc" id="frmgetAircraftByAoc">
                        {!! csrf_field() !!}
                        <span style="font-size:12px; font-weight:bold; display:inline-block;">
                            <select name="aoc_holder_id" id="aocholderid" style="width:150px;">
                                <option value="0">Registered Operator</option>
                                @foreach($allaocs as $aoc)
                                    <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                                @endforeach
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" id="aircraftMakerDropper"> 
                            <select id="aircraftType" name="aircraftType">
                                <option value="0">Aircraft Type</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="registration_marks" id="registration_marks">
                                <option value="0">Registration Marks</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="remarks" id="remarks">
                                <option value="0">Choose remarks</option>
                                <option value="active">Active</option>
                                <option value="expiringSoon">Expiring Soon</option>
                                <option value="expired">Expired</option>
                            </select>
                        </span>
                    </form>
                    <br>

                    <div class="table-responsive" id="contentDropper">  
                        <h4 class="card-title" style="padding-left:10px; display:inline-block">A/C Status Listings</h4>          
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%">#</th>
                                    <th width="15%"><b>Registered Operator</b></th>
                                    <th width="5%"><b>Registration Marks</b></th>
                                    <th width="7%"><b>Aircraft Type</b></th>
                                    <th width="5%" class="center"><b>Aircraft Serial Number</b></th>
                                    <th width="5%" class="center"><b>Year of Mnaufacture</b></th>
                                    <th width="8%" class="center"><b>Current Registration Date</b></th>
                                    <th width="20%"><b>Registered Owner</b></th>
                                    <th width="12%" c;lass="center"><b>C of A Status</b></th>
                                    <th><b>Remarks</b></th>
                                    <th><b>Weight (Kg)</b></th>
                                    <th><b>Major Checks</b></th>
                                    <th><b>Serviceability Status</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($allAircraftStatus))
                                <?php $counter = 0; ?>
                                    @foreach($allAircraftStatus as $aircraft)
                                        <?php $counter++; 
                                         $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        
                                        if($aircraft->c_of_a_status) {
                                            $now = time();
                                            $due_date = strtotime($aircraft->c_of_a_status);;
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
                                        }
                                        else {
                                                $bgcolor = "";
                                                $color = "#000";
                                                $remarks = "";
                                                $numberofdays = $aircraft->c_of_a_status;
                                                
                                        }
                                        $converdatetotimeofregdate = strtotime($aircraft->registration_date); 
                                        $current_registration_date = date('d/m/Y', $converdatetotimeofregdate);
                                        
                                        if($aircraft->c_of_a_status) {
                                            $converdatetotimeofcofastatus = strtotime($aircraft->c_of_a_status); 
                                            $cofastatus = date('d/m/Y', $converdatetotimeofcofastatus);
                                        }
                                        else{
                                            $cofastatus = '<span class="font-weight-bold">NA</span>';
                                        }
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($aircraft->aoc_holder) !!}</td>
                                        <td>{!! $aircraft->registration_marks !!}</td>
                                        <td>{!! $aircraft->aircraft_type !!}</td>
                                        <td>{!! $aircraft->aircraft_serial_number !!}</td>
                                        <td class="center">{!! $aircraft->year_of_manufacture !!}</td>
                                        <td class="center">{!! $current_registration_date !!}</td>
                                        <td>{!! $aircraft->registered_owner !!}</td>
                                        <td style="text-align:center; background:{{$bgcolor}}; color:{{$color}};">
                                            <a href="{{URL::asset('/confidentials/c-of-a/'.$aircraft->c_of_a.'')}}" target="_blank"style="color:{{$color}}">
                                                    {!! $cofastatus !!}
                                                </a>
                                        </td>
                                        <td>{{$remarks}}</td>
                                        <td>{!! $aircraft->weight !!}</td>
                                        <td>{!! $aircraft->major_checks !!}</td>
                                        <td>{!! $aircraft->aircraft_serviceability_status !!}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                                    </tr>
                                @endif
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/aircraft-status/aircraft.js')}}"></script>
<script>
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop