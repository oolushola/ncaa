@extends('v1.ncaa.design-layout')

@section('title')FOCC @stop

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
                <form name="frmfocc" id="frmfocc" method="POST" action="{{URL('/focc')}}">
                {!! csrf_field() !!}
                    <div class="form-group">
                        <select class="form-control" name="aoc_holder_id" id="aocHolder">
                            <option value="0">Choose an Operator</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        
                        <input type="text" class="form-control" name="focc_no" id="focc_no" placeholder="FOCC NO.">
                    </div>
                    <div class="form-group" id="aircraftTypeDropper">
                        <select class="form-control">
                            <option value="0">Choose Aircraft Type</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="aircraft_reg_no" id="registrationMarks" placeholder="Aircraft Reg. No" disabled>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="type_of_operations" id="typeOfOperations", value="">
                        <label style="font-size:12px; font-weight:bold; color:green; display:block">Type of Operation</label>
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input typeofopereation" name="typeofopereation" value="private">
                                    Private &nbsp; &nbsp; 
                                </label>
                            </div>
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input typeofopereation" name="typeofopereation" value="public">
                                    Public
                                </label>
                            </div>
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Date of First Issue</label>
                        <input type="date" name="date_of_first_issue" id="dateOfFirstIssue" class="form-control">
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Renewal</label>
                        <input type="date" name="renewal" id="renewal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Valid Till</label>
                        <input type="date" name="valid_till" id="validTill" class="form-control">
                    </div>

                    <div class="form-group">
                        <textarea name="approval_import" id="approvalImport" class="form-control" placeholder="Name of Applicant for Approval Import"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Inspector" name="inspector" id="inspector">
                    </div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">

                    <div id="loader"></div>
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addFocc">ADD</button>
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