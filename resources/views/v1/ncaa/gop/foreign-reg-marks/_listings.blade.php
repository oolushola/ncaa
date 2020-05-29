<div class="col-md-7 grid-margin stretch-card">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span id="deleteLoader" style="display:block"></span>
            {{ $foreignRegistrationMarksListings->links() }}
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="4" style="font-size:11px; font-weight:bold">General Aviation Lists</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th width="85%" style="font-size:11px; font-weight:bold">General Aviation Name</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($foreignRegistrationMarksListings))
                    <?php $count = 0; ?>
                        @foreach($foreignRegistrationMarksListings as $foreignregistrationmarks)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($foreignregistrationmarks->foreign_registration_marks)}}</td>
                                <td>
                                    <a href="{{URL('foreign-registration-marks/'.$foreignregistrationmarks->id.'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                                <td style="color:red">
                                    <form method="POST" name="frmDeleteforeignregistrationmarks" id="frmDeleteforeignregistrationmarks">
                                        {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                        <i class="mdi mdi-delete-forever deleteforeignregistrationmarks" style="font-size:25px; cursor:pointer" title="{{$foreignregistrationmarks->foreign_registration_marks}}" value="{{$foreignregistrationmarks->id}}"></i>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="6" style="font-size:11px; font-weight:bold">No general aviation has been added yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>