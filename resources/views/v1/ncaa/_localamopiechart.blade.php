    @if(count($localamolist))
      <?php
        $active = 0;
        $expiring_soon = 0;
        $expired = 0;
      ?>
      @foreach($localamolist as $localamo)
      <?php
        $now = time();
        $due_date = strtotime($localamo->expiry);;
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
        $total = count($localamolist);
        $active_localamo = round(($active / $total)*100);
        $expiring_soon_localamo = round(($expiring_soon / $total)*100);
        $expired_localamo = round(($expired / $total)*100);
        
      ?>
    @else
      <?php
        $active_localamo = 33.3;
        $expiring_soon_localamo = 33.3;
        $expired_localamo = 33.3;
      ?>
    @endif

<div class="card">
    <div class="card-body">
        <h4 class="card-title center">Local AMO <sub>arcs in %</sub></h4>
        <input type="hidden" id="localAMOexpiringSoon" value="{{$expiring_soon_localamo}}">
        <input type="hidden" id="localAMOexpired" value="{{$expired_localamo}}">
        <input type="hidden" id="localAMOactive" value="{{$active_localamo}}">

        <canvas id="localAmopieChart"></canvas>
    </div>
</div>