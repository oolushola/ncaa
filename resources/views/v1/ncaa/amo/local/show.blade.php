@extends('v1.ncaa.design-layout')

@section('title')Local AMO's @stop

@section('main')
    <div class="page-header">
        
        <button type="button" class="btn btn-gradient-primary btn-rounded btn-icon"  title="Download local AMO into excel sheet" id="downloadLocalAMO">
            <i class="mdi mdi-cloud-download"></i>
        </button>
       
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-view-selection')}}">Back</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Local AMO's</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Local AMO Listings</h4>
                    <form method="POST" name="frmgetlocalamobystatus" id="frmgetlocalamobystatus">
                        {!! csrf_field() !!}
                        <span style="float:right; font-size:12px; font-weight:bold; margin-top:-30px;">filter by status:
                            <select id="chooseStatus" name="status">
                                <option value="0">Choose Status</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="expiring soon">Expiring Soon</option>
                            </select>
                        </span>
                    </form>

                    @if(Auth::user()->role==3 || 1)
                        @if(count($checkforamolocalupdates))
                            @foreach($checkforamolocalupdates as $lastupdatedby)
                            <span style="float:right; font-size:11px; font-weight:bold; margin-right:30px; color:blue; padding-bottom:10px;">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif

                    <div class="table-responsive" id="contentDropper">              
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning" style="border-top:1px solid #ccc">
                                    <th>#</th>
                                    <th width="12%"><b>AMO Holder</b></th>
                                    <th width="11%"><b>Amo Approval</b></th>
                                    <th><b>Ratings/Capabilities</b></th>
                                    <th><b>Maintenance Locations</b></th>
                                    <th width="10%" class="center"><b>Expiry</b></th>
                                    <th class="center"><b>Status</b></th>
                                    <th width="12%" class="center"><b>APRVL PG & LEP</b></th>
                                    <th><b>Days Left</b></th>
                                    <th class="center"><b>Extention</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($amolocalists))
                                    <?php $count = 0;  ?>
                                    @foreach($amolocalists as $localAmo)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $now = time();
                                        $due_date = strtotime($localAmo->expiry);;
                                        $datediff = $due_date - $now;
                                        $numberofdays = round($datediff / (60 * 60 * 24));

                                        if($numberofdays > 90 ){
                                            $status = "Active";
                                            $bgcolor = "green";
                                            $color = "#fff";
                                        }
                                        else if(($numberofdays >= 0) && ($numberofdays <=90)){
                                            $status = "Expiring soon";
                                            $bgcolor = "#ffbf00";
                                            $color = "#000";
                                        }
                                        else{
                                            $status = "Expired";
                                            $bgcolor = "red";
                                            $color = "#000";
                                        }
                                    
                                        
                                    ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$count}}</td>
                                        <td>{!! strtoupper($localAmo->aoc_holder) !!}</td>
                                        <td>
                                            <a href="{{URL::asset('/confidentials/amo/local/'.$localAmo->amo_approval_number_file.'')}}" target="_blank">
                                                {!! $localAmo->amo_approval_number !!}
                                            </a>
                                        </td>
                                        <td>{!! $localAmo->ratings_capabilities !!}</td>
                                        <td >{!! $localAmo->maintenance_locations !!}</td>
                                        <td style="line-height:18px" class="center">{!! $localAmo->expiry !!}</td>
                                        <td style="background:{{$bgcolor}}; color:{{$color}}" class="center">{!! $status !!}</td>
                                        <td >
                                            <a href="{{URL::asset('/confidentials/amo/local/'.$localAmo->amo_pm_aprvl_pg_lep_file.'')}}" target="_blank">
                                                    {!! $localAmo->amo_pm_aprvl_pg_lep !!}
                                                </a>
                                            
                                        </td>
                                        <td >{{$numberofdays}}</td>
                                        <td class="center">{!! $localAmo->extention !!}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td class="table-danger" colspan="11" style="font-size:11px;">No local AMO has been added yet.</td>
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
<script type="text/javascript" src="{{URL::asset('js/amo/amo-local.js')}}"></script>
@stop