@extends('v1.ncaa.design-layout')

@section('title')User role @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            New user  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('users')}}">View All Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">New User </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    
                    <form method="POST" name="frmUser" id="frmUser">
                    {!! csrf_field() !!} 
                    @if(isset($recid))
                    {!! METHOD_FIELD('PATCH') !!}
                    @endif

                        <div class="form-group">
                           <label style="font-size:12px; font-weight:bold; color:green; display:block">Full Name</label>
                           <input type="text" name="name" id="fullName" class="form-control" value="@if(isset($recid)){{$recid->name }}@endif">
                        </div>
                        <div class="form-group">
                           <label style="font-size:12px; font-weight:bold; color:green; display:block">Email</label>
                           <input type="email" name="email" id="email" class="form-control"  value="@if(isset($recid)){{$recid->email }}@endif">
                        </div>
                        <div class="form-group">
                           <label style="font-size:12px; font-weight:bold; color:green; display:block">Phone No</label>
                           <input type="text" name="phone" id="phoneNo" class="form-control"  value="@if(isset($recid)){{$recid->phone }}@endif">
                        </div>
                        @if(!isset($recid))
                        <div class="form-group">
                           <label style="font-size:12px; font-weight:bold; color:green; display:block">Password</label>
                           <input type="password" name="password" id="password" class="form-control">
                        </div>
                        @endif

                        <div class="form-group">
                            @if(isset($recid))
                                <input type="hidden" name="role" id="role", value="{{$recid->role}}">
                                <input type="hidden" id="user_id", value="{{$recid->id}}">
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
                                <?php
                                    $departments = array('daws', 'dolt', 'datr');
                                ?>
                                <div class="form-group">
                                    <label style="font-size:12px; font-weight:bold; color:green; display:block">Department</label>
                                    <select name="department" id="department" class="form-control">
                                        <option>Choose a department</option>
                                        @foreach($departments as $department)
                                            @if(isset($recid) && $recid->department == $department)
                                            <option value="{{ $department }}" selected>{{ strtoupper($department) }}</option>
                                            @else
                                            <option value="{{ $department }}">{{ strtoupper($department) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div id="loader"></div>
                                
                        </div>
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateRole">UPDATE ROLE</button>
                        @else
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addRole">ADD ROLE</button>
                        @endif
                        <button type="reset" class="btn btn-light">Cancel</button>

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