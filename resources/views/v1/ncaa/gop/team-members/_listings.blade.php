<div class="col-md-7 grid-margin ">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span id="deleteLoader" style="display:block"></span>
            {{ $teamMemberListings->links() }}
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="4" style="font-size:11px; font-weight:bold">Team Member List</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th width="85%" style="font-size:11px; font-weight:bold">Team Member</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($teamMemberListings))
                    <?php $count = 0; ?>
                        @foreach($teamMemberListings as $teamMember)
                        <?php $count++; 
                            $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                        ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($teamMember->full_name)}}</td>
                                <td>
                                    <a href="{{URL('team-members/'.$teamMember->id.'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                                <td style="color:red">
                                    <form method="POST" id="frmDeleteTeamMember">
                                        {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                        <i class="mdi mdi-delete-forever deleteTeamMember" style="font-size:25px; cursor:pointer" title="{{$teamMember->full_name}}" value="{{$teamMember->id}}"></i>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="table-danger">
                        <td colspan="6" style="font-size:11px; font-weight:bold">No team member has been added yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>                
        </div>
    </div>
</div>