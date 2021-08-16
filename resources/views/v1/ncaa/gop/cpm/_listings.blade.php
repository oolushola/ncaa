<div class="col-md-7 grid-margin ">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span id="deleteLoader" style="display:block"></span>
            {{ $cpmListings->links() }}
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="4" style="font-size:11px; font-weight:bold">CPM List</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th width="85%" style="font-size:11px; font-weight:bold">CPM</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($cpmListings))
                    <?php $count = 0; ?>
                        @foreach($cpmListings as $cpm)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($cpm->full_name)}}</td>
                                <td>
                                    <a href="{{URL('cpm/'.$cpm->id.'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                                <td style="color:red">
                                    <form method="POST" id="frmDeleteCpm">
                                        {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                        <i class="mdi mdi-delete-forever deleteCpm" style="font-size:25px; cursor:pointer" title="{{$cpm->full_name}}" value="{{$cpm->id}}"></i>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="6" style="font-size:11px; font-weight:bold">No cpm has been added yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>