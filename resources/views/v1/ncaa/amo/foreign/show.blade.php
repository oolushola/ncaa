@extends('v1.ncaa.design-layout')

@section('title')Foreign AMO's @stop

@section('main')
    <div class="page-header">
        <button type="button" class="btn btn-gradient-primary btn-rounded btn-icon"  title="Download foreign AMO into excel sheet" id="downloadForeignAmo">
            <i class="mdi mdi-cloud-download"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-view-selection')}}">Back</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Foreign AMO's</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Foreign AMO Listings</h4>
                    <form method="POST" name="frmForeignAmobyStatus" id="frmForeignAmobyStatus">
                        <span style="float:right; font-size:12px; font-weight:bold; margin-top:-30px;">filter by status:
                            {!! csrf_field() !!}
                            <select id="chooseStatus" name="status">
                                <option value="0">Choose Status</option>
                                <option value="active">Active</option>
                                <option value="expire">Expired</option>
                                <option value="expiring soon">Expiring Soon</option>
                            </select>
                        </span>
                    </form>

                    @if(Auth::user()->role==3 || 1)
                        @if(count($checkforamoforeignlastupdate))
                            @foreach($checkforamoforeignlastupdate as $lastupdatedby)
                            <span style="float:right; font-size:11px; font-weight:bold; margin-right:30px; color:blue; padding-bottom:10px;">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif

                    <div class="table-responsive" id="contentDropper">              
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th><b>#</b></th>
                                    <th><b>AMO Holder</b></th>
                                    <th><b>Country</b></th>
                                    <th><b>MOE Reference</b></th>
                                    <th class="center"><b>Approvals</b></th>
                                    <th><b>Ratings/Capabilities</b></th>
                                    <th class="center"><b>AMO Number</b></th>
                                    <th width = "10%" class="center"><b>Expiry</b></th>
                                    <th><b>Days Left</b></th>
                                    <th class="center"><b>Status</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($allforAmos))
                                    <?php $count = 0;  ?>
                                    @foreach($allforAmos as $foreignAmo)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $foreignAmo->moe_reference=='' ? $moe = ' - ' : $moe = $foreignAmo->moe_reference;

                                        $amo = '<a href="">'.$foreignAmo->amo_number.'</a>';
                                        $now = time();
                                        $due_date = strtotime($foreignAmo->expiry);;
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
                                    
                                        
                                    ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$count}}</td>
                                        <td>{!! $foreignAmo->amo_holder !!}</td>
                                        <td>{!! $foreignAmo->country !!}</td>
                                        <td>{!! $moe !!}</td>
                                        <td>{!! $foreignAmo->approvals !!}</td>
                                        <td style="line-height:18px;">{!! $foreignAmo->ratings_capabilities !!}
                                        </td>
                                        <td  class="center">
                                            <a href="{{URL::asset('/confidentials/amo/foreign/'.$foreignAmo->amo.'')}}" target="_blank">
                                                    {!! $foreignAmo->amo_number !!}
                                                </a>
                                        </td>
                                        <td>{!! $foreignAmo->expiry !!}</td>
                                        <td>{{$numberofdays}}</td>
                                        <td style="background:{{$bgcolor}}; color:{{$color}}" class="center">{!! $status !!}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                    <td class="table-danger" colspan="11" style="font-size:11px;">No foreign AMO has been added yet.</td>
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
<script type="text/javascript" src="{{URL::asset('js/amo/amo-foreign.js')}}"></script>
@stop