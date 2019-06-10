<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Preview Pane</h4>
            <div class="table-responsive">
                <table class="table table-bordered"  width="100%">
                    <thead>
                        <tr class="table-warning">
                            <th style="font-size:11px; font-weight:bold">#</th>
                            <th style="font-size:11px; font-weight:bold">Registered Operator</th>
                            <th style="font-size:11px; font-weight:bold">Registration Marks</th>
                            <th style="font-size:11px; font-weight:bold">A/C Type</th>
                            <th style="font-size:11px; font-weight:bold" class="center">Edit</th>
                            @if(Auth::user()->role==1)
                                <th style="font-size:11px; font-weight:bold">Created By?</th>
                                <th style="font-size:11px; font-weight:bold" class="center">Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($allAirCraftLists))
                            <?php $counter = 0; ?>
                            @foreach($allAirCraftLists as $aircraft)
                                <?php $counter++; 
                                    $counter % 2 == 0 ? $css_style = 'table-secondary' : $css_style = 'table-primary';
                                ?>
                                <tr class={{$css_style}}>
                                    <td style="font-size:12px;">{{$counter}}</td>
                                    <td style="font-size:12px; line-height:15px;">{{$aircraft->aoc_holder}}</td>
                                    <td style="font-size:12px; line-height:15px;">{{strtoupper($aircraft->registration_marks)}}</td>
                                    <td style="font-size:12px; line-height:15px;">{{strtoupper($aircraft->aircraft_type)}}</td>
                                    <td style="font-size:12px; line-height:15px;" align="center">
                                        <a href="{{URL('add-new-aircraft/'.base64_encode($aircraft->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$aircraft->created_by}}</td>
                                        <td style="font-size:12px; line-height:15px;" align="center">
                                            <i class="mdi mdi-delete-forever" style="font-size:25px; color:red"></i>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else 
                            <tr class="table-danger">
                                <td colspan="5" style="font-size:10px; line-height:15px;">You do not have any aircraft status added yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
</div>
