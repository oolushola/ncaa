@extends('v1.ncaa.design-layout')

@section('title')User role @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Assign User Role  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Global Operation</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Role</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmUserRole" id="frmUserRole">
                    {!! csrf_field() !!} {!! method_field('PATCH') !!}
                        <div class="form-group">
                           <select name="user_id" id="userid" class="form-control">
                                <option value="0">Choose User</option>
                                @foreach($userslist as $user)
                                    @if(isset($recid))
                                        @if($recid->id == $user->id)
                                            <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                        @else
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endif
                                    @else
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endif
                                @endforeach
                           </select>
                        </div> 

                        <div class="form-group">
                            @if(isset($recid))
                                <input type="text" name="role" id="role", value="{{$recid->role}}">
                            @else
                                <input type="hidden" name="role" id="role", value="">
                            @endif
                            <label style="font-size:12px; font-weight:bold; color:green; display:block">User Role</label>
                                @if(isset($recid))
                                    @if($recid->role==1)
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="1" name="role" checked>
                                                Super &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="2" name="role">
                                                Top-tier &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="3" name="role">
                                                View & Read
                                            </label>
                                        </div>
                                    @elseif($recid->role==2)
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="1" name="role">
                                                Super &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" checked value="2" name="role">
                                                Top-tier &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="3" name="role">
                                                View & Read
                                            </label>
                                        </div>
                                    @elseif($recid->role==3)
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="1" name="role">
                                                Super &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="2" name="role">
                                                Top-tier &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="3" name="role" checked>
                                                View & Read
                                            </label>
                                        </div>
                                    @else
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="1" name="role">
                                                Super &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="2" name="role">
                                                Top-tier &nbsp; &nbsp; 
                                            </label>
                                        </div>
                                        <div class="form-check" style="display:inline-block;">
                                            <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                                <input type="radio" class="form-check-input userRole" value="3" name="role">
                                                View & Read
                                            </label>
                                        </div>
                                    @endif
                                @else
                                    <div class="form-check" style="display:inline-block;">
                                        <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                            <input type="radio" class="form-check-input userRole" value="1" name="role">
                                            Super &nbsp; &nbsp; 
                                        </label>
                                    </div>
                                    <div class="form-check" style="display:inline-block;">
                                        <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                            <input type="radio" class="form-check-input userRole" value="2" name="role">
                                            Top-tier &nbsp; &nbsp; 
                                        </label>
                                    </div>
                                    <div class="form-check" style="display:inline-block;">
                                        <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                            <input type="radio" class="form-check-input userRole" value="3" name="role">
                                            View & Read
                                        </label>
                                    </div>
                                @endif

                                <div id="loader"></div>
                                
                        </div>
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addRole">UPDATE ROLE</button>
                        @else
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addRole">ADD ROLE</button>
                        @endif
                        <button class="btn btn-light">Cancel</button>

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        @include('v1.ncaa.gop.user.partials.role')
        
    </div>
@stop

@section('scripts')
<script src="{{URL::asset('js/gop/user-role.js')}}"></script>
@stop