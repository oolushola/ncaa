<div class="col-md-6 grid-margin">
    <div class="card">

        <div class="card-body" style="padding-bottom:0">
            <h4 class="card-title">{!! strtoupper(Auth::user()->name) !!}</h4>
            <p class="card-description">
                @if(Auth::user()->phone != '')
                    {{Auth::user()->phone}}
                @else
                    You've not updated your phone number.
                @endif

            </p>
            
        </div>

        <div class="card-body">
            <h4 class="card-title">Account Details</h4>
            <div class="row">
                <div class="col-md-5">
                    <address>
                        <p class="font-weight-bold">Account Status</p>
                        <p style="color:green">Active</p>
                        <br>
                        <p class="font-weight-bold">User Role</p>
                        <?php
                            $userrole = Auth::user()->role;
                            if($userrole==1){
                                $role = 'Super Access';
                            }
                            elseif($userrole == 2){
                                $role = 'Top-tier Access';
                            }
                            else{
                                $role = 'Read & View Access';
                            }

                        ?>
                        <p style="color:blue">{{$role}}</p>
                    </address>
                </div>
                <div class="col-md-7">
                    <address class="text-primary">
                        <p class="font-weight-bold">E-mail</p>
                        <p class="mb-2">{{Auth::user()->email}}</p>
                        <p class="font-weight-bold">Date Registered</p>
                        <?php 
                            $converdatetotimeofissued = strtotime(Auth::user()->created_at); 
                            $date_registered = date('jS \of F, Y.', $converdatetotimeofissued);
                        ?>
                        <p>{{$date_registered}}</p>
                    </address>
                </div>
            </div>
        </div>
       
    </div>
</div>