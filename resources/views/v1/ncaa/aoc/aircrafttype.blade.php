@extends('v1.ncaa.design-layout')

@section('title') Assign Aircraft Type to an AOC @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            A/c Type Operated By : {{ ucwords(str_slug($name)) }}
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">AOC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Aircraft Type</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form name="frmaocaircraft" id="frmaocaircraft" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label style="font-size:11px; font-weight:bold; color:green">Aircraft Type*</label>
                            
                            <select class="form-control" name="aircraft_maker_id" id="aircraft_maker_id">
                                <option value=''>Choose Aircraft Make</option>
                                    @foreach($getAircraftMakerLists as $aircraftmake)
                                        <option value="{{$aircraftmake->id}}">{{$aircraftmake->aircraft_maker}}</option>
                                    @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="aoc_holder_id" id="aoc_holder_id" value="{{$id}}">       
                        
                        <button type="submit" class="btn btn-gradient-primary mr-2" id="addAocAircraft">ADD</button>
                        <button class="btn btn-light">Cancel</button>

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Preview Pane</h4>
                    <table class="table table-bordered" width="100%">
                        <thead class="table-info">
                            <tr class="table-warning">
                                <th colspan="3" style="font-size:11px; font-weight:bold">Aircraft type operated by : {{ucwords($name)}}</th>
                            </tr>
                            <tr>
                                <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                <th width="90%" style="font-size:11px; font-weight:bold" align="center">A/C Type</th>
                                <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($aircraftlistings))
                                <?php $count = 0; ?>
                                @foreach($aircraftlistings as $aircraft)
                                <?php
                                    $count++;
                                ?>
                                <tr class="table-secondary">
                                    <td style="font-size:11px;">{{$count}}</td>
                                    <td style="font-size:10px; line-height:15px; font-weight:bold">{{strtoupper($aircraft->aircraft_maker)}}</td>
                                    <td style="font-size:10px; line-height:15px; color:red" align="center"><i class="mdi mdi-delete-forever" style="font-size:25px;"></i></td>
                                </tr>
                                @endforeach
                            @else
                            <tr class="table-danger">
                                <td colspan="3" style="font-size:11px; font-weight:bold">You have not assigned any aircraft type to {{ucwords($name)}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/aoc/aoc.js')}}"></script>
@stop