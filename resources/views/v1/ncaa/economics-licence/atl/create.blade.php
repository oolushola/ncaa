@extends('v1.ncaa.design-layout')

@section('title')Air Transport Licence @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
             Air Transport License.
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('economice-licence/atl/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">@if(isset($recid))Update @else Add @endif ATL</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin ">
            <div class="card">
            <div class="card-body">
                @if(isset($recid)) 
                <form name="frmAtl" id="frmAtl" method="POST" action="{{URL('economic-licence/atl',$recid->id)}}">
                {{ method_field('PATCH') }}
                @else
                <form name="frmAtl" id="frmAtl" method="POST" action="{{URL('/economic-licence/atl')}}">
                @endif

                {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="hidden" name="operator_type" id="operatorTypeChecker" value="@if(isset($recid)){{$recid->operator_type}}@else{{''}}@endif">
                        <span class="labelholder" style="display:inline-block; margin-right:10px;" >Operator Type *</span>
                       
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="1" @if(isset($recid) && $recid->operator_type == 1) checked @endif>
                                Existing AOC &nbsp; &nbsp; 
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="2" @if(isset($recid) && $recid->operator_type == 2) checked @endif>
                                General Aviation
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="3" @if(isset($recid) && $recid->operator_type == 3) checked @endif>
                                Others
                            </label>
                        </div>
                    </div>

                    @if(isset($recid))
                        @if($recid->operator_type == 1)
                            <div class="form-group" id="existingAocHolder">
                                <label class="labelholder">Existing AOC Operator *</label>
                                <select class="form-control operator" id="aocHolderInUse" name="operator">
                                    <option value="0">Choose an Operator</option>
                                    @foreach($aoclists as $aoc)
                                        @if($aoc->aoc_holder == $recid->operator)
                                            <option selected value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                                        @else
                                            <option value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if($recid->operator_type == 2)
                            <div class="form-group" id="generalAviationHolder">
                                <label class="labelholder">General Aviation Operator *</label>
                                <select class="form-control operator" name="operator" id="aocHolderGa">
                                    <option value="0">Choose an Operator</option>
                                    @foreach($generalaviations as $generalAviation)
                                        @if($generalAviation->general_aviation_name == $recid->operator)
                                        <option value="{{$generalAviation->general_aviation_name}}" selected>
                                            {{$generalAviation->general_aviation_name}}
                                        </option>
                                        @else
                                        <option value="{{$generalAviation->general_aviation_name}}">
                                            {{$generalAviation->general_aviation_name}}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if($recid->operator_type == 3)
                            <div class="form-group operator" id="otherHolder">
                                <label class="labelholder">Others *</label>
                                <input type="text" class="form-control" id="aocHolderOther" value="{{ $recid->operator }}">
                            </div>
                        @endif
                    @else
                    <div class="form-group" style="display:none" id="existingAocHolder">
                        <label class="labelholder">Existing AOC Operator *</label>
                        <select class="form-control operator" id="aocHolderInUse">
                            <option value="0">Choose an Operator</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group operator" style="display:none" id="generalAviationHolder">
                        <label class="labelholder">General Aviation Operator *</label>
                        <select class="form-control" id="aocHolderGa">
                            <option value="0">Choose an Operator</option>
                            @foreach($generalaviations as $generalAviation)
                                <option value="{{$generalAviation->general_aviation_name}}">{{$generalAviation->general_aviation_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group operator" style="display:none" id="otherHolder">
                        <label class="labelholder">Others *</label>
                        <input type="text" class="form-control" id="aocHolderOther">
                    </div>
                    @endif



                    <div class="form-group">
                        <label class="labelholder">Licence Number *</label>
                        <input type="text" name="licence_no" id="licenceNumber" value="@if(isset($recid)){{$recid->licence_no}}@else{{old('licence_no')}}@endif" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Upload Certificate *</label>
                        
                        <input type="file" name="atl_certificate" id="file" style="font-size:12px; display:inline-block"  @if(isset($recid))disabled  @endif > @if(isset($recid)) 
                        <span class="labelholder" id="changePhoto" style="cursor:pointer">Change</span> @endif
                        <input type="hidden" name="photoChecker" id="photoChecker" value="@if(isset($recid)){{$recid->atl_certificate}}@else{{0}}@endif">
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

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                    <input type="hidden" name="ftype" id="ftype" value="pdf" />

                    <div class="form-group">
                        <label class="labelholder">Comments *</label>
                        <textarea name="comments" id="comments" class="form-control">@if(isset($recid)){{$recid->comments}}@else{{old('comments')}}@endif</textarea>
                    </div>

                    <div id="loader"></div>
                    
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    @if(isset($recid))        
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateAtl">UPDATE</button>
                    @else
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addAtl">SAVE</button>
                    @endif
                    <button class="btn btn-light">Cancel</button>
                    @endif
                </form>
            </div>
            </div>
        </div>
        
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body" style="padding:4px;">
                    <h4 class="card-title">Preview Pane <span id="deleteLoader"></span></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead class="table-info">
                                <tr class="table-warning">
                                    <th colspan="8" style="font-size:11px; font-weight:bold">Atl Lists</th>
                                    {!! $atllistings->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Licence NO.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">IssInitial Issue Date</th>
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
                                @if(count($atllistings))
                                <?php $count = 0; ?>
                                    @foreach($atllistings as $atl)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';

                                        $convertdate_of_first_issue = strtotime($atl->date_of_first_issue);
                                        $convertdaterenewal = strtotime($atl->date_of_renewal);

                                        $date_of_first_issue = date('d/m/Y', $convertdate_of_first_issue);
                                        $renewal = date('d/m/Y', $convertdaterenewal);
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{strtoupper($atl->licence_no)}}</td>
                                            <td>{{$date_of_first_issue}}</td>
                                            <td>{{$renewal}}</td>

                                            <td style="color:blue" class="center">
                                                <a href="{{URL('economic-licence/atl/'.$atl->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                            
                                            <td>{{$atl->created_by}}</td>
                                            <td style="color:red" class="center">
                                                <form method="POST" name="frmDeleteAtl" id="frmDeleteAtl">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteAtl" style="font-size:25px; cursor:pointer" value="{{$atl->id}}" title="Delete {{$atl->licence_no}}"></i>
                                                </form>
                                            </td>
                                            
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
<script type="text/javascript" src="{{URL::asset('js/economic-licence/atl.js')}}"></script>
@stop