<div class="col-md-7 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Preview Pane</h4>
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="4" style="font-size:11px; font-weight:bold">Aircraft Makers Lists</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th width="85%" style="font-size:11px; font-weight:bold" align="center">A/C Type</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($aircraftMakerLists))
                    <?php $count = 0; ?>
                        @foreach($aircraftMakerLists as $aircraftMaker)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td style="font-size:11px;">{{$count}}</td>
                                <td style="font-size:10px; line-height:15px; font-weight:bold">{{strtoupper($aircraftMaker->aircraft_maker)}}</td>
                                <td style="font-size:10px; line-height:15px; color:blue" align="center">
                                    <a href="{{URL('aircraft-make/'.base64_encode($aircraftMaker->id).'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                                <td style="font-size:10px; line-height:15px; color:red" align="center">
                                    <i class="mdi mdi-delete-forever" style="font-size:25px;"></i>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="4" style="font-size:11px; font-weight:bold">No aircraft maker has been added yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>