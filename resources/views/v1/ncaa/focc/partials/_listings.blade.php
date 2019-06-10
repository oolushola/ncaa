<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Preview Pane</h4>
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="8" style="font-size:11px; font-weight:bold">FOCC Lists</th>
                        {!! $focclists->links() !!}
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th style="font-size:11px; font-weight:bold" align="center">FOCC NO.</th>
                        <th style="font-size:11px; font-weight:bold" align="center">Issue Date</th>
                        <th style="font-size:11px; font-weight:bold" align="center">Renewal</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                        @if(Auth::user()->role==1)
                        <th width="5%" style="font-size:11px; font-weight:bold;">Created By?</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Delete</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(count($focclists))
                    <?php $count = 0; ?>
                        @foreach($focclists as $focc)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($focc->focc_no)}}</td>
                                <td>{{$focc->date_of_first_issue}}</td>
                                <td>{{$focc->renewal}}</td>

                                <td style="color:blue" class="center">
                                    <a href="{{URL('focc/'.base64_encode($focc->id).'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                                @if(Auth::user()->role==1)
                                <td>{{$focc->created_by}}</td>
                                <td style="color:red" class="center">
                                    <i class="mdi mdi-delete-forever" style="font-size:25px;"></i>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="8" style="font-size:11px; font-weight:bold">No record yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>