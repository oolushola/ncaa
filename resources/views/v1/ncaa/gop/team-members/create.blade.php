@extends('v1.ncaa.design-layout')

@section('title') Team Members @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Team Members  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Add Team Members</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="frmTeamMembers">
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

                        <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateTeamMembers">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addTeamMembers">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        @include('v1.ncaa.gop.team-members._listings')
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/team-members.js?v=').time()}}"></script>
@stop