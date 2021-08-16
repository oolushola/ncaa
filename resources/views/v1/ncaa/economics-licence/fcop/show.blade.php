@extends('v1.ncaa.design-layout')

@section('title')View all Air Transport Licence @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadFcop" title="Download FCOP into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('economic-licence/fcop')}}">FCOP</a></li>
                <li class="breadcrumb-item active" aria-current="page">view all FCOPs</li>
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
                    <form name="" id="">
                        {!! csrf_field() !!}
                        <!-- <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="operator" id="operator">
                                <option value="0">Operators</option>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </span> -->
                        <span style="font-size:12px; font-weight:bold; display:inline-block" > 
                            <select name="chooseStatus" id="chooseStatus">
                                <option value="0">Status</option>
                                <option value="active">Active</option>
                                <option value="expiringSoon">Expiring Soon</option>
                                <option value="expired">Expired</option>
                            </select>
                        </span>
                    </form>
                    <br>

                    <div class="table-responsive" id="contentDropper">            
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">FCOP Listings</h4>
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%">#</th>
                                    <th><b>Foreign Airline</b></th>
                                    <th><b>Licence No.</b></th>
                                    <th class="text-center"><b>FCOP Issue Date</b></th>
                                    <th class="center"><b>Part 18</b></th>
                                    <th class="center"><b>Part 10</b></th>
                                    <th class="center"><b>Part 17</b></th>
                                    <th class="center"><b>FCOP Status</b></th>
                                    <th class="font-weight-bold">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($fcopListings))
                                <?php $counter = 0; ?>
                                    @foreach($fcopListings as $fcop)
                                        <?php $counter++; 
                                         $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                         
                                        $issuedDate = strtotime($fcop->date_fcop_issued); 
                                        $dateIssued = date('d/m/Y', $issuedDate);
                                        $fcop->part_18 == 0 ? $part18 = 'No' : $part18 = 'Yes';
                                        $fcop->part_10 == 0 ? $part10 = 'No' : $part10 = 'Yes';
                                        $fcop->part_17 == 0 ? $part17 = 'No' : $part17 = 'Yes';
                                        $fcop->fcop_status == 0 ? $status = 'Inactive' : $status = 'Active';
                                            
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($fcop->foreign_airline) !!}</td>
                                        <td>
                                            <a href="{{URL::asset('/confidentials/economic-licence/'.$fcop->fcop_certificate.'')}}" target="_blank">
                                                {!! $fcop->licence_no !!}
                                            </a>
                                        </td>
                                        <td class="text-center">{!! $dateIssued !!}</td>
                                        <td class="text-center">{!! $part18 !!}</td>
                                        <td class="center">{!! $part10 !!}</td>
                                        <td class="center">{!! $part17 !!}</td>
                                        <td class="center">{!! $status !!}</td>
                                        <td>{{ $fcop->comments }}</td>
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/fcop.js')}}"></script>
<script type="text/javascript">
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop