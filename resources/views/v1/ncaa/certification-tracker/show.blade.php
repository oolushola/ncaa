@extends('v1.ncaa.design-layout')

@section('title')View all Certification Tracker @stop

@section('main')
    <div class="page-header">
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="downloadCertificationTracker" title="Download Certification Tracker into EXCEL SHEET">
                <i class="mdi mdi-cloud-download"></i>
                Download Excel
            </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('certification-tracker')}}">Certification Tracker</a></li>
                <li class="breadcrumb-item active" aria-current="page">View all certification tracker</li>
            </ol>
            <button type="button" class="btn btn-gradient-primary btn-icon-text" id="sendToPrint">
                <i class="mdi mdi-printer" title="Print"></i>
                PRINT
            </button>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin">
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
                                <option value="active">Phase 1</option>
                                <option value="expiringSoon">Phase 2</option>
                                <option value="expired">Phase 3</option>
                                <option value="expired">Phase 4</option>
                                <option value="expired">Phase 5</option>
                                <option value="expired">Active </option>
                                <option value="expired">Completed</option>
                                <option value="expired">Inactive</option>
                            </select>
                        </span>
                    </form>
                    <br>

                    <div class="table-responsive" id="contentDropper">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">Certification Tracker Listings</h4>
            
                        <table class="table table-bordered" id="exportTableData">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%">#</th>
                                    <th><b>Certification No.</b></th>
                                    <th><b>Date Assigned</b></th>
                                    <th class="text-center"><b>Applicant's Name</b></th>
                                    <th class="center"><b>Certification Type</b></th>
                                    <th class="center"><b>CPM</b></th>
                                    <th class="center"><b>Team Members</b></th>
                                    <th class="center"><b>Start Date</b></th>
                                    <th class="center"><b>Completion Date</b></th>
                                    <th class="center"><b>Status</b></th>
                                    <th><b>Type of Aircraft</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($certificationTrackerListings))
                                <?php $counter = 0; ?>
                                    @foreach($certificationTrackerListings as $certificationTracker)
                                        <?php $counter++; 
                                            $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';

                                        $dateAssigned = strtotime($certificationTracker->date_assigned); 
                                        $assignedDate = date('d/m/Y', $dateAssigned);
                                        $startDate_ = strtotime($certificationTracker->start_date);
                                        $startDate = date('d/m/Y', $startDate_);
                                        $completionDate = strtotime($certificationTracker->completion_date); 
                                        $completionDate_ = date('d/m/Y', $completionDate); 
                                        ?>
                                    <tr class="{{$css_style}}">
                                        <td>{{$counter}}</td>
                                        <td>{!! strtoupper($certificationTracker->certification_no) !!}</td>
                                        <td class="text-center">{!! $assignedDate !!}</td>
                                        <td>{{ $certificationTracker->applicant_name }}</td>
                                        <td>{{ $certificationTracker->certification_type }}</td>
                                        <td>{{ $certificationTracker->cpm }}</td>
                                        <td>{{ $certificationTracker->team_member }}</td>
                                        <td class="text-center">{!! $startDate !!}</td>
                                        <td class="center">{!! $completionDate_ !!}</td>
                                        <td>{{ $certificationTracker->status }}</td>
                                        <td>{{ $certificationTracker->aircraft_type }}</td>
                                        
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
<script type="text/javascript" src="{{URL::asset('js/foreignAirlineDacl.js')}}"></script>
<script type="text/javascript">
    $('#sendToPrint').click(function() {
        $.print('#contentDropper')
    })
</script>
@stop