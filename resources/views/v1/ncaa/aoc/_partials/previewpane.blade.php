<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="display:inline-block">Preview Pane</h4>
            <div style="display:inline-block; float:right">{{$aoclistings->links()}}</div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        
                        <thead>
                            <tr class="table-warning">
                                <th width="7%" style="font-weight:bold">#</th>
                                <th width="40%" style="font-weight:bold">AOC Holder</th>

                                @if(Auth::user()->role == 1 || 2)
                                    <th style="font-weight:bold" class="center">A/C Type</th>
                                    <th style="font-weight:bold" class="center">OPS Specs</th>
                                @endif

                                @if(Auth::user()->role == 1)
                                    <th style="font-weight:bold">Created By?</th>
                                @endif

                                <th width="7%" style="font-weight:bold" class="center">Edit</th>

                                @if(Auth::user()->role == 1 || 2)
                                    <th width="7%" style="font-weight:bold" class="center">Delete</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($aoclistings))
                                <?php 
                                    $count = 0;
                                ?>
                                @foreach($aoclistings as $aoc)
                                <?php 
                                    $count++; 
                                    if ($count % 2 == 0){
                                        $css = 'table-primary';
                                    }
                                    else{
                                        $css = 'table-secondary';
                                    }
                                ?>
                                    <tr class={{$css}}>
                                        <td>{{$count}}</td>
                                        <td>{!! strtoupper($aoc->aoc_holder) !!}</td>
                                        
                                        @if(Auth::user()->role == 1 || 2)
                                            <td class="center">
                                                <a href="{{URL(str_slug($aoc->aoc_holder).'/aircrafts/'.$aoc->id)}}" target="_blank" title="Give Aircraft Type to this AOC Holder">
                                                    <i class="mdi mdi-airplane" style="font-size:25px; color:green"></i>
                                                </a>
                                            </td>
                                            <td class="center">
                                                <a href="{{URL('assign-operation-type-to-aoc')}}" style="color:#333" target="_blank" title="Give OPS Specs to this AOC Holder">
                                                    <i class="mdi mdi-airplane-takeoff" style="font-size:25px;"></i>
                                                </a>
                                            </td>
                                        @endif

                                        @if(Auth::user()->role==1)
                                            <td>{{$aoc->created_by}}</td>
                                        @endif

                                        <td class="center">
                                            <a href="{{URL('new-aoc/'.base64_encode($aoc->id).'/edit')}}">
                                                <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                            </a>
                                        </td>
                                        @if(Auth::user()->role == 1 || 2)
                                            <td class="center">
                                                <i class="mdi mdi-delete-forever" style="font-size:25px; color:red"></i>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="table-secondary">No records available for AOC yet.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>               
        </div>
    </div>
</div>