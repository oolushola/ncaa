@extends('v1.ncaa.design-layout')

@section('title') Assign Ratings & Capabilities to Local Amo Holder @stop

@section('main')

    <div class="page-header">
        <h3 class="page-title">
            Local Amo Holder Ratings & Capabilities
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-local')}}">Local Amo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ratings & Capabilities</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-7 grid-margin" >
            <form method="POST" name="frmLocalRatingstoHolder" id="frmLocalRatingstoHolder">
            {{csrf_field()}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">  
                            {{$recid->aoc_holder_id}}
                        </h4>
                        <p class="card-description" style="font-size:11px; font-weight:bold; color:green">Aircraft Maker * </p>
                        <div class="form-group">
                            <select class="form-control" id="aircraft_maker_id" name="aircraft_maker_id">
                            <option value="">Select Aircraft Maker</option>
                            @foreach($aircraftMakerList as $aircraftMaker)
                                    <option value="{{$aircraftMaker->id}}">{{$aircraftMaker->aircraft_maker}}</option>
                            @endforeach
                            </select>
                            <input type="hidden" id="checkboxValidator" name="checkboxValidator" value="0">
                        </div>
                        <div class="form-group">
                            <div class="row" id="contentDropper"></div>
                        </div>

                        <div id="loader"></div>

                        <input type="hidden" name="id" id='local_amo_id' value="{{$recid->id}}">
                        <button type="button" class="btn btn-gradient-primary mr-2" id="addLocalAmoRating">Add</button>
                        <button class="btn btn-light">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- make it a partial and include the form here -->
        
        @include('v1.ncaa.amo.local.partials._ratingslistings')

        
    </div>
        
    
    


@stop


@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/amo/local-assign.js')}}"></script>
@stop