<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane <span id="deleteLoader"></span></h4>
            <div class="table-responsive">
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

                                $convertdate_of_first_issue = strtotime($focc->date_of_first_issue);
                                $convertdaterenewal = strtotime($focc->renewal);

                                $date_of_first_issue = date('d/m/Y', $convertdate_of_first_issue);
                                $renewal = date('d/m/Y', $convertdaterenewal);
                            ?>
                                <tr class="{{$css}}">
                                    <td>{{$count}}</td>
                                    <td>{{strtoupper($focc->focc_no)}}</td>
                                    <td>{{$date_of_first_issue}}</td>
                                    <td>{{$renewal}}</td>

                                    <td style="color:blue" class="center">
                                        <a href="{{URL('focc-and-mcc/'.$focc->id.'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                    <td>{{$focc->created_by}}</td>
                                    <td style="color:red" class="center">
                                        <form method="POST" name="deleteFocc" id="deleteFocc">
                                            {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                            <i class="mdi mdi-delete-forever deleteFocc" style="font-size:25px; cursor:pointer" value="{{$focc->id}}" title="Delete {{$focc->focc_no}}"></i>
                                        </form>
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
</div>