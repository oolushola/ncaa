<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane <span id="deleteLoader"></span></h4>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead class="table-info">
                        <tr class="table-warning">
                            <th colspan="8" style="font-size:11px; font-weight:bold">TAC Lists</th>
                            
                        </tr>
                        <tr>
                            <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                            <th style="font-size:11px; font-weight:bold" align="center">TC Acceptance Approval Certificate No.</th>
                            <th style="font-size:11px; font-weight:bold" align="center">Issue Date</th>
                            @if(Auth::user()->role==1)
                            <th width="5%" style="font-size:11px; font-weight:bold;">Edit</th>
                            <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($tacs))
                        <?php $count = 0; ?>
                            @foreach($tacs as $tac)
                            <?php $count++; 
                                $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';

                                $convertdate_of_first_issue = strtotime($tac->date_issued);

                                $date_of_issue = date('d/m/Y', $convertdate_of_first_issue);
                            ?>
                                <tr class="{{$css}}">
                                    <td>{{$count}}</td>
                                    <td>{{strtoupper($tac->tc_acceptance_approval)}}</td>
                                    <td>{{$date_of_issue}}</td>

                                    <td style="color:blue" class="center">
                                        <a href="{{URL('type-acceptance-certificate/'.$tac->id.'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                        </a>
                                    </td>
                                    
                                   
                                    <td style="color:red" class="center">
                                        <form method="POST" id="deleteTac">
                                            {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                            <i class="mdi mdi-delete-forever deleteTac" style="font-size:25px; cursor:pointer" value="{{$tac->id}}" title="Delete {{$tac->tc_acceptance_approval}}" name="{{$tac->tc_acceptance_approval}}"></i>
                                        </form>
                                    </td>
                                    
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