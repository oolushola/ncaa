@extends('v1.ncaa.design-layout')

@section('title') Aircraft Type @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Add Aircraft Type  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('aircraft-type')}}">Aircraft Type</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Aircraft Type</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmAircraftType" id="frmAircraftType">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        <div class="form-group">
                            <label class="labelholder">Aircraft Maker *</label>
                            <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                                <option value="">Choose aircraft make</option>
                                @foreach($aircraftmakelists as $make)
                                    @if(isset($recid))
                                        @if($recid->aircraft_maker_id == $make->id)
                                            <option value="{{$make->id}}" selected>{{$make->aircraft_maker}}</option>
                                        @else
                                            <option value="{{$make->id}}">{{$make->aircraft_maker}}</option>
                                        @endif
                                    @else
                                        <option value="{{$make->id}}">{{$make->aircraft_maker}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="labelholder">Aircraft Type *</label>
                            <input type="text" name="aircraft_type" id="aircraft_type" class="form-control" value="<?php if(isset($recid)){echo $recid->aircraft_type;}else{echo ""; } ?>">
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateAircraftType">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addNewAircraftType">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.gop.aircraft-type._listings')
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/aircraft-type.js')}}"></Script>
@stop