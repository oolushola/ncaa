@extends('v1.ncaa.design-layout')

@section('title') Assign Ratings & Capabilities to Amo Holder @stop

@section('main')

    <div class="page-header">
        <h3 class="page-title">
            Foreign Amo Holder Ratings & Capabilities
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-foreign')}}">Foreign Amo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ratings & Capabilities</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-7 grid-margin" >
            <form method="POST" name="frmAssignRatingstoHolder" id="frmAssignRatingstoHolder">
            {{csrf_field()}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">  
                            {{$amoholderName->foreign_amo_holder}}
                        </h4>
                        <p class="card-description" style="font-size:11px; font-weight:bold; color:green">Aircraft Maker * </p>
                        <div class="form-group">
                            <select class="form-control" id="aircraft_maker_id" name="aircraft_maker_id">
                            <option value="">Select Aircraft Maker</option>
                            @foreach($aircraftMakerlists as $aircraftMaker)
                                    <option value="{{$aircraftMaker->id}}">{{$aircraftMaker->aircraft_maker}}</option>
                            @endforeach
                            </select>
                            <input type="hidden" id="checkboxValidator" name="checkboxValidator" value="0">
                        </div>
                        <div class="form-group">
                            <div class="row" id="contentDropper"></div>
                        </div>

                        <div id="loader"></div>

                        <input type="hidden" name="id" id='foreign_amo_id' value="{{$recid->id}}">
                        <button type="button" class="btn btn-gradient-primary mr-2" id="addForeignAmoRatings">Add</button>
                        <button class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- make it a partial and include the form here -->
        

        @include('v1.ncaa.amo.foreign.partials._ratingslistings')
    </div>
        
    
    


@stop


@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/amo/foreign-assign.js')}}"></script>
@stop