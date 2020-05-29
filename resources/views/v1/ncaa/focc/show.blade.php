@extends('v1.ncaa.design-layout')

@section('title') View all FOCC @stop

@section('main')
    <div class="page-header">
        <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadFocc"  title="Download FOCC into excel sheet">
            <i class="mdi mdi-cloud-download"></i>
            Download Excel
        </button>

        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('focc')}}">FOCC & MCC </a></li>
                <li class="breadcrumb-item active" aria-current="page">All FOCC & MCC</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">All FOCC & MCC Listings</h4>
                    @if(Auth::user()->role==3 || 1)
                        @if(count($checkforfoccupdates))
                            @foreach($checkforfoccupdates as $lastupdatedby)
                            <span style="float:right; font-size:11px; font-weight:bold; margin-right:30px; color:blue">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif

                    <form method="POST" name="frmSortFocc" id="frmSortFocc">
                        {!! csrf_field() !!}
                        <input type="hidden" name="criteria" value="" id="selectorCriteria">
                        <input type="hidden" name="direction" value="" id="direction">
                        <span style="font-size:11px; padding-left:10px; color:green">sort by:
                            <select id="amoHolder" id='amoHolder'>
                                <option value="0">AMO Holder</option>
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:11px; padding-left:10px; color:green">
                            <select id="regNumber" id='regNumber'>
                                <option value="0">Aircraft Reg Number</option>
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:11px; padding-left:10px; color:green">
                            <select id="chooseStatus" name="status">
                                <option value="0">Status</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="expiring soon">Expiring Soon</option>
                            </select>
                        </span>
                    </form>
                    <br>
                    <div class="table-responsive" id="contentDropper">
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th><b>#</b></th>
                                    <th><b>Operator</b></th>
                                    <th width="10%"><b>FOCC No.</b></th>
                                    <th width="10%"><b>MCC No.</b></th>
                                    <th width="10%"><b>State of Registry</b></th>
                                    <th width="10%"><b>Registered Owner</b></th>
                                    <th width="10%"><b>A/C Type</b></th>
                                    <th width="10%"><b>Aircraft Reg. No</b></th>
                                    <th width="10%" class="center"><b>Date of first Issue</b></th>
                                    <th width="10%" class="center"><b>Renewal Date</b></th>
                                    <th width="10%" class="center"><b>Expiry</b></th>
                                    <th><b>Status</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($allfoccs))
                                    <?php $count = 0; ?>
                                    @foreach($allfoccs as $focc)
                                        <?php $count++;

                                            

                                            $now = time();
                                            $due_date = strtotime($focc->valid_till);;
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
                                            if($focc->amo_holder_status){
                                                $additionStyle = "";
                                            }
                                            else{
                                                $additionStyle = "<span class='mdi mdi-close-octagon-outline' style='font-size:25px;color:red; margin-top:0px; float:right' title='This Operator Licence has been revoked'></span>";
                                            }
                                            
                                            $converdatetotimeoffirstissue = strtotime($focc->date_of_first_issue); 
                                            $date_of_first_issue = date('j/m/Y', $converdatetotimeoffirstissue);

                                            $converdatetotimeofrenewal = strtotime($focc->renewal); 
                                            $renewal = date('j/m/Y', $converdatetotimeofrenewal);

                                            $converdatetotimeofvalidtill = strtotime($focc->valid_till); 
                                            $valid_till = date('j/m/Y', $converdatetotimeofvalidtill);
                                            
                                        ?>
                                        <tr style="font-family:tahoma; {!! $additionStyle !!} " class="{{$css_style}}">
                                            <td style="font-size:11px;">{{$count}}</td>
                                            <td>{!! strtoupper($focc->operator) !!} {!! $additionStyle !!}</td>
                                            <td>{!! $focc->focc_no !!}</td>
                                            <td>{!! $focc->mcc_no !!}</td>
                                            <td>{!! $focc->state_of_registry !!}</td>
                                            <td>{!! $focc->registered_owner !!}</td>
                                            <td>{!! $focc->aircraft_type !!}</td>
                                            <td class="center">{!! $focc->foreign_registration_marks !!}</td>
                                            <td class="center">{!! $date_of_first_issue !!}</td>
                                            <td class="center">{!! $renewal !!}</td>
                                            <td class="center">{!! $valid_till !!}</td>
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
<script type="text/javascript" src="{{URL::asset('js/focc/focc.js')}}"></script>
@stop