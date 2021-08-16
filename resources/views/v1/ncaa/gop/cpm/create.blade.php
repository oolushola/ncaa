@extends('v1.ncaa.design-layout')

@section('title') CPM @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            CPM  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Add CPM</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="frmCpm">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        <div class="form-group">
                          <label class="labelholder">Title *</label>
                          <input type="text" name="title" id="title" class="form-control" value="<?php if(isset($recid)){echo $recid->title;}else{echo ""; } ?>">
                        </div>
                        <div class="form-group">
                          <label class="labelholder">First Name *</label>
                          <input type="text" name="first_name" id="firstName" class="form-control" value="<?php if(isset($recid)){echo $recid->first_name;}else{echo ""; } ?>">
                        </div>
                        <div class="form-group">
                          <label class="labelholder">Last Name *</label>
                          <input type="text" name="last_name" id="lastName" class="form-control" value="<?php if(isset($recid)){echo $recid->last_name;}else{echo ""; } ?>">
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateCpm">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addCpm">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.cpm._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/cpm.js?v=').time()}}"></script>
@stop