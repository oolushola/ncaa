@extends('v1.ncaa.design-layout')

@section('title') State of Registry @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            State of Registry  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="{{URL('aircraft-type')}}">State of Registry</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add State of Registry</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmStateOfRegistry" id="frmStateOfRegistry">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        
                        <div class="form-group">
                            <label class="labelholder">State of Registry *</label>
                            <input type="text" name="state_of_registry" id="state_of_registry" class="form-control" value="<?php if(isset($recid)){echo $recid->state_of_registry;}else{echo ""; } ?>">
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateStateOfRegistry">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addStateOfRegistry">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.state-of-registry._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/state-of-registry.js')}}"></Script>
@stop