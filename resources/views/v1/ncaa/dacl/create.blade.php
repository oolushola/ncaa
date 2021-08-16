@extends('v1.ncaa.design-layout')

@section('title')Foreign Airline DACL @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Foreign Airline DACL
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('foreign-airline-dacl/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Foreign Airline DACL</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                @if(isset($recid)) 
                <form name="foreignAirlineDacl" id="foreignAirlineDacl" method="POST" action="{{URL('foreign-airline-dacl',$recid->id)}}" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                @else
                <form name="foreignAirlineDacl" id="foreignAirlineDacl" method="POST" action="{{URL('foreign-airline-dacl')}}" enctype="multipart/form-data">
                @endif
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="labelholder">Foreign Airline Name *</label>
                        <select class="form-control" id="airlineName" name="airline_name">
                            <option value="0">Choose a foreign airline</option>
                            @foreach($foreignAirlineListings as $foreignAirline)
                              @if(isset($recid) && $recid->airline_name === $foreignAirline->foreign_airline)
                              <option value="{{$foreignAirline->foreign_airline}}" selected>
                                {{ $foreignAirline->foreign_airline}}
                              </option>
                              @else
                              <option value="{{$foreignAirline->foreign_airline}}">
                                {{ $foreignAirline->foreign_airline}}
                              </option>
                              @endif
                            @endforeach
                        </select>
                    </div>
                    
                    @if(!isset($recid))
                    <?php 
                        if($lastDaclNo) {
                            $lastValue = $lastDaclNo->dacl_no;
                        }
                        else{
                            $lastValue = 0;
                        }
                        $currentDaclNo = str_replace('FSG/NCAA/DACL/', '', $lastValue);
                        $counter = intval('0000') + intval($currentDaclNo) + 1;
                        $daclNo = 'FSG/NCAA/DACL/'.sprintf('%04d', $counter);
                    ?>
                    @endif
                    <div class="form-group">
                        <label class="labelholder">Dacl Number *</label>
                        <input type="text" value="@if(isset($recid)){{$recid->dacl_no}}@else{{$daclNo}}@endif" class="form-control" disabled>
                        <input type="hidden" name="dacl_no" value="@if(isset($recid)){{$recid->dacl_no}}@else{{$daclNo}}@endif" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Upload Dacl *</label>
                        <input type="file" name="dacl_certificate" id="file" style="font-size:12px; display:inline-block"  @if(isset($recid))disabled  @endif > @if(isset($recid)) 
                        <span class="labelholder" id="changeDacl" style="cursor:pointer">Change</span> @endif
                        <input type="hidden" name="daclChecker" id="daclChecker" value="@if(isset($recid)){{$recid->dacl_no}}@else{{0}}@endif">
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">DACL Issue Date *</label>
                        <input type="date" name="dacl_issue_date" id="daclIssueDate" class="form-control" value="@if(isset($recid)){{$recid->dacl_issue_date}}@else{{old('dacl_issue_date')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Upload Foreign AOC & Operation Specification *</label>
                        <input type="file" name="aoc_opspec" id="file2" style="font-size:12px; display:inline-block"  @if(isset($recid))disabled  @endif /> @if(isset($recid)) 
                        <span class="labelholder" id="changeAocOpspec" style="cursor:pointer">Change</span> @endif
                        <input type="hidden" name="opspecChecker" id="opspecChecker" value="@if(isset($recid)){{$recid->aoc_opspec}}@else{{0}}@endif">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aoc Expiry Date*</label>
                        <input type="date" name="aoc_expiry_date" id="aocExpiryDate" class="form-control" value="@if(isset($recid)){{$recid->aoc_expiry_date}}@else{{old('aoc_expiry_date')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Country *</label>
                        <select class="form-control" id="country" name="country">
                            <option value="0">Choose country</option>
                            @foreach($countries as $country)
                              @if(isset($recid) && $recid->country === $country->country)
                              <option value="{{$country->country}}" selected>
                                {{ $country->country}}
                              </option>
                              @else
                              <option value="{{$country->country}}">
                                {{ $country->country}}
                              </option>
                              @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Remarks *</label>
                        <textarea name="remarks" id="remarks" class="form-control">@if(isset($recid)){{$recid->remarks}}@else{{old('remarks')}}@endif</textarea>
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
                                    <th colspan="8" style="font-size:11px; font-weight:bold">DACL Lists</th>
                                    {!! $foreignAirlineDacllistings->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Airline Name.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">DACL NO.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">AOC Expiry Date</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Country</th>
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
                                @if(count($foreignAirlineDacllistings))
                                <?php $count = 0; ?>
                                    @foreach($foreignAirlineDacllistings as $foreignAirlineDacl)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                                        $convertAocExpiryDate = strtotime($foreignAirlineDacl->aoc_expiry_date);
                                        $aocExpiryDate = date('d/m/Y', $convertAocExpiryDate);
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{$foreignAirlineDacl->airline_name}}</td>
                                            <td>{{strtoupper($foreignAirlineDacl->dacl_no)}}</td>
                                            <td>{{$aocExpiryDate}}</td>
                                            <td>{{$foreignAirlineDacl->country}}</td>

                                            @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                                            <td style="color:blue" class="center">
                                                <a href="{{URL('foreign-airline-dacl/'.$foreignAirlineDacl->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                            <td style="color:red" class="center">
                                                <form method="POST" name="frmDeleteforeignAirlineDacl" id="frmDeleteforeignAirlineDacl">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteforeignAirlineDacl" style="font-size:25px; cursor:pointer" value="{{$foreignAirlineDacl->id}}" title="Delete {{$foreignAirlineDacl->dacl_no}}"></i>
                                                </form>
                                            </td>
                                            @endif
                                            @if(Auth::user()->role == 1)
                                            <td>{{$foreignAirlineDacl->created_by}}</td>
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
<script type="text/javascript" src="{{URL::asset('js/foreignAirlineDacl.js?v=').time()}}"></script>
@stop