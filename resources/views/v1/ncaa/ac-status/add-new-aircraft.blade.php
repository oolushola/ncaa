@extends('v1.ncaa.design-layout')

@section('title')Aircraft status @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            A/C Status 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">A/C Status</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add New Aircraft Status</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmAircraft" id="frmAircraft" method="POST" action="{{URL('/add-new-aircraft')}}">
                {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="labelholder">AOC Holder *</label>
                        <select class="form-control" name="aoc_holder_id" id="aoc_holder_id">
                            <option value="0">Choose a Registered Operator</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Make Operated by AOC Holder *</label>
                        <div id="aircraftconntent">
                            <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                                <option value="0">Aircraft Make</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registration Marks *</label>
                        <input type="text" class="form-control" placeholder="Registration Marks" name="registration_marks" id="registration_mark">
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Aircraft Type *</label>
                            <div id="aircraftTypeContent">
                                <select name="aircraft_type" class="form-control" id="aircraft_type">
                                    <option value="">Choose Aircraft Type</option>
                                </select>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Serial Number *</label>
                        <input type="text" class="form-control" name="aircraft_serial_number" id="aircraft_serial_number">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Year of Manufacture *</label>
                        <select name="year_of_manufacture" class="form-control" id="year_of_manufacture">
                            <option value=''>Choose year of manufacture</option>
                            <?php
                                $current_year = date('Y');
                                for($year = $current_year; $year >= 1960; $year--){
                                    echo '<option value='.$year.'>'.$year.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Current Registration date *</label>
                        <input type="date" class="form-control" id="registration_date" name="registration_date">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registered Owner *</label>
                        <input type="text" class="form-control" name="registered_owner" id="registered_owner">
                    </div>
                    <div class="form-group">
                        <label for="year of manufacture" style="font-size:12px; font-weight:bold; color:green">C of A Status</label>
                        <input type="date" class="form-control" id="c_of_a_status" name="c_of_a_status">
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Upload C of A</label>
                        <input type="file" name="file" id="file" style="font-size:12px; display:block">
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Weight(Kg) *</label>
                        <input type="number" class="form-control" name="weight" id="weight">
                    </div>

                    <div id="loader"></div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addAircraft">SAVE</button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                </form>
            </div>
            </div>
        </div>
        <!-- Include your partials on this line... -->
        @include('v1.ncaa.ac-status.partials._viewlistings')
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/aircraft-status/aircraft.js')}}"></script>

@stop