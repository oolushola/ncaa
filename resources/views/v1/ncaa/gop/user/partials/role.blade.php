<div class="col-md-7 grid-margin stretch-card">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <table class="table table-bordered" width="100%">
                <thead class="table-info">
                    <tr class="table-warning">
                        <th colspan="4" style="font-size:11px; font-weight:bold">Users</th>
                    </tr>
                    <tr>
                        <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                        <th style="font-size:11px; font-weight:bold" align="center">Name</th>
                        <th style="font-size:11px; font-weight:bold; text-align:center">Role</th>
                        <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Update</th>
                    </tr>
                </thead>
                <tbody>
                {{$allUsersForPreview->links()}}
                    @if(count($allUsersForPreview))
                    <?php $count=0; ?>
                        @foreach($allUsersForPreview as $user)
                            <?php $count++;
                                $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                                if($user->role == 1)
                                {
                                   $role = 'Super User';
                                }
                                elseif ($user->role == 2)
                                {
                                    $role = 'Top-tier User';
                                }
                                elseif($user->role == 3){
                                    $role = 'Read & View';
                                }
                                else{
                                    $role = 'None assigned';
                                }
                             ?>
                            <tr class="{{$css}}">
                                <td>{{$count}}</td>
                                <td>{{strtoupper($user->name)}}</td>
                                <td>{{$role}}</td>
                                <td class="center">
                                    <a href="{{URL('user-role/'.base64_encode($user->id).'/edit')}}">
                                        <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="table-danger">
                            <td colspan="4" style="font-size:11px; font-weight:bold">Sorry, you do not have any users</td>
                        </tr>
                    @endif
                    
                    
                    
                </tbody>
            </table>                
        </div>
    </div>
</div>