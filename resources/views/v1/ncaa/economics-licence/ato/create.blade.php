@extends('v1.ncaa.design-layout')

@section('title')Approved Training Organizations @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Approved Training Organizations
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('economice-licence/ato/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add ATO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                @if(isset($recid)) 
                <form name="frmAto" id="frmAto" method="POST" action="{{URL('economic-licence/ato',$recid->id)}}">
                    {{ method_field('PATCH') }}
                @else
                <form name="frmAto" id="frmAto" method="POST" action="{{URL('/economic-licence/ato')}}">
                @endif
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="labelholder">Training Organizations *</label>
                        <select class="form-control operator" id="trainingOrganizationId" name="training_organization_id">
                            <option value="0">Choose a Training Organization</option>
                            @foreach($trainingOrganizationListings as $training)
                            @if(isset($recid) && $recid->training_organization_id == $training->id)
                            <option value="{{$training->id}}" selected>{{$training->training_organization}}</option>
                            @else
                            <option value="{{$training->id}}">{{$training->training_organization}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Approval Number *</label>
                        <input type="text" name="approval_no" id="approval_no" value="@if(isset($recid)){{$recid->approval_no}}@else{{old('approval_no')}}@endif" class="form-control" >
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Upload Certificate *</label>
                        
                        <input type="file" name="ato_certificate" id="file" style="font-size:12px; display:inline-block"  @if(isset($recid))disabled  @endif > @if(isset($recid)) 
                        <span class="labelholder" id="changePhoto" style="cursor:pointer">Change</span> @endif
                        <input type="hidden" name="photoChecker" id="photoChecker" value="@if(isset($recid)){{$recid->ato_certificate}}@else{{0}}@endif">
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Date of Initial Issue *</label>
                        <input type="date" name="date_of_first_issue" id="dateOfFirstIssue" class="form-control" value="@if(isset($recid)){{$recid->date_of_first_issue}}@else{{old('date_of_first_issue')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Date of Last Renewal *</label>
                        <input type="date" name="date_of_renewal" id="dateOfLastRenewal" class="form-control" value="@if(isset($recid)){{$recid->date_of_renewal}}@else{{old('date_of_renewal')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Date of Expiry *</label>
                        <input type="date" name="date_of_expiry" id="dateOfExpiry" class="form-control" value="@if(isset($recid)){{$recid->date_of_expiry}}@else{{old('date_of_expiry')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Comments *</label>
                        <textarea name="comments" id="comments" class="form-control">@if(isset($recid)){{$recid->comments}}@else{{old('comments')}}@endif</textarea>
                    </div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                    <input type="hidden" name="ftype" id="ftype" value="pdf" />

                    <div id="loader"></div>
                    
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    @if(isset($recid))        
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateAto">UPDATE</button>
                    @else
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addAto">SAVE</button>
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
                                    <th colspan="8" style="font-size:11px; font-weight:bold">ATO Lists</th>
                                    {!! $atoListings->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Approval NO.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Initial Issue Date</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Renewal</th>
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
                                @if(count($atoListings))
                                <?php $count = 0; ?>
                                    @foreach($atoListings as $ato)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';

                                        $convertdate_of_first_issue = strtotime($ato->date_of_first_issue);
                                        $convertdaterenewal = strtotime($ato->date_of_renewal);

                                        $date_of_first_issue = date('d/m/Y', $convertdate_of_first_issue);
                                        $renewal = date('d/m/Y', $convertdaterenewal);
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{strtoupper($ato->approval_no)}}</td>
                                            <td>{{$date_of_first_issue}}</td>
                                            <td>{{$renewal}}</td>

                                            @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                                            <td style="color:blue" class="center">
                                                <a href="{{URL('economic-licence/ato/'.$ato->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                            <td style="color:red" class="center">
                                                <form method="POST" name="deleteato" id="deleteato">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteato" style="font-size:25px; cursor:pointer" value="{{$ato->id}}" title="Delete {{$ato->approval_no}}"></i>
                                                </form>
                                            </td>
                                            @endif
                                            @if(Auth::user()->role == 1)
                                            <td>{{$ato->created_by}}</td>
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/ato.js')}}"></script>
@stop