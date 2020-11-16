@extends('v1.ncaa.design-layout')

@section('title') View all Users @stop

@section('main')
    <div class="page-header">        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL('user-role')}}">Add New User</a></li>
                <li class="breadcrumb-item active" aria-current="page">View All Users</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding-left:3px; padding-right:3px;">
                    <h4 class="card-title" style="padding-left:10px; display:inline-block">All Users Listings</h4>
                    
                    <div class="table-responsive">
                    {{ $allusers->links() }}
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-warning">
                                    <th width="5%"><b>#</b></th>
                                    <th><b>Name</b></th>
                                    <th><b>Phone Number</b></th>
                                    <th><b>email</b></th>
                                    <th><b>Role</b></th>
                                    <th class="center"><b>Date Registered</b></th>
                                    <th><b>Last Login</b></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(count($allusers))
                                    <?php $count = 0; ?>
                                    @foreach($allusers as $user)
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
                                              else{
                                                  $role = 'View & Read Only';
                                              }
                                              $converdatetotimeofissued = strtotime($user->created_at); 
                                              $date_registered = date('jS \of F, Y.', $converdatetotimeofissued);
                                        ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{ucwords($user->name)}}</td>
                                            <td>{{ucwords($user->phone)}}</td>
                                            <td>{{ucwords($user->email)}}</td>
                                            <td>{{$role}}</td>
                                            <td class="center">{{$date_registered}}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td style="font-size:11px; font-weight:bold; color:red; text-align:center" colspan="15" class="table-danger">No records available</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

