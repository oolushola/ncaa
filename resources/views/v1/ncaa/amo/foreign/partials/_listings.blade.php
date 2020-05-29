<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span id="deleteLoader" style="display:block"></span>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-warning">
                            <th><b>#</b></th>
                            <th><b>AMO Holder</b></th>
                            <th><b>MOE Reference</b></th>
                            <th><b>Ratings / Capabilities</b></th>
                            <th class="center"><b>Edit</b></th>
                            @if(Auth::user()->role==1)
                                <th><b>Created By?</b></th>
                                <th class="center"><b>Delete</b></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($amoforeignlists))
                            <?php $counter = 0; ?>
                            @foreach($amoforeignlists as $amoforeign)
                                <?php $counter++; 
                                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                ?>
                                <tr class={{$css_style}}>
                                    <td>{{$counter}}</td>
                                    <td>{{strtoupper($amoforeign->foreign_amo_holder)}}</td>
                                    <td>{{strtoupper($amoforeign->moe_reference)}}</td>
                                    <td class="center">
                                        <a href="{{URL('assignaircrafttype-to-maker/'.$amoforeign->id)}}">
                                            <i class="mdi mdi-star-circle" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    <td class="center">
                                        <a href="{{URL('amo-foreign/'.base64_encode($amoforeign->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$amoforeign->created_by}}</td>
                                        <td class="center">
                                            <form method="POST" name="deleteForeignAmo" id="deleteForeignAmo">
                                                @csrf {!! method_field('DELETE') !!}
                                                <i class="mdi mdi-delete-forever deleteForeignAmo" style="font-size:25px; color:red;cursor:pointer" value="{{$amoforeign->id}}" title="Delete {{$amoforeign->amo_holder}}"></i>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @else 
                                <tr class="table-danger">
                                    <td colspan="7">You do not have any Foreign AMO added yet.</td>
                                </tr>
                        @endif
                    </tbody>
                </table> 
            </div>               
        </div>
    </div>
</div>
