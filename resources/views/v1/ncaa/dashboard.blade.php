@extends('v1.ncaa.design-layout')

@section('title') {{Auth::user()->name}} NCAA - Dashboard
@stop

@section('main')

  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white mr-2">
        <i class="mdi mdi-home"></i>                 
      </span>
      Dashboard
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Overview
          <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
        </li>
      </ul>
    </nav>
  </div>
  <div class="row">
      <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
          <div class="card-body">
            <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>
            <h4 class="font-weight-normal mb-3">AOC
                
              <i class="mdi mdi-chart-line mdi-24px float-right" style="color:#fff"></i>
            </h4>
            <h2>
              @if(count($aoclist))
                {{count($aoclist)}}
              @else
                  No Record Yet.
              @endif
            </h2>
            <h6 class="card-text mb-5">@if(count($aoclist)) Record founds @endif</h6>
            @if(Auth::user()->role==1)
              <a href="{{URL('activity-log/aoc')}}" style="text-decoration:none; color:#333; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log
              </a>
            @else
              Air Operator Certificate
            @endif
          </div>
        </div>
      </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                  
          <h4 class="font-weight-normal mb-3">A/C Status
              
            <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
          </h4>
          <h2>
            @if(count($aircraftslistings))
              {{count($aircraftslistings)}}
            @else
                No Record Yet.
            @endif
          </h2>
          <h6 class="card-text mb-5">
              @if(count($aircraftslistings)) Record founds @endif
          </h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/ac-status')}}" style="text-decoration:none; color:yellow; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log
            </a>
          @else
            Aircrafts
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                                    
          <h4 class="font-weight-normal mb-3">AMO'<sub>s</sub>
            <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
          <h2>
            <?php
                $foreignamo = count($foreignamolist);
                $localamo = count($localamolist);
                $totalamo = $foreignamo + $localamo;
                if($totalamo > 0){
                  echo $totalamo;
                }
                else{
                  echo 'No Record Yet';
                }
            ?>
          </h2>
          <h6 class="card-text mb-5">
            <?php if($totalamo > 0){ ?> Records Found for both local & foreign <?php } ?>
          </h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/amo-foreign')}}" style="text-decoration:none; color:blue; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log Foreign ::
            </a>
            <a href="{{URL('activity-log/amo-local')}}" style="text-decoration:none; color:blue; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Local
            </a>
          @else
                Approved Maintenance Organizations
          @endif
        </div>
      </div>
    </div>
    
    @if(count($aircraftslistings))
      <?php
        $active = 0;
        $expiring_soon = 0;
        $expired = 0;
      ?>
      @foreach($aircraftslistings as $aircraftstatus)
      <?php
        $now = time();
        $due_date = strtotime($aircraftstatus->c_of_a_status);;
        $datediff = $due_date - $now;
        $numberofdays = round($datediff / (60 * 60 * 24));
        
        if($numberofdays > 90 ){
          $active++;
        }
        else if(($numberofdays >= 0) && ($numberofdays <=90)){
            $expiring_soon++;
        }
        else{
            $expired++;
        }
      ?>
      @endforeach
    @endif

    <?php 
      $total = count($aircraftslistings);
      $active_aircraft = round(($active / $total)*100);
      $expiring_soon_aircraft = round(($expiring_soon / $total)*100);
      $expired_aircraft = round(($expired / $total)*100);
      
    ?>
    
    <div class="col-md-4  grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                  
          <h4 class="font-weight-normal mb-3">FOCC
            <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
          </h4>
          <h2>
            @if(count($focclist))
              {{count($focclist)}}
            @else
                No Record Yet.
            @endif
          </h2>
          <h6 class="card-text mb-5">
            @if(count($focclist)) Record founds @endif
          </h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/focc')}}" style="text-decoration:none; color:yellow; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log
            </a>
          @else
            Flight Operations Clearance Certificate
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-8 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title center">A/C Status <sub>arcs in %</sub></h4>

          <input type="hidden" id="expiringSoon" value="{{$expiring_soon_aircraft}}">
          <input type="hidden" id="expired" value="{{$expired_aircraft}}">
          <input type="hidden" id="active" value="{{$active_aircraft}}">

          <canvas id="pieChart"></canvas>
        </div>
      </div>
    </div>

    

  </div>
@stop