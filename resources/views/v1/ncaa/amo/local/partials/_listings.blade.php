<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Preview Pane</h4>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-warning">
                            <th width="7%" style="font-size:11px; font-weight:bold">#</th>
                            <th width="35%" style="font-size:11px; font-weight:bold">AMO Holder</th>
                            <th width="19%" style="font-size:11px; font-weight:bold">Approval Number</th>
                            <th width="25%" style="font-size:11px; font-weight:bold; text-align:center">Expiry</th>
                            <th width="7%" style="font-size:11px; font-weight:bold" class="center">Edit</th>
                            @if(Auth::user()->role==1)
                                <th style="font-size:11px; font-weight:bold" class="center">Created By?</th>
                                <th width="7%" style="font-size:11px; font-weight:bold" class="center">Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($amolocals))
                            <?php $counter = 0; ?>
                            @foreach($amolocals as $amolocal)
                                <?php $counter++; 
                                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                    $converdatetotime = strtotime($amolocal->expiry); 
                                    $choosendate = date('Y-m-d', $converdatetotime);
                                ?>
                                <tr class={{$css_style}}>
                                    <td>{{$counter}}</td>
                                    <td>{{$amolocal->aoc_holder}}</td>
                                    <td>{{strtoupper($amolocal->amo_approval_number)}}</td>
                                    <td class="center">{{$choosendate}}</td>
                                    <td class="center">
                                        <a href="{{URL('amo-local/'.base64_encode($amolocal->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$amolocal->created_by}}</td>
                                        <td class="center">
                                            <i class="mdi mdi-delete-forever" style="font-size:25px; color:red"></i>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @else 
                                <tr class="table-danger">
                                    <td colspan="5">You do not have any aircraft status added yet.</td>
                                </tr>
                        @endif
                    </tbody>
                </table>
            </div>              
        </div>
    </div>
</div>
