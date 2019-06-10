@extends('v1.ncaa.design-layout')

@section('title') list of {!! ucwords($acmakerrec->aircraft_maker) !!} aircraft owned by {!! ucwords($aocrec->aoc_holder) !!} @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            {!! ucwords($aocrec->aoc_holder) !!}
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('view-all-aoc')}}">Back to view all AOC</a></li>
                <!-- <li class="breadcrumb-item active" aria-current="page">view all aoc</li> -->
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">{!! ucwords($acmakerrec->aircraft_maker) !!} Aircraft history</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th width="7%" style="font-size:11px; font-weight:bold">Registration Marks</th>
                                    <th width="8%" style="font-size:11px; font-weight:bold">Aircraft Type</th>
                                    <th width="8%" style="font-size:11px; font-weight:bold;">Aircraft Serial Number</th>
                                    <th width="5%" style="font-size:11px; font-weight:bold; ; text-align:center">Year of Manufacture</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Current Registration Date</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center"">Registered Owner</th>
                                    <th width="10%"style="font-size:11px; font-weight:bold; text-align:center">C of A Status</th>
                                    <th width="5%" style="font-size:11px; font-weight:bold">Remarks</th>
                                    <th width="5%" style="font-size:11px; font-weight:bold">Weight (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($aircrafthistory))
                                    <?php $counter = 0; ?>
                                        @foreach($aircrafthistory as $aircraft)
                                            <?php $counter++; 
                                            $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                            
                                            $now = time();
                                            $due_date = strtotime($aircraft->c_of_a_status);;
                                            $datediff = $due_date - $now;
                                            $numberofdays = round($datediff / (60 * 60 * 24));

                                            

                                            if($numberofdays > 90 ){
                                                $bgcolor = "green";
                                                $color = "#fff";
                                            }
                                            else if(($numberofdays >= 0) && ($numberofdays <=90)){
                                            $bgcolor = "#ffbf00";
                                                $color = "#000";
                                            }
                                            else{
                                                $bgcolor = "red";
                                                $color = "#000";
                                            }
                                            ?>
                                        <tr style="font-family:tahoma;" class="{{$css_style}}">
                                            <td style="font-size:11px;">{{$counter}}</td>
                                            <td style="font-size:11px; line-height:15px;">{!! $aircraft->registration_marks !!}</td>
                                            <td style="font-size:11px; line-height:15px;">{!! $aircraft->aircraft_type !!}</td>
                                            <td style="font-size:11px; line-height:15px;">{!! $aircraft->aircraft_serial_number !!}</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center">{!! $aircraft->year_of_manufacture !!}</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center">{!! $aircraft->registration_date !!}</td>
                                            <td style="font-size:11px; line-height:15px;">{!! $aircraft->registered_owner !!}</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center; background:{{$bgcolor}}; color:{{$color}};">
                                                <a href="{{URL::asset('/confidentials/c-of-a/'.$aircraft->c_of_a.'')}}" target="_blank" style="color:{{$color}}">
                                                        {!! $aircraft->c_of_a_status !!}
                                                    </a>
                                            </td>
                                            <td style="font-size:11px; line-height:15px;"></td>
                                            <td style="font-size:11px; line-height:15px;">{!! $aircraft->weight !!}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                                        </tr>
                                    @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


