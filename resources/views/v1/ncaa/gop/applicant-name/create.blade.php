@extends('v1.ncaa.design-layout')

@section('title') Applicant Name @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Applicant Name <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Add Applicant Name</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="frmApplicantName">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        <div class="form-group">
                          <label class="labelholder">Applicant Name *</label>
                          <input type="text" name="applicant_name" id="applicantName" class="form-control" value="<?php if(isset($recid)){echo $recid->applicant_name;}else{echo ""; } ?>">
                        </div>
                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateApplicantName">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addApplicantName">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.applicant-name._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/applicant-name.js?v=').time()}}"></script>
@stop