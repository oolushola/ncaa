@extends('v1.ncaa.design-layout')

@section('title')View all Foreign Airline DACL @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadDacl" title="Download DACL into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('foreign-airline-dacl')}}">Foreign Airline Dacl</a></li>
                <li class="breadcrumb-item active" aria-current="page">view all Dacl's</li>
            </ol>
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="sendToPrint">
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
                        @if(count($checkfordaclupdates))
                            @foreach($checkfordaclupdates as $lastupdatedby)
                            <span style="float:right; font-size:11px; font-weight:bold; margin-right:30px; color:blue">
                                Last updated by: {!! $lastupdatedby->name !!}
                            </span>
                            @endforeach                            
                        @endif
                    @endif
                    
                    <form name="" id="">
                        {!! csrf_field() !!}
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="chooseStatus" id="chooseStatus">
                                <option value="0">Status</option>
                                <option value="active">Active</option>
                                <option value="expiringSoon">Expiring Soon</option>
                                <option value="expired">Expired</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="sortCountry" id="sortCountry">
                                <option value="0">Choose Country</option>
                                @foreach($countries as $country)
                                <option value="{{$country->country}}">{{$country->country}}</option>
                                @endforeach
                            </select>
                        </span>
                    </form>
                    <br>
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Foreign Airline Dacl Listings</h4>

                    <div class="table-responsive" id="contentDropper">
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%">#</th>
                                    <th><b>Foreign Airline Name</b></th>
                                    <th><b>Dacl No.</b></th>
                                    <th class="text-center"><b>DACL Issue Date</b></th>
                                    <th class="center"><b>Foreign AOC & Ops Spec</b></th>
                                    <th class="center"><b>AOC Expiry Date</b></th>
                                    <th class="center"><b>Country</b></th>
                                    <th class="center"><b>Status</b></th>
                                    <th><b>Comment</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($dacllistings))
                                <?php $counter = 0; ?>
                                    @foreach($dacllistings as $dacl)
                                        <?php $counter++; 
                                         $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                         
                                        $now = time();
                                        $due_date = strtotime($dacl->aoc_expiry_date);;
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
                                        $issuedDate = strtotime($dacl->dacl_issue_date); 
                                        $dateIssued = date('d/m/Y', $issuedDate);
                                        
                                        $dateExpired = strtotime($dacl->aoc_expiry_date); 
                                        $expired = date('d/m/Y', $dateExpired);
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($dacl->airline_name) !!}</td>
                                        <td>
                                            <a href="{{URL::asset('/confidentials/foreign-airline/'.$dacl->dacl_certificate.'')}}" target="_blank">
                                                {!! $dacl->dacl_no !!}
                                            </a>
                                        </td>
                                        <td class="text-center">{!! $dateIssued !!}</td>
                                        <td>
                                            <a href="{{URL::asset('confidentials/foreign-airline/'.$dacl->aoc_opspec.'')}}" target="_blank">
                                            <i class="mdi mdi-file-pdf" style="color:black; font-size:20px;" title="click to view {{$dacl->airline_name}} ops spec certificate"></i>
                                            </a>
                                        </td>
                                        <td class="center">{!! $expired !!}</td>
                                        <td>{{ $dacl->country }}</td>
                                        <td style="text-align:center; background:{{$bgcolor}}; color:{{$color}};">
                                            {!! $remarks !!}
                                        </td>
                                        <td>{{ $dacl->remarks }}</td>
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
<script type="text/javascript" src="{{URL::asset('js/foreignAirlineDacl.js?v=').time()}}"></script>
<script type="text/javascript">
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop