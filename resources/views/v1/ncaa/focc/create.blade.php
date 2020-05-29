@extends('v1.ncaa.design-layout')

@section('title')FOCC & MCC @stop

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
                <form name="frmfoccandfocc" id="frmfoccandfocc" method="POST" action="{{URL('/focc-and-mcc')}}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <input type="hidden" name="operator_type" id="operatorTypeChecker" value="">
                    <span class="labelholder" style="display:inline-block; margin-right:10px;" >Operator Type *</span>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="1">
                                Existing AOC &nbsp; &nbsp; 
                            </label>
                        </div>
                        <div class="form-check" style="display:inline-block;">
                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                <input type="radio" class="form-check-input operatorChecker" name="typeofopereation" value="2">
                                General Aviation
                            </label>
                        </div>
                    </div>
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
                    <div class="form-group">
                        <label class="labelholder">FOCC NO *</label>
                        <input type="text" class="form-control" name="focc_no" id="focc_no">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">MCC NO *</label>
                        <input type="text" class="form-control" name="mcc_no" id="mcc_no">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">State of Registry *</label>
                        <div>
                            <select class="form-control" name="state_of_registry_id" id="state_of_registry_id">
                                <option value="0">Choose State of Registry</option>
                                @foreach($stateofregistries as $stateofregistry)
                                    <option value="{{$stateofregistry->id}}">{{$stateofregistry->state_of_registry}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registered Owner *</label>
                        <input type="text" class="form-control" name="registered_owner" id="registered_owner">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Maker *</label>
                            <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                                <option value="0">Choose Aircraft Maker</option>
                                @foreach($aircraftmakers as $make)
                                    <option value="{{$make->id}}">{{$make->aircraft_maker}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Type *</label>
                        <div id="aircraftTypeDropper">
                            <select class="form-control">
                                <option value="0">Choose Aircraft Type</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Reg No. *</label>
                        <select class="form-control" name="aircraft_reg_no_id" id="aircraft_reg_no">
                            <option value="0">Choose Aircraft Reg No.</option>
                            @foreach($foreignregmarks as $regno)
                                <option value="{{$regno->id}}">{{$regno->foreign_registration_marks}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Date of First Issue *</label>
                        <input type="date" name="date_of_first_issue" id="dateOfFirstIssue" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Renewal</label>
                        <input type="date" name="renewal" id="renewal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Valid Till</label>
                        <input type="date" name="valid_till" id="validTill" class="form-control">
                    </div>

                    <input type="hidden" name="amo_holder_status" value="1">

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">

                    <div id="loader"></div>
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addFocc">SAVE</button>
                    <button class="btn btn-light">Cancel</button>
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