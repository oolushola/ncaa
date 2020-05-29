<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span style="display:block;" id="deleteLoader"></span>
            <span style="display:block">{{$amolocals->links()}}</span>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-warning">
                            <th width="7%" style="font-size:11px; font-weight:bold">#</th>
                            <th width="20%" style="font-size:11px; font-weight:bold">AMO Holder</th>
                            <th style="font-size:11px; font-weight:bold">Approval Number</th>
                            <th width="10%" style="font-size:11px; font-weight:bold; text-align:center">Ratings/<br>Capabilities</th>
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
                                    $choosendate = date('d/m/Y', $converdatetotime);
                                ?>
                                <tr class={{$css_style}}>
                                    <td>{{$counter}}</td>
                                    <td>{{strtoupper($amolocal->aoc_holder_id)}}</td>
                                    <td>{{strtoupper($amolocal->amo_approval_number)}}</td>
                                    <td class="center">
                                        <a href="{{URL('local-ratings-capabilities/'.$amolocal->id)}}">
                                            <i class="mdi mdi-star-circle" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    <td class="center">
                                        <a href="{{URL('amo-local/'.base64_encode($amolocal->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$amolocal->created_by}}</td>
                                        <td class="center">
                                            <form method="POST" name="deleteLocalAmo" id="deleteLocalAmo">
                                                @csrf {!! method_field('DELETE') !!}
                                                <i class="mdi mdi-delete-forever deleteLocalAmo" style="font-size:25px; color:red; cursor:pointer" value="{{$amolocal->id}}" title="Delete {{$amolocal->aoc_holder_id}}"></i>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @else 
                                <tr class="table-danger">
                                    <td colspan="8">You do not have any aircraft status added yet.</td>
                                </tr>
                        @endif
                    </tbody>
                </table>
            </div>              
        </div>
    </div>
</div>
