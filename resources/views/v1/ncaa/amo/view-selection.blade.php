@extends('v1.ncaa.design-layout')

@section('title') AMO View Choice @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">AMO</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('amo-view-selection')}}">View AMO</a></li>
                <li class="breadcrumb-item active" aria-current="page">View All AMO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="template-demo">
                        <a href="{{URL('amo-foreign/view-all.html')}}" style="text-decoration:none">
                            <button type="button" class="btn btn-gradient-info btn-lg btn-block">FOREIGN AMO
                                <i class="mdi mdi-bullseye float-right"></i>
                            </button>
                        </a>
                        <br>
                        <a href="{{URL('amo-local/view-all.html')}}" style="text-decoration:none">
                            <button type="button" class="btn btn-dark btn-lg btn-block">LOCAL AMO
                                <i class="mdi mdi-eye float-right"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Soon to Expire Local AMO's <i class="mdi mdi-alert-circle-outline" style="float:right"></i></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-warning">
                                    <th style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold">AMO Holder</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Extention</th>
                                    <th style="font-size:11px; font-weight:bold">AMO Number</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Expiry</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Indicator</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($soontoexpireLocalAmos))
                                    <?php $count = 0;  ?>
                                    @foreach($soontoexpireLocalAmos as $localAmo)
                                    <?php 
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $now = time();
                                        $due_date = strtotime($localAmo->expiry);;
                                        $datediff = $due_date - $now;
                                        $numberofdays = round($datediff / (60 * 60 * 24));

                                        if(($numberofdays >= 0) && ($numberofdays <=90)){
                                            $count++; 
                                            $bgcolor = "#ffbf00";
                                            $color = "#000";

                                    echo '<tr class='.$css_style.' "style="font-family:tahoma; font-size:11px;">
                                        <td style="font-size:11px;">'.$count.'</td>
                                        <td style="font-size:11px; line-height:15px;">'.strtoupper($localAmo->aoc_holder).'</td>
                                        <td style="font-size:11px; line-height:15px;">'.$localAmo->extention.'</td>
                                        <td style="font-size:11px; line-height:15px;">'.$localAmo->amo_approval_number.'</td>
                                        <td style="font-size:11px; line-height:18px; text-align:center">'.$localAmo->expiry.'</td>
                                        <td style="font-size:11px; line-height:15px; text-align:center; background:'.$bgcolor.'">expires in '.$numberofdays.' days</td>
                                    </tr>';
                                        }
                                    ?>
                                    
                                    @endforeach
                                @else
                                <tr class="table-danger">
                                    <td colspan="6" style="font-size:12px; line-height:15px; font-weight:bold; color:brown">You do not have any local AMO that is soon due for expiry</td>
                                </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </div>                
                </div>
            </div>
            <br><br>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Soon to Expire Foreign AMO's <i class="mdi mdi-alert-circle-outline" style="float:right"></i></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr class="table-warning">
                                    <th style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold">AMO Holder</th>
                                    <th style="font-size:11px; font-weight:bold">Country</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">AMO Number</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Expiry</th>
                                    <th style="font-size:11px; font-weight:bold; text-align:center">Indicator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($soontoexpireForeignAmos))
                                    <?php $count = 0;  ?>
                                    @foreach($soontoexpireForeignAmos as $foreignAmo)
                                        <?php 
                                        $count % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                        $now = time();
                                        $due_date = strtotime($foreignAmo->expiry);;
                                        $datediff = $due_date - $now;
                                        $numberofdays = round($datediff / (60 * 60 * 24));

                                        if(($numberofdays >= 0) && ($numberofdays <=90)){
                                            $count++;
                                            $status = "Expiring soon";
                                            $bgcolor = "#ffbf00";
                                            $color = "#000";

                                            echo '<tr class='.$css_style.' style="font-family:tahoma;">
                                            <td style="font-size:11px;">'.$count.'</td>
                                            <td style="font-size:11px; line-height:15px;">'.$foreignAmo->amo_holder.' </td>
                                            <td style="font-size:11px; line-height:15px;">'.$foreignAmo->country.' </td>
                                            </td>
                                            <td style="font-size:11px; line-height:15px; text-align:center">'.$foreignAmo->amo_number.' </td>
                                            <td style="font-size:11px; line-height:15px;">'.$foreignAmo->expiry.'</td>
                                            <td style="font-size:11px; line-height:15px; text-align:center; background:'.$bgcolor.'; color:'.$color.'">expire\'s in '.$numberofdays.' days</td>
                                        </tr>';
                                        }
                                    ?>
                                        @endforeach
                                    @else
                                        <tr class="table-danger">
                                            <td colspan="6" style="font-size:12px; line-height:15px; font-weight:bold; color:brown">You do not have any Foreign AMO added yet.</td>
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