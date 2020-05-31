@extends('v1.ncaa.design-layout')

@section('title') View all AOC @stop

@section('main')
    <div class="page-header">
        @if($checkforaocupdates)
            
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadAOC">
                <i class="mdi mdi-cloud-download" title="Download AOC to EXCEL SHEET"></i>
                Download Excel
            </button>
        @endif
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">AOC</a></li>
                <li class="breadcrumb-item active" aria-current="page">view all aoc</li>
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
                        @if(count($checkforaocupdates))
                            @foreach($checkforaocupdates as $lastupdatedby)
                            <span style="float:right; font-size:11px; font-weight:bold; margin-right:30px; color:blue">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif

                    <form name="frmSortAoc" id="frmSortAoc">
                        {!! csrf_field() !!}
                        <span style="font-size:11px; padding-left:10px; color:green">sort by: </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block">
                            <select name="remarks" id="remarks" >
                                <option value="0">Remarks</option>
                                <option value="1">Active</option>
                                <option value="2">Suspended</option>
                                <option value="3">Expired</option>
                                <option value="4">Revoked</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block"> 
                            <select name="issuedDate" id="issuedDate">
                                <option value="0">Issued Date</option>
                                <option value="asc">Ascending Order</option>
                                <option value="desc">Descending Order</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block"> 
                            <select name="operation" id="operation">
                                <option value="0">Operation</option>
                                @foreach($operations as $operation)
                                    <option value="{!! $operation->id !!}">{!! $operation->operation_type !!}</option>
                                @endforeach
                            </select>
                        </span>
                    </form>
                    <br>
                    
                    <div class="table-responsive" id="contentDropper">
                        <h4 class="card-title" style="padding-left:10px; display:inline-block">All AOC Listings</h4>
                        <table class="table table-bordered" id="exportTableData">
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
                            <tbody>
                                @if(count($aoclists))
                                    <?php $count = 0;
                                        $oprscounter = 0;
                                    ?>
                                    @foreach($aoclists as $aoc)
                                        <?php $count++;
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

                                            $now = time();
                                            $due_date = strtotime($aoc->validity);;
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
                                            
                                        ?>
                                        <tr style="font-family:tahoma;" class="{{$css_style}}">
                                            <td>{{$count}}</td>
                                            <td>{!! strtoupper($aoc->aoc_holder) !!}</td>
                                            <td>
                                                @foreach($aocAircrafts as $acmaker)
                                                    @if($acmaker->aoc_holder_id == $aoc->id)
                                                        <a href="{{URL('/aircraft-list/'.str_slug($aoc->aoc_holder).'/'.$aoc->id.'/'.str_slug($acmaker->aircraft_maker).'/'.$acmaker->aircraft_maker_id)}}" target="_blank">
                                                            {{ strtoupper($acmaker->aircraft_maker)}},
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="center">
                                                <a href="{{URL::asset('/confidentials/'.$aoc->aoc_certificate)}}" target="_blank">
                                                    <!-- <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view {{$aoc->aoc_holder}} certificate"></i> -->
                                                    {{$aoc->aoc_certificate_no}}
                                                </a>
                                            </td>
                                            <td class="center">{!! $issued_date !!}</td>
                                        <td class="center" style="background:{{$bgcolor}}; color:{{$color}}; font-weight:bold">{!! $validity !!}</td>
                                            <td class="center">
                                                <a href="{{URL::asset('/confidentials/'.$aoc->ops_specs)}}" target="_blank">
                                                    <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view {{$aoc->aoc_holder}} OPS Specs"></i>
                                                </a>
                                            </td>
                                            <td class="center">
                                                <a href="{{URL::asset('/confidentials/'.$aoc->part_g)}}" target="_blank">
                                                    <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view {{$aoc->aoc_holder}} Part G"></i>
                                                </a>
                                            </td>
                                            <td style="font-size:10px; line-height:15px; font-weight:bold; color:{{$color}}">{!! $remarks !!}</td>
                                            <td>
                                                
                                                @foreach($oprspecslists as $oprspecs)
                                                    @if($aoc->id == $oprspecs->aoc_holder_id)
                                                        {!! strtoupper($oprspecs->operation_type) !!},
                                                    @endif
                                                @endforeach
                                            </td>
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
<script type="text/javascript" src="{{URL::asset('js/aoc/aoc.js')}}"></script>
<script>
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop