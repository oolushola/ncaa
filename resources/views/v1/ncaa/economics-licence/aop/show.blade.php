@extends('v1.ncaa.design-layout')

@section('title')View all Airline Operating Permit @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadAOP" title="Download Airline Operating Permit into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('economic-licence/aop')}}">Airline Operating Permit</a></li>
                <li class="breadcrumb-item active" aria-current="page">view all</li>
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
                    
                    
                    <form name="frmSortAop" id="frmAop">
                        {!! csrf_field() !!}
                        
                        
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="operator" id="operator">
                                <option value="0">Operators</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </span>
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="status" id="chooseStatus">
                                <option value="0">Status</option>
                                <option value="active">Active</option>
                                <option value="expiringSoon">Expiring Soon</option>
                                <option value="expired">Expired</option>
                            </select>
                        </span>
                    </form>
                    <br>

                    <div class="table-responsive" id="contentDropper">            
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Airline Operating Permit Listings</h4>
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%">#</th>
                                    <th><b>Operators</b></th>
                                    <th><b>Licence No.</b></th>
                                    <th class="center"><b>Date of Initial Issue</b></th>
                                    <th class="center"><b>Date of Last Renewal</b></th>
                                    <th class="center"><b>Date of Expiry</b></th>
                                    <th class="center"><b>Status</b></th>
                                    <th><b>Comment</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($aoplistings))
                                <?php $counter = 0; ?>
                                    @foreach($aoplistings as $aop)
                                        <?php $counter++; 
                                         $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                         
                                        $now = time();
                                        $due_date = strtotime($aop->date_of_expiry);;
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
                                        $issuedDate = strtotime($aop->date_of_first_issue); 
                                        $dateIssued = date('d/m/Y', $issuedDate);
                                        
                                        $renewalDate = strtotime($aop->date_of_renewal); 
                                        $renewed = date('d/m/Y', $renewalDate);

                                        $dateExpired = strtotime($aop->date_of_expiry); 
                                        $expired = date('d/m/Y', $dateExpired);
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($aop->operator) !!}</td>
                                        <td>
                                            <a href="{{URL::asset('/confidentials/economic-licence/'.$aop->aop_certificate.'')}}" target="_blank">
                                                {!! $aop->licence_no !!}
                                            </a>
                                        </td>
                                        <td class="text-center">{!! $dateIssued !!}</td>
                                        <td class="text-center">{!! $renewed !!}</td>
                                        <td class="center">{!! $expired !!}</td>
                                        <td style="text-align:center; background:{{$bgcolor}}; color:{{$color}};">
                                            {!! $remarks !!}
                                        </td>
                                        <td>{{$aop->comment}}</td>
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/aop.js')}}"></script>
<script type="text/javascript">
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop