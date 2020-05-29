@extends('v1.ncaa.design-layout')

@section('title') Update {{ $recid->aoc_holder }} record @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            {{$recid -> aoc_holder}}  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('/new-aoc',)}}">AOC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method='POST' name="frmUpdateAoc" id="frmUpdateAoc" enctype="multipart/form-data" action="{{URL('new-aoc', base64_encode($recid->id))}}">
                        {!! csrf_field() !!} {!! method_field('PATCH') !!}
                        <div class="form-group">
                            <label class="labelholder">AOC Holder *</label>
                            <input type="text" class="form-control" id="aoc_holder" name="aoc_holder" value="{{ $recid->aoc_holder }}">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">AOC Certificate No *</label>
                            <input type="text" class="form-control" id="aoc_certificate_no" name="aoc_certificate_no" value="{{ $recid->aoc_certificate_no }}">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">AOC Certificate *</label>
                            <i class="mdi mdi-lock-open-outline" id="unlockAocCertificateNo" title="click to make upload active" style=" position:relative; top:-3px;"></i>

                            <input type="file" value="{{$recid->aoc_certificate}}"  name="aoc_certificate" id="aoc_certificate"  style="font-size:12px; display:block" disabled>

                            <input type="hidden" id="aocCertificateNoCheck" value=0>
                        </div>
                        <div class="form-group">
                        <?php 
                            $converdatetotime = strtotime($recid->issued_date); 
                            $choosendate = date('Y-m-d', $converdatetotime);
                        ?>
                            <label style="font-size:11px; color: green; font-weight: bold">Issued Date *</label>
                            <input type="date" name="issued_date" class="form-control" id="issued_date" placeholder="Issued Date" value="{{ $choosendate}}">
                        </div>
                        <div class="form-group">
                        <?php 
                            $converdatetotime = strtotime($recid->validity); 
                            $validitychoosendate = date('Y-m-d', $converdatetotime);
                        ?>
                            <label style="font-size:11px; color: green; font-weight: bold">Validity *</label>
                            <input type="date" value="{{ $validitychoosendate }}" name="validity" class="form-control" id="validity" placeholder="Validity">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">OPS Specs *</label>
                            <i class="mdi mdi-lock-open-outline" id="unlockOpsSpecs" title="click to make upload active" style=" position:relative; top:-3px;"></i>

                            <input type="file" name="ops_specs" value="{{$recid->ops_specs}}" id="ops_specs"  style="font-size:12px; display:block" disabled>

                            <input type="hidden" id="opsSpecsCheck" value="0">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">Part G *</label>
                            <i class="mdi mdi-lock-open-outline" id="unlockPartG" title="click to make upload active" style=" position:relative; top:-3px;"></i>

                            <input type="file" value="{{$recid->part_g}}" name="part_g" id="part_g"  style="font-size:12px; display:block" disabled>

                            <input type="hidden" id="partGCheck" value="0">
                        </div>
                        <div class="form-group">
                        <input type="hidden" name="remarks" id="remarks", value="{{$recid->remarks}}">
                            @if($recid->remarks == 1)
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" checked class="form-check-input remarks" name="remarks" value="1">
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
                                        <input type="radio" class="form-check-input remarks" name="remarks" value=4>
                                        Revoked &nbsp; &nbsp; 
                                    </label>
                                </div>
                            @elseif($recid->remarks == 2)
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value="1">
                                        Active &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label"  style="font-size:11px; font-weight:bold;"> 
                                        <input type="radio" checked class="form-check-input remarks" name="remarks" value="2" >
                                        Suspended &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio"  class="form-check-input remarks" name="remarks" value=3>
                                        Expired &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value=4>
                                        Revoked &nbsp; &nbsp; 
                                    </label>
                                </div>
                            @elseif($recid->remarks == 3)
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
                                        <input type="radio" checked class="form-check-input remarks" name="remarks" value=3>
                                        Expired &nbsp; &nbsp; 
                                    </label>
                                </div>
                                <div class="form-check" style="display:inline-block;">
                                    <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                        <input type="radio" class="form-check-input remarks" name="remarks" value=4>
                                        Revoked &nbsp; &nbsp; 
                                    </label>
                                </div>
                            @elseif($recid->remarks == 4)
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
                                        <input type="radio" checked class="form-check-input remarks" name="remarks" value="4">
                                        Revoked
                                    </label>
                                </div>
                            @endif
                        </div>

                        <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                        <input type="hidden" name="ftype" id="ftype" value="pdf" />
                        <input type="hidden" name="name" value="{{Auth::user()->name}}">
                        <input type="hidden" name="module" value="aoc">
                        <input type="hidden" name="actual"value="{{$recid->aoc_holder}}">
                        <input type="hidden" name="record_id" value="{{$recid->id}}">

                        <button type="submit" id="updateAocRecord" class="btn btn-gradient-primary mr-2">UPDATE</button>
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