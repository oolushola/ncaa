<div class="col-md-7 grid-margin">
    <div class="card">
        <div class="card-body" style="padding:4px;">
            <h4 class="card-title">Preview Pane</h4>
            <span style="display:block" id="deleteLoader"></span>
            <div class="table-responsive" style="max-height:930px;">
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
                                    <td>{{$counter}}</td>
                                    <td>{{$aircraft->aoc_holder}}</td>
                                    <td>{{strtoupper($aircraft->registration_marks)}}</td>
                                    <td>{{strtoupper($aircraft->aircraft_type)}}</td>
                                    <td class="center">
                                        <a href="{{URL('add-new-aircraft/'.base64_encode($aircraft->id).'/edit')}}">
                                            <i class="mdi mdi-pen" style="font-size:25px; color:blue;"></i>
                                        </a>
                                    </td>
                                    @if(Auth::user()->role==1)
                                        <td>{{$aircraft->created_by}}</td>
                                        <td class="center">
                                            <form method="POST" name="deleteAircraftStatus" id="deleteAircraftStatus">
                                                @csrf {!! method_field('DELETE') !!}
                                                <i class="mdi mdi-delete-forever deleteAircraftStatus" style="font-size:25px; color:red; cursor:pointer" title="Delete {{$aircraft->aoc_holder}} - {{$aircraft->registration_marks}} :{{$aircraft->aircraft_type}}" value="{{$aircraft->id}}"></i>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else 
                            <tr class="table-danger">
                                <td colspan="10" style="font-size:10px; line-height:15px;">You do not have any aircraft status added yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
</div>
