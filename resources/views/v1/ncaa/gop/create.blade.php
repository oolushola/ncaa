@extends('v1.ncaa.design-layout')

@section('title') Setup a new aircraft make @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Add New Aircraft Make  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Global Operation</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add aircraft Make</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmAircraftMake" id="frmAircraftMake">
                    {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="labelholder">Aircraft Maker *</label>
                            <input type="text" name="aircraft_maker" id="aircraft_maker" class="form-control">
                        </div>                
                                
                        <button type="submit" class="btn btn-gradient-primary mr-2" id="addNewAircraftMaker">ADD</button>
                        <button type="reset" class="btn btn-light">Cancel</button>

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.gop.partials._viewlistings')
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/aircraftmaker/aircraftmaker.js')}}"></Script>
@stop