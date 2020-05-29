@if(count($foreignamolist))
      <?php
        $active = 0;
        $expiring_soon = 0;
        $expired = 0;
      ?>
      @foreach($foreignamolist as $foreignamo)
      <?php
        $now = time();
        $due_date = strtotime($foreignamo->expiry);;
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
      <?php 
        $total = count($foreignamolist);
        $active_foreignamo = round(($active / $total)*100);
        $expiring_soon_foreignamo = round(($expiring_soon / $total)*100);
        $expired_foreignamo = round(($expired / $total)*100);
        
      ?>
    @else
      <?php
        $active_foreignamo = 33.3;
        $expiring_soon_foreignamo = 33.3;
        $expired_foreignamo = 33.3;
      ?>
    @endif

<div class="card">
    <div class="card-body">
        <h4 class="card-title center">Foreign AMO <sub>arcs in %</sub></h4>
        <input type="hidden" id="foreignAMOexpiringSoon" value="{{$expiring_soon_foreignamo}}">
        <input type="hidden" id="foreignAMOexpired" value="{{$expired_foreignamo}}">
        <input type="hidden" id="foreignAMOactive" value="{{$active_foreignamo}}">

        <canvas id="foreignAmopieChart"></canvas>
    </div>
</div>

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/amo/foreignamochart.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/amo/localamochart.js')}}"></script>

@stop