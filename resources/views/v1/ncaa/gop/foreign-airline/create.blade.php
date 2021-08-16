@extends('v1.ncaa.design-layout')

@section('title') Foreign Airline @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Foreign Airline  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Add Foreign Airline</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="frmForeignAirline">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        
                        <div class="form-group">
                            <label class="labelholder">Foreign Airline Name *</label>
                            <input type="text" name="foreign_airline" id="foreign_airline" class="form-control" value="<?php if(isset($recid)){echo $recid->foreign_airline;}else{echo ""; } ?>">
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateForeignAirline">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addForeignAirline">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.foreign-airline._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/foreignAirline.js?v=').time()}}"></Script>
@stop