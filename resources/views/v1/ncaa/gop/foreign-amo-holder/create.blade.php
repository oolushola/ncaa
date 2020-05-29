@extends('v1.ncaa.design-layout')

@section('title')Foreign Amo Holder @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Foreign Amo  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="{{URL('aircraft-type')}}">General Aviation</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add Foreign Amo Holder</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmAddForeignAmoHolder" id="frmAddForeignAmoHolder">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        
                        <div class="form-group">
                            <label class="labelholder">Foreign Amo *</label>
                            <input type="text" name="foreign_amo_holder" id="foreign_amo_holder" class="form-control" value="<?php if(isset($recid)){echo $recid->foreign_amo_holder;}else{echo ""; } ?>">
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateForeignAmoHolder">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addForeignAmoHolder">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.foreign-amo-holder._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/foreignamoholder.js')}}"></Script>
@stop