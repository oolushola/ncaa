@extends('v1.ncaa.design-layout')

@section('title')Update FOCC & MCC @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Flight Operations Clearance Certificate 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">A/C Status</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add FOCC</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmfocc" id="frmfocc" method="POST">
                {!! method_field('PATCH') !!}
                {!! csrf_field() !!}


                <input type="hidden" id="id" value="{{$recid->id}}" >

                <div class="form-group">
                    <input type="hidden" name="operator_type" id="operatorTypeChecker" value="{{$recid->operator_type}}">
                    <span class="labelholder" style="display:inline-block; margin-right:10px;" >Operator Type *</span>
                        @if($recid->operator_type == 1)
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="1" checked>
                                Existing AOC &nbsp; &nbsp; 
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="2">
                                General Aviation
                            </label>
                        </div>
                        @else
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="1">
                                    Existing AOC &nbsp; &nbsp; 
                                </label>
                            </div>
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="2" checked>
                                    General Aviation
                                </label>
                            </div>
                        @endif
                    </div>
                    
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
                    <div class="form-group">
                        <label class="labelholder">FOCC NO *</label>
                        <input type="text" class="form-control" name="focc_no" id="focc_no" value="{{$recid->focc_no}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">MCC NO *</label>
                        <input type="text" class="form-control" name="mcc_no" id="mcc_no" value="{{$recid->mcc_no}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">State of Registry *</label>
                        <div>
                            <select class="form-control" name="state_of_registry_id" id="state_of_registry_id">
                                <option value="0">Choose State of Registry</option>
                                @foreach($stateofregistries as $stateofregistry)
                                    @if($stateofregistry->id === $recid->state_of_registry_id)
                                    <option value="{{$stateofregistry->id}}" selected>
                                        {{$stateofregistry->state_of_registry}}
                                    </option>
                                    @else
                                    <option value="{{$stateofregistry->id}}">
                                        {{$stateofregistry->state_of_registry}}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registered Owner *</label>
                        <input type="text" class="form-control" name="registered_owner" id="registered_owner" value="{{$recid->registered_owner}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Maker *</label>
                            <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                                <option value="0">Choose Aircraft Maker</option>
                                @foreach($aircraftmakers as $make)
                                    @if($make->id === $recid->aircraft_maker_id)
                                    <option value="{{$make->id}}" selected>{{$make->aircraft_maker}}</option>
                                    @else
                                    <option value="{{$make->id}}">{{$make->aircraft_maker}}</option>
                                    @endif
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Type *</label>
                        <div id="aircraftTypeDropper">
                            <select class="form-control" name="aircraft_type_id">
                                <option value="0">Choose Aircraft Type</option>
                                @foreach($aircraft_types as $aircrafttype)
                                    @if($aircrafttype->aircraft_maker_id == $recid->aircraft_maker_id)
                                        @if($aircrafttype->id == $recid->aircraft_type_id)
                                        <option value="{{$aircrafttype->id}}" selected>
                                            {{$aircrafttype->aircraft_type}}
                                        </option>
                                        @else
                                        <option value="{{$aircrafttype->id}}">
                                            {{$aircrafttype->aircraft_type}}
                                        </option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Reg No. *</label>
                        <select class="form-control" name="aircraft_reg_no_id" id="aircraft_reg_no">
                            <option value="0">Choose Aircraft Reg No.</option>
                            @foreach($foreignregmarks as $regno)
                                @if($regno->id == $recid->aircraft_reg_no_id)
                                    <option value="{{$regno->id}}" selected>
                                        {{$regno->foreign_registration_marks}}
                                    </option>
                                @else
                                    <option value="{{$regno->id}}">
                                        {{$regno->foreign_registration_marks}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Date of First Issue *</label>
                        <input type="date" name="date_of_first_issue" id="dateOfFirstIssue" class="form-control"  value="{{$recid->date_of_first_issue}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Renewal</label>
                        <input type="date" name="renewal" id="renewal" class="form-control" value="{{$recid->renewal}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Valid Till</label>
                        <input type="date" name="valid_till" id="validTill" class="form-control" value="{{$recid->valid_till}}">
                    </div>

                    <div id="loader"></div>
                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="module" value="focc">
                    <input type="hidden" name="actual"value="{{$recid->focc_no}}">
                    <input type="hidden" name="record_id" value="{{$recid->id}}">

                    <input type="hidden" name="amo_holder_status" id="amo_holder_status" value="{{$recid->amo_holder_status}}">

                    <div class="form-group">
                    <span class="labelholder" style="display:inline-block; margin-right:10px;" >Revoke Authority to Manage? </span>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input amoStatusChecker" name="amoStatus" value="1">
                                No &nbsp; &nbsp;
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input amoStatusChecker" name="amoStatus" value="0">
                                Yes
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateFocc">UPDATE</button>
                    <button type="reset" onclick="window.location='/focc-and-mcc'" class="btn btn-light">Cancel</button>
                </form>
            </div>
            </div>
        </div>
        <!-- Include your partials on this line... -->
        @include('v1.ncaa.focc.partials._listings')
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/focc/focc.js')}}"></script>
@stop