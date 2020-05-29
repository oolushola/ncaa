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
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form name="frmNewAoc" method="POST" id="frmNewAoc" enctype="multipart/form-data" action="{{URL('new-aoc')}}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                        <label class="labelholder">AOC Holder *</label>
                            <input type="text" class="form-control" id="aoc_holder" name="aoc_holder">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">AOC Certificate No *</label>
                            <input type="text" class="form-control" id="aoc_certificate_no" name="aoc_certificate_no">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">AOC Certificate *</label>
                            <input type="file" name="aoc_certificate" id="aoc_certificate"  style="font-size:12px; display:block">
                        </div>
                        <div class="form-group">
                            <label style="font-size:11px; color: green; font-weight: bold">Issued Date*</label>
                            <input type="date" name="issued_date" class="form-control" id="issued_date">
                        </div>
                        <div class="form-group">
                            <label style="font-size:11px; color: green; font-weight: bold">Validity *</label>
                            <input type="date" name="validity" class="form-control" id="validity">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">OPS Specs *</label>
                            <input type="file" name="ops_specs" id="ops_specs"  style="font-size:12px; display:block">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">PART G *</label>
                            <input type="file" name="part_g" id="part_g"  style="font-size:12px; display:block">
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
                            
                        <button type="submit" id="addNewAoc" class="btn btn-gradient-primary mr-2">SAVE</button>
                        <button type="reset" class="btn btn-light">Cancel</button>
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