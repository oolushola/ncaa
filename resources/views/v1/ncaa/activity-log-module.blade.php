@extends('v1.ncaa.design-layout')

@section('title') Activity log of {{strtoupper($module)}} @stop

@section('main')
<div class="col-md-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"></h4>
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="8" style="font-size:11px; font-weight:bold">{{strtoupper($module)}} Activity Log History</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th style="font-size:11px; font-weight:bold">Module Worked On?</th>
                        <th style="font-size:11px; font-weight:bold" class="center">Thread</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @if(count($moduleActivityLogs))
                    <?php $count = 0; ?>
                        @foreach($moduleActivityLogs as $activitylog)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($activitylog->actual)}}</td>
                                <td class="center"><a href="{{URL('activity-log/'.$activitylog->module.'/'.str_slug($activitylog->actual).'/'.$activitylog->record_id)}}">Show this thread</a></td>

                                
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="8" style="font-size:11px; font-weight:bold">There are no update record</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>
@stop