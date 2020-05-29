@extends('v1.ncaa.design-layout')

@section('title')Type Acceptance Certificate @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Type Acceptance Certificate  
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">A/C Status</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add TAC</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 ">
            <div class="card">
                <div class="card-body">
                    @if(isset($recid))
                    <form name="frmTac" id="frmTac" method="POST" enctype="multipart/form-data" action="{{URL('/type-acceptance-certificate', $recid->id)}}">
                    {!! method_field('PATCH') !!}
                    @else
                    <form name="frmTac" id="frmTac" method="POST" enctype="multipart/form-data" action="{{URL('/type-acceptance-certificate')}}">
                    @endif
                    {!! csrf_field() !!}
                    
                        
                        <div class="form-group">
                            <label class="labelholder">Aircraft Maker *</label>
                            <select class="form-control" name="aircraft_maker_id" id="aircraftMaker">
                                <option value="0">Choose an Aircraft Maker</option>
                                @foreach($aircraftMaker as $maker)
                                    @if(isset($recid) && $maker->id == $recid->aircraft_maker_id)
                                    <option value="{{$maker->id}}" selected>{{$maker->aircraft_maker}}</option>
                                    @else
                                    <option value="{{$maker->id}}">{{$maker->aircraft_maker}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="aircraftModel">
                            @if(isset($recid))
                            <label class="labelholder">Choose Aircraft Model</label>
                            <div class="row" style="max-height:200px; overflow:auto; font-size:11px;">
                                @foreach($aircraftTypeLists as $aircraftTypes) 
                                <div class="col-md-6" style="margin-bottom:10px;">
                                    <input type="checkbox" value="{{$aircraftTypes->id}}" name="aircraftTypes[]">
                                    {{$aircraftTypes->aircraft_type}}
                                </div>
                                @endforeach
                                
                            
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label class="labelholder">TC Acceptance Approval Certificate No *</label>
                            <input type="text" class="form-control" name="tc_acceptance_approval" id="tc_acceptance_approval" value="@if(isset($recid->tc_acceptance_approval)){{$recid->tc_acceptance_approval}}@endif">
                            <input type="file" name="file" id="file">
                        </div>

                        <div class="form-group">
                            <label class="labelholder">Date Issued *</label>
                            <input type="date" name="date_issued" id="date_issued" class="form-control" value="@if(isset($recid->date_issued)){{$recid->date_issued}}@endif">
                        </div>

                        <div class="form-group">
                            <label class="labelholder">TC Holder *</label>
                            <select class="form-control" name="tc_holder" id="tc_holder">
                                <option value="0">Choose Aircraft Maker</option>
                                @foreach($aircraftMaker as $maker)
                                    @if(isset($recid) && $maker->id == $recid->aircraft_maker_id)
                                    <option value="{{$maker->id}}" selected>{{$maker->aircraft_maker}}</option>
                                    @else
                                    <option value="{{$maker->id}}">{{$maker->aircraft_maker}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="labelholder">Original TC Issued By *</label>
                            <input type="text" name="original_tc_issued_by" id="original_tc_issued_by" class="form-control" value="@if(isset($recid->original_tc_issued_by)){{$recid->original_tc_issued_by}}@endif">
                        </div>

                        <div class="form-group">
                            <label class="labelholder">TC No *</label>
                            <input type="text" name="tc_no" id="tc_no" class="form-control" value="@if(isset($recid->tc_no)){{$recid->tc_no}}@endif">
                        </div>

                        <div class="form-group">
                            <label class="labelholder">Remark</label>
                            <input type="text" name="remarks" id="remarks" class="form-control" value="@if(isset($recid->remarks)){{$recid->remarks}}@endif">
                        </div>
                        

                        <input type="hidden" name="created_by" value="{{Auth::user()->name}}">

                        <div id="loader"></div>
                        
                                
                        <button type="submit" class="btn btn-gradient-primary mr-2" id="addTac">SAVE</button>
                        <button class="btn btn-light">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Include your partials on this line... -->
       
        @include('v1.ncaa.tac.partials.preview')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/tac/tac.js')}}"></script>
@stop