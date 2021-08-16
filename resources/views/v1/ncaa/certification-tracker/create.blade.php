@extends('v1.ncaa.design-layout')

@section('title')Certification Tracker @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Certification Tracker
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('certification-tracker/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Certification Tracker</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                @if(isset($recid)) 
                <form id="frmCertificationTracker" method="POST" action="{{URL('certification-tracker',$recid->id)}}" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                @else
                <form id="frmCertificationTracker" method="POST" action="{{URL('certification-tracker')}}" enctype="multipart/form-data">
                @endif
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="labelholder">Certification Number *</label>
                        <input type="text" class="form-control" id="certificationNo" name="certification_no" value="@if(isset($recid)){{$recid->certification_no}}@else{{old('certification_no')}}@endif" />
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Date Assigned *</label>
                        <input name="date_assigned" id="dateAssigned" type="date" value="@if(isset($recid)){{$recid->date_assigned}}@else{{old('date_assigned')}}@endif" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Applicant's Name *</label>
                        <input type="text" name="applicant_name" id="applicantsName" class="form-control" value="@if(isset($recid)){{$recid->applicant_name}}@else{{old('applicant_name')}}@endif"> 
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Certification Type *</label>
                        <input type="text" name="certification_type" id="certificationType" class="form-control" value="@if(isset($recid)){{$recid->certification_type}}@else{{old('certification_type')}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">CPM *</label>
                        <select type="date" name="cpm" id="cpm" class="form-control">
                            <option>Choose CPM</option>
                            @foreach($cpms as $cpm) 
                            @if(isset($recid) && $recid->cpm == $cpm->full_name)
                            <option selected value="{{$cpm->full_name}}">{{ $cpm->full_name }}</option>
                            @else
                            <option value="{{$cpm->full_name}}">{{ $cpm->full_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Team Members *</label>
                        <select type="date" name="team_member" id="team_member" class="form-control">
                            <option value="0">Choose Team Members</option>
                            @foreach($teamMembers as $teamMember) 
                            @if(isset($recid) && $recid->team_member == $teamMember->full_name)
                            <option selected value="{{$teamMember->full_name}}">{{ $teamMember->full_name }}</option>
                            @else
                            <option value="{{$teamMember->full_name}}">{{ $teamMember->full_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Start Date *</label>
                        <input type="date" name="start_date" id="startDate" class="form-control" value="@if(isset($recid)){{$recid->start_date}}@else{{old('start_date')}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Completion Date *</label>
                        <input type="date" name="completion_date" id="completionDate" class="form-control" value="@if(isset($recid)){{$recid->completion_date}}@else{{old('completion_date')}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Status *</label>
                        <select type="date" name="status" id="status" class="form-control" >
                            <option value="">Choose Status</option>
                            <option value="Phase 1" @if(isset($recid) && $recid->status == "Phase 1") selected @endif>Phase 1</option>
                            <option  value="Phase 2" @if(isset($recid) && $recid->status == "Phase 2") selected @endif>Phase 2</option>
                            <option  value="Phase 3" @if(isset($recid) && $recid->status == "Phase 3") selected @endif>Phase 3</option>
                            <option  value="Phase 4" @if(isset($recid) && $recid->status == "Phase 4") selected @endif>Phase 4</option>
                            <option  value="Phase 5" @if(isset($recid) && $recid->status == "Phase 5") selected @endif>Phase 5</option>
                            <option  value="Active" @if(isset($recid) && $recid->status == "Active") selected @endif>Active</option>
                            <option  value="Completed" @if(isset($recid) && $recid->status == "Completed") selected @endif>Completed</option>
                            <option  value="Inactive" @if(isset($recid) && $recid->status == "Inactive") selected @endif>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Type of Aircraft *</label>
                        <select class="form-control" name="aircraft_type" id="aircraftType">
                            <option>Choose Aircraft Type</option>
                            @foreach($aircraftTypes as $aircraft)
                            @if(isset($recid) && $recid->aircraft_type == $aircraft->aircraft_type)
                            <option selected value="{{$aircraft->aircraft_type}}">{{$aircraft->aircraft_type}}</option>
                            @else
                            <option value="{{$aircraft->aircraft_type}}">{{$aircraft->aircraft_type}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                    <input type="hidden" name="ftype" id="ftype" value="pdf" />

                    <div id="loader"></div>
                    
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    @if(isset($recid))        
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateForeingAirlineDacl">UPDATE</button>
                    @else
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addForeingAirlineDacl">SAVE</button>
                    @endif
                    <button class="btn btn-light">Cancel</button>
                    @endif
                </form>
            </div>
            </div>
        </div>
        <div class="col-md-7 grid-margin">
            <div class="card">
                <div class="card-body" style="padding:4px;">
                    <h4 class="card-title">Preview Pane <span id="deleteLoader"></span></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead class="table-info">
                                <tr class="table-warning">
                                    <th colspan="8" style="font-size:11px; font-weight:bold">Certification Tracker Lists</th>
                                    {!! $certificationTrackers->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Certification No.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Date Assigned</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Applicant's Name</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Status</th>
                                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                                    <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                                    <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Delete</th>
                                    @endif
                                    @if(Auth::user()->role == 1)
                                    <th width="5%" style="font-size:11px; font-weight:bold;">Created By?</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @if(count($certificationTrackers))
                                <?php $count = 0; ?>
                                    @foreach($certificationTrackers as $certificationTracker)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                                        $cdateAssigned = strtotime($certificationTracker->date_assigned);
                                        $dateAssigned = date('d/m/Y', $cdateAssigned);
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{$certificationTracker->certification_no}}</td>
                                            <td>{{$dateAssigned}}</td>
                                            <td>{{$certificationTracker->applicant_name}}</td>
                                            <td>{{$certificationTracker->status}}</td>

                                            @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                                            <td style="color:blue" class="center">
                                                <a href="{{URL('certification-tracker/'.$certificationTracker->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                            <td style="color:red" class="center">
                                                <form method="POST" name="frmDeleteCertificationTracker" id="frmDeleteCertificationTracker">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteCertificationTracker" style="font-size:25px; cursor:pointer" value="{{$certificationTracker->id}}" title="Delete {{$certificationTracker->certification_no}}"></i>
                                                </form>
                                            </td>
                                            @endif
                                            @if(Auth::user()->role == 1)
                                            <td>{{$certificationTracker->created_by}}</td>
                                            @endif
                                            
                                        </tr>
                                    @endforeach
                                @else
                                <tr class="table-danger">
                                    <td colspan="8" style="font-size:11px; font-weight:bold">No record yet.</td>
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
<script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/certification-tracker.js?v=').time()}}"></script>
@stop