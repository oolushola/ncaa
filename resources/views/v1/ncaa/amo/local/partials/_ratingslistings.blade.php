 
    <div class="col-md-5 grid-margin" >
        <form name="frmDeleteLocalRatings" id="frmDeleteLocalRatings">
        @csrf {{method_field('DELETE')}}
            <button class="btn btn-danger btn-gradient mb-1" id="removeRatings">Remove</button>
            <span id="errorLoader" style="float:right; font-size:11px; margin-top:15px;"></span>
            <div class="card"  style="max-height:5  50px; overflow:auto">
            <div class="card-body" style="padding:0;">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr class="table-info">
                            <td colspan="4" style="font-weight:bold">Ratings for {{$recid->aoc_holder_id}}</td>
                        </tr>
                        <tr class="table-warning">
                            <td width="3%" style="font-weight:bold">#</td>
                            <!-- <td width="5%"><input type="checkbox" id="selectAllLeftchkbx"></td> -->
                            <td  colspan="4" id="selectAllLeftTxt">Remove all added ratings & capabilities</td>
                        </tr>
                        
                    </thead>
                    <tbody>
                        @if(count($panelLists))
                            <?php $counter = 0; ?>
                            @foreach($panelLists as $aircraftType)
                            <?php
                                $counter+=1;
                                $counter % 2 == 0 ? $css_style = 'table-primary' : $css_style = 'table-secondary';
                            ?>
                                <tr class="{{$css_style}}">
                                    <td>{{$counter}}</td>
                                    <td width="5%">
                                        <input type="checkbox" class="aircraftType" name="aircraft_type[]"value="{{$aircraftType->id}}">
                                    </td>
                                    <td>{{$aircraftType->aircraft_type}}</td>
                                </tr>
                            @endforeach
                        @else

                        @endif
                    </tbody>
                </table> 
            </div>
            </div>
        </form>
    </div>
