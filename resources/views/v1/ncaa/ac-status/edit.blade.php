@extends('v1.ncaa.design-layout')

@section('title')Update {{ucwords($recid->registration_marks)}} : {{ucwords($recid->aircraft_type)}} @stop

@section('main')
<div class="page-header">
        <h3 class="page-title">
            A/C Status Update
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('/add-new-aircraft')}}">AC Status</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Aircraft Status</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmAircraft" id="frmAircraft" method="POST" action="{{URL('/add-new-aircraft', $recid->id)}}">
                {!! csrf_field() !!} {!! method_field('PATCH') !!}
                    <div class="form-group">
                        <label class="labelholder">AOC Holder *</label>
                        <select class="form-control" name="aoc_holder_id" id="aoc_holder_id">
                            <option value="0">Choose a Registered Operator</option>
                            @foreach($aoclists as $aoc)
                                @if($aoc->id === $recid->aoc_holder_id)
                                    <option value="{{$aoc->id}}" selected>{{$aoc->aoc_holder}}</option>
                                @else
                                    <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Make Operated by AOC Holder *</label>
                        <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                            <option value="0">Aircraft Maker</option>
                            @foreach($aircraftMakerLists as $aircraftMaker)
                                @if($aircraftMaker->id === $recid->aircraft_maker_id)
                                    <option value="{{$aircraftMaker->id}}" selected>{{$aircraftMaker->aircraft_maker}}</option>
                                @else
                                <option value="{{$aircraftMaker->id}}">{{$aircraftMaker->aircraft_maker}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registration Marks *</label>
                        <input type="text" class="form-control" name="registration_marks" id="registration_mark" value="{{strtoupper($recid->registration_marks)}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Type *</label>
                            <div id="aircraftTypeContent">
                                <select name="aircraft_type" class="form-control" id="aircraft_type">
                                    <option value="">Choose Aircraft Type</option>
                                    @foreach($aircraftTypelists as $aircraft_type)
                                        @if($aircraft_type->aircraft_type == $recid->aircraft_type)
                                        <option value="{{$aircraft_type->aircraft_type}}" selected>{{$aircraft_type->aircraft_type}}</option>
                                        @else
                                        <option value="{{$aircraft_type->aircraft_type}}">{{$aircraft_type->aircraft_type}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Aircraft Serial Number *</label>
                        <input type="text" class="form-control" name="aircraft_serial_number" id="aircraft_serial_number" value="{{strtoupper($recid->aircraft_serial_number)}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Year of Manufacture *</label>
                        <select name="year_of_manufacture" class="form-control" id="year_of_manufacture">
                            <option value=''>Choose year of manufacture</option>
                            <?php
                                $current_year = date('Y');
                                for($year = $current_year; $year >= 1960; $year--){
                                    if($year == $recid->year_of_manufacture){
                                        echo '<option value='.$year.' selected>'.$year.'</option>';
                                    }
                                    else{
                                    echo '<option value='.$year.'>'.$year.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Current Registration Date *</label>
                        <input type="date" class="form-control" id="registration_date" name="registration_date" value="{{$recid->registration_date}}">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Registered Owner *</label>
                        <input type="text" class="form-control" name="registered_owner" id="registered_owner" value="{{$recid->registered_owner}}">
                    </div>
                    <div class="form-group">
                        <label for="year of manufacture" class="labelholder">C of A Status</label>
                        <input type="date" class="form-control" id="c_of_a_status" name="c_of_a_status" value="{{$recid->c_of_a_status}}">
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" class="labelholder">Upload C of A</label> <i class="mdi mdi-lock-open-outline" id="unlock" title="click to make upload active" style=" position:relative; top:-3px;"></i>

                        <input type="file" name="file" id="file" style="font-size:12px; display:block" disabled>
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">

                        <input type="hidden" id="checker" name="checker"value="0">
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Weight(Kg) *</label>
                        <input type="number" class="form-control" placeholder="Weight(Kg)" name="weight" id="weight" value="{{$recid->weight}}">
                    </div>

                    <div id="loader"></div>
                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="module" value="ac-status">
                    <input type="hidden" name="actual"value="{{$recid->registration_marks}}">
                    <input type="hidden" name="record_id" value="{{$recid->id}}">
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateAircraft">UPDATE</button>
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