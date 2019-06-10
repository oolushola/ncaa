@extends('v1.ncaa.design-layout')

@section('title')Activity Log of {{$actual}} @stop

@section('main')
<div class="row">
    <div class="col-md-5 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" style="color:#ff0000">Update history thread of, <br>{{strtoupper($module)}} : {{$actual}}</h4>
                <h6 style="color:blue">{{count($threadlists)}} Updates found for this record</h6>
                @if(count($threadlists))
                    @foreach($threadlists as $thread)
                    <ul style="margin:0; padding:0">
                        <li style="list-style-type:none">
                            <div style="width:50px; height:50px; border-radius:50%; overflow:hidden; padding:0;">
                                @if($thread->photo)
                                    <img src="{{URL::asset('/confidentials/users/'.$thread->photo)}}" alt="{{$thread->name}}" class="img img-rounded" width="50" height="50">
                                @else
                                    <img src='/images/no-photo.jpg' class="img img-rounded" width="50" height="50">
                                @endif     
                            </div>
                        </li>
                        <li style="border-left:2px dashed #ccc; padding:4px; list-style-type:none; margin-left:25px;">
                            <span style="color:#333; font-size:11px; font-weight:bold;">Updated by:</span> {{$thread->name}}
                        </li>
                        <?php 
                            $convertodate = strtotime($thread->updated_at);
                            $readable_date = date('l, jS \of F, Y.', $convertodate);

                            $timestamp = $thread->updated_at;
                            $timestamp_separation = explode(" ", $timestamp);
                            $time = $timestamp_separation[1];
                        ?>
                        <li style="border-left:2px dashed #ccc; padding:4px; list-style-type:none; margin-left:25px;">
                            <span style="color:green; font-size:11px; font-weight:bold;">On:</span> {{$readable_date}}
                        </li>
                        <li style="border-left:2px dashed #ccc; padding:4px; list-style-type:none; margin-left:25px;">
                            <span style="color:#333; font-size:11px; font-weight:bold;">at:</span> {{$time}}
                        </li>
                    </ul>
                    @endforeach
                @else
                    No thread match for this {{$module}} - {{$actual}}
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" width="100%">
                    <thead class="table-info">
                        <tr class="table-warning">
                            <th colspan="8" style="font-size:11px; font-weight:bold">Other Activity Logs of {{strtoupper($module)}}</th>
                        </tr>
                        <tr>
                            <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                            <th style="font-size:11px; font-weight:bold">Module Worked On?</th>
                            <th style="font-size:11px; font-weight:bold" class="center">Update History</th>
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
                                    <td>{{$activitylog->actual}}</td>
                                    <td class="center"><a href="{{URL('activity-log/'.$activitylog->module.'/'.str_slug($activitylog->actual).'/'.$activitylog->record_id)}}">Show this thread</a></td>

                                    
                                </tr>
                            @endforeach
                        @else
                        <tr class="table-danger">
                            <td colspan="8" style="font-size:11px; color:blue">You are viewing only the update history available for {{strtoupper($module)}} so far.</td>
                        </tr>
                        @endif
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>

@stop