@extends('v1.ncaa.design-layout')

@section('title') Setup a new aircraft make @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Operation Type  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Global Operation</a></li>
            <li class="breadcrumb-item active" aria-current="page">AOC operation type</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmOperations" id="frmOperations">
                    {!! csrf_field() !!} {!! method_field('PATCH') !!}
                        <div class="form-group">
                            <input type="text" name="operation_type" id="operation_type" class="form-control" placeholder="Enter Operation Type" value="{{$recid->operation_type}}">
                        </div> 
                        <input type="hidden" name="id" value="{{$recid->id}}" id="id">     
                                
                        <button type="submit" class="btn btn-gradient-primary mr-2" id="updateOperations">Update</button>
                        <button class="btn btn-light">Cancel</button>

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.gop.operation-type.partials._viewlistings')
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/operations.js')}}"></Script>
@stop