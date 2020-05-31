@extends('v1.ncaa.design-layout')

@section('title')Air Travel Organization Licence @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Air Travel Organization Licence 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('economic-licence/atol/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add ATOL</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
            @if(isset($recid)) 
                <form name="frmAtol" id="frmAtol" method="POST" enctype="multipart/form-data" action="{{URL('economic-licence/atol',$recid->id)}}">
                {{ method_field('PATCH') }}
                @else
                <form name="frmAtol" id="frmAtol" method="POST" enctype="multipart/form-data" action="{{URL('/economic-licence/atol')}}">
                @endif
                {!! csrf_field() !!}
                <div class="form-group">
                    <input type="hidden" name="operator_type_checker" id="operatorTypeChecker" value="@if(isset($recid)){{$recid->operator_type_checker}}@else{{''}}@endif">

                    <span class="labelholder" style="display:block; margin-right:10px;" >Operator Type *</span>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="1" @if(isset($recid) && $recid->operator_type_checker == 1) checked @endif>
                                Existing AOC &nbsp; &nbsp; 
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="2" @if(isset($recid) && $recid->operator_type_checker == 2) checked @endif>
                                General Aviation
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="3" @if(isset($recid) && $recid->operator_type_checker == 3) checked @endif>
                                Travel Agency
                            </label>
                        </div>
                    </div>
                    <div class="form-group" style="@if(isset($recid) && $recid->operator_type_checker == 1) display:block @else display:none @endif" id="existingAocHolder">
                        <label class="labelholder">Existing AOC Operator *</label>
                        <select class="form-control" id="aocHolderInUse">
                            <option value="0">Choose an Operator</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="@if(isset($recid) && $recid->operator_type_checker == 2) display:block @else display:none @endif" id="generalAviationHolder">
                        <label class="labelholder">General Aviation Operator *</label>
                        
                        <select class="form-control" id="aocHolderGa">
                            <option value="0">Choose an Operator</option>
                            @foreach($generalaviations as $generalAviation)
                                @if(isset($recid) && $recid->operator_type == $generalAviation->general_aviation_name)
                                <option value="{{$generalAviation->general_aviation_name}}" selected>{{$generalAviation->general_aviation_name}}</option>
                                @else
                                <option value="{{$generalAviation->general_aviation_name}}" >{{$generalAviation->general_aviation_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="@if(isset($recid) && $recid->operator_type_checker == 3) display:block @else display:none @endif" id="travelAgencyHolder">
                        <label class="labelholder">Travel Agency *</label>
                        <select class="form-control" id="aocHolderTa">
                            <option value="0">Choose Travel Agency</option>
                            @foreach($travelAgencies as $travelAgency)
                                @if(isset($recid) && $recid->operator_type == $travelAgency->travel_agency_name)
                                <option value="{{$travelAgency->travel_agency_name}}" selected>{{$travelAgency->travel_agency_name}}</option>
                                @else
                                <option value="{{$travelAgency->travel_agency_name}}">{{$travelAgency->travel_agency_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="labelholder">Licence Number *</label>
                        <input type="text" name="licence_no" id="licence_no" class="form-control"  value="@if(isset($recid)){{$recid->licence_no}}@else{{old('licence_no')}}@endif">
                        
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Upload Certificate *</label>
                        <input type="file" name="file" id="file" style="font-size:12px; display:block" >
                        <input type="hidden" value="0" id="photoChecker">
                    </div>
                   
                    
                    <div class="form-group">
                        <label class="labelholder">Date of Initial Issue *</label>
                        <input type="date" name="date_of_first_issue" id="dateOfFirstIssue" class="form-control" value="@if(isset($recid)){{$recid->date_of_first_issue}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Date of Last Renewal *</label>
                        <input type="date" name="renewal" id="dateOfLastRenewal" class="form-control" value="@if(isset($recid)){{$recid->renewal}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Date of Expiry *</label>
                        <input type="date" name="date_of_expiry" id="dateOfExpiry" class="form-control" value="@if(isset($recid)){{$recid->date_of_expiry}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Comments </label>
                        <textarea name="comments" id="comments" class="form-control">@if(isset($recid)){{$recid->comments}}@else{{old('comments')}}@endif</textarea>
                    </div>

                    @if(!isset($recid))
                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    @endif

                    <div id="loader"></div>
                    
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    @if(!isset($recid)) 
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addAtol">SAVE</button>
                    @endif
                    @if(isset($recid)) 
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateAtol">UPDATE</button>
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
                                    <th colspan="8" style="font-size:11px; font-weight:bold">ATOL Lists</th>
                                    {!! $atolListings->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Operator Type</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Initial Issue Date</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Date of Expiry</th>

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
                                @if(count($atolListings))
                                <?php $count = 0; ?>
                                    @foreach($atolListings as $atol)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';

                                        $convertdate_of_first_issue = strtotime($atol->date_of_first_issue);
                                        $convertdateofepiry = strtotime($atol->date_of_expiry);

                                        $date_of_first_issue = date('d/m/Y', $convertdate_of_first_issue);
                                        $expiry = date('d/m/Y', $convertdateofepiry);
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{strtoupper($atol->operator_type)}}</td>
                                            <td>{{$date_of_first_issue}}</td>
                                            <td>{{$expiry}}</td>
                                            @if(Auth::user()->role == 1 || Auth::user()->role == 2)

                                            <td style="color:blue" class="center">
                                                <a href="{{URL('economic-licence/atol/'.$atol->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                        
                                            <td style="color:red" class="center">
                                                <form method="POST" name="deleteatol" id="deleteatol">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteatol" style="font-size:25px; cursor:pointer" value="{{$atol->id}}" title="Delete {{$atol->approval_no}}"></i>
                                                </form>
                                            </td>
                                            @endif

                                            @if(Auth::user()->role == 1)
                                            <td>{{$atol->created_by}}</td>
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/atol.js')}}"></script>

@stop