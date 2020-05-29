@extends('v1.ncaa.design-layout')

@section('title')View all Permit for Aerial Aviation Service @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadPaas" title="Download PAAS into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('economic-licence/paas')}}">PAAS</a></li>
            <li class="breadcrumb-item active" aria-current="page">view all PAAS</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Permit for Aerial Aviation Service Listings</h4>
                    
                    
                    <form name="" id="">
                        {!! csrf_field() !!}
                        
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="operator" id="operator">
                                <option value="0">Operators</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="remarks" id="chooseStatus">
                                <option value="0">Status</option>
                                <option value="active">Active</option>
                                <option value="expiringSoon">Expiring Soon</option>
                                <option value="expired">Expired</option>
                            </select>
                        </span>
                    </form>
                    <br>

                    <div class="table-responsive" id="contentDropper">            
                        <table class="table table-bordered" id="exportTableData">
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
                            <tbody>
                                @if(count($paaslistings))
                                <?php $counter = 0; ?>
                                    @foreach($paaslistings as $paas)
                                        <?php $counter++; 
                                         $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                         
                                        $now = time();
                                        $due_date = strtotime($paas->date_of_expiry);;
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
                                        $issuedDate = strtotime($paas->date_of_first_issue); 
                                        $dateIssued = date('d/m/Y', $issuedDate);
                                        
                                        $renewalDate = strtotime($paas->date_of_renewal); 
                                        $renewed = date('d/m/Y', $renewalDate);

                                        $dateExpired = strtotime($paas->date_of_expiry); 
                                        $expired = date('d/m/Y', $dateExpired);
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($paas->operator) !!}</td>
                                        <td>
                                            <a href="{{URL::asset('/confidentials/economic-licence/'.$paas->paas_certificate.'')}}" target="_blank">
                                                {!! $paas->licence_no !!}
                                            </a>
                                        </td>
                                        <td class="text-center">{!! $dateIssued !!}</td>
                                        <td class="text-center">{!! $renewed !!}</td>
                                        <td class="center">{!! $expired !!}</td>
                                        <td style="text-align:center; background:{{$bgcolor}}; color:{{$color}};">
                                            {!! $remarks !!}
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/paas.js')}}"></script>
@stop