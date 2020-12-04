@extends('v1.ncaa.design-layout')

@section('title'){{ucwords(Auth::user()->name)}} Update Profile  @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Update Profile  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">AOC</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Profile Summary</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form name="frmProfileUpdate" method="POST" id="frmProfileUpdate" enctype="multipart/form-data" action="{{URL('update-profile', Auth::user()->id)}}">
                    
                        {!! csrf_field() !!} {!! method_field('PATCH') !!}
                        <div class="form-group">
                            <label class="labelholder">Full Name</label>
                            <input type="text" class="form-control" id="username" name="name" placeholder="Full Name" value="{{ucwords(Auth::user()->name)}}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="labelholder">Photo</label>
                            <input type="file" name="file" id="file" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" id="fileBox" class="form-control file-upload-info" disabled value="">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="labelholder">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{Auth::user()->phone}}">
                        </div>

                        <div class="form-group">
                            <input type="text" disabled name="email" id="email" class="form-control" placeholder="Email" value="{{Auth::user()->email}}">
                        </div>

                        <input type="hidden"  id="filecheck" value="0" /> 
                        <input type="hidden"  id="ftype" value="jpg, jpeg, png, svg" />
                            
                        <button type="submit" id="updateUserProfile" class="btn btn-gradient-primary mr-2">Update</button>
                        <input type="button" class="btn btn-light" value="Cancel">
                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.gop.user.partials._profile_update')
    </div>

@stop

@section('scripts')
<script src="{{URL::asset('js/jquery.form.js')}}"></script>
<script src="{{URL::asset('js/mediavalidate.js')}}"></script>
<script src="{{URL::asset('js/gop/profile-update.js?v='.time())}}"></script>
@stop
