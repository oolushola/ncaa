@extends('v1.ncaa.design-layout')

@section('title') Add New AOC @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            New AOC  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">AOC</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Register New AOC</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form name="frmNewAoc" method="POST" id="frmNewAoc" enctype="multipart/form-data" action="{{URL('new-aoc')}}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <input type="text" class="form-control" id="aoc_holder" name="aoc_holder" placeholder="AOC Holder">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="aoc_certificate_no" name="aoc_certificate_no" placeholder="AOC Certificate No">
                        </div>
                        <div class="form-group">
                            <input type="file" name="aoc_certificate" id="aoc_certificate" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" id="aoc_certificate_box" class="form-control file-upload-info" disabled placeholder="AOC Certificate">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-size:11px; color: green; font-weight: bold">Issued Date*</label>
                            <input type="date" name="issued_date" class="form-control" id="issued_date" placeholder="Issued Date">
                        </div>
                        <div class="form-group">
                            <label style="font-size:11px; color: green; font-weight: bold">Validity *</label>
                            <input type="date" name="validity" class="form-control" id="validity" placeholder="Validity">
                        </div>
                        <div class="form-group">
                            <input type="file" name="ops_specs" id="ops_specs" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="OPS Specs">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="file" name="part_g" id="part_g" class="file-upload-default">
                            <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="PART G">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="remarks" id="remarks", value="0">
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value="1">
                                        Active &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label"  style="font-size:11px; font-weight:bold;"> 
                                        <input type="radio" class="form-check-input remarks" name="remarks" value="2" >
                                        Suspended &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value=3>
                                        Expired &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value="4">
                                        Revoked
                                    </label>
                                </div>
                        </div>

                        <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                        <input type="hidden" name="ftype" id="ftype" value="pdf" />

                        <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                            
                        <button type="submit" id="addNewAoc" class="btn btn-gradient-primary mr-2">ADD</button>
                        <button class="btn btn-light">Cancel</button>
                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.aoc._partials.previewpane')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/aoc/aoc.js')}}"></script>
@stop