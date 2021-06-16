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
        
    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>
          <h4 class="font-weight-normal mb-3" data-toggle="modal" href=".aocModalInformation" id="forAoc" style="z-index:1000; position:relative; cursor:pointer;">AOC
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
    
    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                  
          <h4 class="font-weight-normal mb-3" data-toggle="modal" href=".aocModalInformation" id="forFoccAndMcc" style="z-index:1000; position:relative; cursor:pointer;">FOCC & MCC 
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


    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>
          <h4 class="font-weight-normal mb-3">Type Acceptance Certificate
            <i class="mdi mdi-chart-line mdi-24px float-right" style="color:#fff"></i>
          </h4>
          <h2>
            @if($tacList > 0)
              {{ $tacList }}
            @else
                No Record Yet.
            @endif
          </h2>
          <h6 class="card-text mb-5">@if($tacList) Record founds @endif</h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/tac')}}" style="text-decoration:none; color:#333; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
            Activity Log
            </a>
          @else
            Type Acceptance Certificate
          @endif
        </div>
      </div>
    </div>
    
    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>
          <h4 class="font-weight-normal mb-3" data-toggle="modal" href=".aocModalInformation" id="forAcStatus" style="z-index:1000; position:relative; cursor:pointer;">A/C Status
            <i class="mdi mdi-chart-line mdi-24px float-right" style="color:#fff"></i>
          </h4>
          <h2>
            @if(count($aircraftslistings))
              {{count($aircraftslistings)}}
            @else
                No Record Yet.
            @endif
          </h2>
          <h6 class="card-text mb-5">@if(count($aircraftslistings)) Record founds @endif</h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/ac-status')}}" style="text-decoration:none; color:#333; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
            Activity Log
            </a>
          @else
            Air Operator Certificate
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                                    
          <h4 class="font-weight-normal mb-3" data-toggle="modal" href=".aocModalInformation" id="forForeignAmo"> Foreign AMO
            <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
          <h2>
            <?php
                $foreignamo = count($foreignamolist);
                if($foreignamo > 0){
                  echo $foreignamo;
                }
                else{
                  echo 'No Record Yet';
                }
            ?>
          </h2>
          <h6 class="card-text mb-5">
            <?php if($foreignamo > 0){ ?> Records Found for foreign  <?php } ?>
          </h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/amo-foreign')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log 
            </a>
          @else
            Approved Maintenance Organizations
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-3 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                                    
          <h4 class="font-weight-normal mb-3" data-toggle="modal" href=".aocModalInformation" id="forLocalAmo">Local AMO
            <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
          <h2>
            <?php
                $localamo = count($localamolist);
                if($localamo > 0){
                  echo $localamo;
                }
                else{
                  echo 'No Record Yet';
                }
            ?>
          </h2>
          <h6 class="card-text mb-5">
            <?php if($localamo > 0){ ?> Records Found for Local AMO <?php } ?>
          </h6>
          @if(Auth::user()->role==1)
            <a href="{{URL('activity-log/amo-local')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:13px; position:relative; z-index:1"> 
              Activity Log 
            </a>
          @else
            Approved Maintenance Organizations
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-6 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{URL::asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image"/>                                    
          <h6>Economic Licences</h6>

          <div class="row">
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forAop">AOP ({{ $aopCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/aop')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:11px; position:relative; z-index:1"> 
                    Activity Log of AOP
                  </a>
                @else
                  Airline Operating Permit
                @endif
              </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forAtl">ATL ({{ $atlCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/atl')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:11px; position:relative; z-index:1"> 
                    Activity Log of AOP
                  </a>
                @else
                  Air Transport Licence
                @endif
              </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p  style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forPncf">PNCF ({{ $pncfCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/pncf')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:10px; position:relative; z-index:1"> 
                    Activity Log of PNCF
                  </a>
                @else
                  Non Commercial FLight
                @endif
              </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forAtol">ATOL ({{ $atolCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/atol')}}" style="text-decoration:none; color:black; font-weight:bold; font-size:11px; position:relative; z-index:1"> 
                    Activity Log of ATOL
                  </a>
                @else
                  Air Travel Organization Licence
                @endif
              </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forPaas">PAAS ({{ $paasCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/paas')}}"style="text-decoration:none; color:#000; font-weight:bold; font-size:11px; position:relative; z-index:1"> 
                    Activity Log of PAAS
                  </a>
                @else
                  Permit for Aerial Aviation Service
                @endif
              </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-2 mt-2">
            <div class="dashboard__economic">
                <p style="margin-bottom:10px; font-size:13px; font-weight:bold" data-toggle="modal" href=".aocModalInformation" id="forAto">ATO ({{ $atoCount }})</p>
                @if(Auth::user()->role==1)
                  <a href="{{URL('activity-log/ato')}}" style="text-decoration:none; color:#000; font-weight:bold; font-size:11px; position:relative; z-index:1"> 
                    Activity Log of ATO
                  </a>
                @else
                  Approved Training Organizations
                @endif
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>



    

  </div>


  @include('v1.ncaa._notifier')

@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
  /* Chart for Daily Gate out */
      
      var ctx = document.getElementById('notifier')
      var ctxChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ['Active', 'Expiring Soon', 'Expired'],
              datasets: [{
                  label: [''],
                  data: [0, 0, 0],
                  backgroundColor: [
                      'green',
                      '#FFBF00',
                      'red',
                      
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero: true
                      }
                  }]
              },
              "hover": {
                  "animationDuration": 0
              },
              "animation": {
                  "duration": 1,
                  "onComplete": function() {
                      var chartInstance = this.chart,
                      ctx = chartInstance.ctx

                      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                      ctx.textAlign = 'center'
                      ctx.textBaseline = 'bottom'

                      this.data.datasets.forEach(function(dataset, i) {
                          var meta = chartInstance.controller.getDatasetMeta(i)
                          meta.data.forEach(function (bar, index) {
                              var data = dataset.data[index]
                              if(data !== 0) {
                                  ctx.fillText(data, bar._model.x, bar._model.y, )
                              }
                          })
                      })
                  }
              },
              
          }
      });
    

    function modulePopupRequest(moduleId, url, namedModule, chartModuleName) {
      $(`#${moduleId}`).click(function() {
        $('#namedModule').text(namedModule)
        $('#loader').html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $('#resultPlaceholder').html('')
        $.get(url, function(data) {
          ctxChart.data.datasets[0].label = chartModuleName;
          ctxChart.data.datasets[0].data  = data.determinant;
          $('#resultPlaceholder').html(data.expiredData)
          $('#loader').html('')
          ctxChart.update()
        })
      })
    }

    modulePopupRequest('forAoc', '/aoc-chart-result', 'Aircraft Operation Certificate', 'AOC CHART')
    modulePopupRequest('forFoccAndMcc', '/focc-and-mcc-chart-result', 'FOCC & MCC', 'FOCC CHART')
    modulePopupRequest('forAcStatus', '/ac-status-chart-result', 'Aircraft Status', 'AIRCRAFT STATUS')
    modulePopupRequest('forForeignAmo', '/foreign-amo-chart-result', 'Foreign Amo', 'FOREIGN AMO')
    modulePopupRequest('forLocalAmo', '/local-amo-chart-result', 'Local Amo', 'LOCAL AMO')
    
    // Economic Licences
    modulePopupRequest('forAop', '/aop-chart-result', 'Aop', 'AOP')
    modulePopupRequest('forAtl', '/atl-chart-result', 'Atl', 'ATL')
    modulePopupRequest('forPncf', '/pncf-chart-result', 'Pncf', 'PNCF')
    modulePopupRequest('forAtol', '/atol-chart-result', 'Atol', 'ATOL')
    modulePopupRequest('forPaas', '/paas-chart-result', 'Paas', 'PAAS')
    modulePopupRequest('forAto', '/ato-chart-result', 'Ato', 'ATO')
    
</script>
@stop