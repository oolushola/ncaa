<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Preview Pane</h4>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-warning">
                            <th style="font-size:11px; font-weight:bold">#</th>
                            <th style="font-size:11px; font-weight:bold">AMO Holder</th>
                            <th style="font-size:11px; font-weight:bold">MOE Reference</th>
                            <th style="font-size:11px; font-weight:bold; text-align:center">Expiry</th>
                            <th style="font-size:11px; font-weight:bold" class="center">Edit</th>
                            @if(Auth::user()->role==1)
                                <th style="font-size:11px; font-weight:bold">Created By?</th>
                                <th style="font-size:11px; font-weight:bold" class="center">Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($amoforeignlists))
                            <?php $counter = 0; ?>
                            @foreach($amoforeignlists as $amoforeign)
                                <?php $counter++; 
                                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                    $converdatetotime = strtotime($amoforeign->expiry); 
                                    $choosendate = date('Y-m-d', $converdatetotime);
                                ?>
                                <tr class={{$css_style}}>
                                    <td>{{$counter}}</td>
                                    <td>{{strtoupper($amoforeign->amo_holder)}}</td>
                                    <td>{{strtoupper($amoforeign->moe_reference)}}</td>
                                    <td>{{$choosendate}}</td>
                                    <td class="center">
                                        <a href="{{URL('amo-foreign/'.base64_encode($amoforeign->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$amoforeign->created_by}}</td>
                                        <td class="center">
                                            <i class="mdi mdi-delete-forever" style="font-size:25px; color:red"></i>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @else 
                                <tr class="table-danger">
                                    <td colspan="6" style="font-weight:bold; color:brown">You do not have any Foreign AMO added yet.</td>
                                </tr>
                        @endif
                    </tbody>
                </table> 
            </div>               
        </div>
    </div>
</div>
