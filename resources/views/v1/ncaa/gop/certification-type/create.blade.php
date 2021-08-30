@extends('v1.ncaa.design-layout')

@section('title') Certification Type @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Certification Type <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Add Certification Type</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="frmCertificationType">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        <div class="form-group">
                          <label class="labelholder">Certification Type *</label>
                          <input type="text" name="certification_type" id="certificationType" class="form-control" value="<?php if(isset($recid)){echo $recid->certification_type;}else{echo ""; } ?>">
                        </div>
                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateCertificationType">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addCertificationType">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.certification-type._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/certification-type.js?v=').time()}}"></script>
@stop