@extends('v1.ncaa.design-layout')

@section('title') View all Type Acceptance Certificate @stop

@section('main')
    <div class="page-header">
        <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadTac"  title="Download TAC into excel sheet">
            <i class="mdi mdi-cloud-download"></i>
            Download Excel
        </button>

        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('type-acceptance-certificate')}}">TAC</a></li>
                <li class="breadcrumb-item active" aria-current="page">All TAC's</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">All TAC Listings</h4>
                    

                    
                    <br>
                    <div class="table-responsive" id="contentDropper">
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="">
                                    <th><b>#</b></th>
                                    <th><b>Aircraft Maker</b></th>
                                    <th><b>Aircraft Model</b></th>
                                    <th><b>TAC Acceptance Cert. No.</b></th>
                                    <th><b>Date Issued</b></th>
                                    <th><b>TC Holder</b></th>
                                    <th><b>Orginal Issued by</b></th>
                                    <th><b>TC NO.</b></th>
                                    <th><b>Remark</b></th>
                                    <th><b>Status</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($allTacs))
                                <?php $count = 0; ?>
                                @foreach($allTacs as $tac)
                                <?php 
                                    $count++;

                                    $now = time();
                                    $due_date = strtotime($tac->date_issued);;
                                    $datediff = $due_date - $now;
                                    $numberofdays = round($datediff / (60 * 60 * 24));

                                    if($numberofdays > 90 ){
                                        $status = "Active";
                                        $bgcolor = "green";
                                        $color = "#fff";
                                    }
                                    else if(($numberofdays >= 1) && ($numberofdays <=90)){
                                        $status = "Expiring soon";
                                        $bgcolor = "#ffbf00";
                                        $color = "#000";
                                    }
                                    else{
                                        $status = "Expired";
                                        $bgcolor = "red";
                                        $color = "#000";
                                    }

                                    date_default_timezone_set("Africa/Lagos");
                                    $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                    
                                    $dateIssued = strtotime($tac->date_issued);
                                    $date_issued = date('j/m/Y', $dateIssued);
                                ?>
                                <tr style="font-family:tahoma; " class="{{$css_style}}">
                                    <td style="font-size:11px;">{{$count}}</td>
                                    <td>{!! strtoupper($tac->aircraft_maker) !!} </td>
                                    <td>
                                        @foreach($aircraftModels as $aircraft_type)
                                            @if($tac->id == $aircraft_type->tac_id)
                                                {{ $aircraft_type->aircraft_type }}<br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{!! $tac->tc_acceptance_approval !!}</td>
                                    <td>{!! $date_issued !!}</td>
                                    <td>
                                        @foreach($aircraftMakers as $maker)
                                            @if($maker->id == $tac->tc_holder)
                                            {{ $maker->aircraft_maker }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{!! $tac->original_tc_issued_by !!}</td>
                                    <td class="center">{!! $tac->tc_no !!}</td>
                                    <td class="center">{!! $tac->remarks !!}</td>
                                    
                                    <td style="background:{{$bgcolor}}; color:{{$color}}">{!! $status !!}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="color:red" class="center" colspan="15" class="table-danger">No records available</td>
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
<script type="text/javascript" src="{{URL::asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/tac/tac.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/data-table.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#exportTableData').DataTable( {
        "order": [[ 3, "asc" ]],
        
    } );
});
</script>

@stop