@extends('v1.ncaa.design-layout')

@section('title') Travel Agency @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            Travel Agency  <span id="errContainer"></span>
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="{{URL('travel-agency')}}">Travel Agency</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add Travel Agency</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form method="POST" name="frmTravelAgency" id="frmTravelAgency">
                    {!! csrf_field() !!}
                    @if(isset($recid))
                        {!! method_field('PATCH') !!}
                    @endif
                        
                        <div class="form-group">
                            <label class="labelholder">Name of Travel Agency *</label>
                            <input type="text" name="travel_agency_name" id="travelAgencyName" class="form-control" value="<?php if(isset($recid)){echo $recid->travel_agency_name;}else{echo ""; } ?>">
                        </div>
                        <div class="form-group">
                            <label class="labelholder">Description <sub>(optional)</sub></label>
                            <textarea name="description" id="description" class="form-control"><?php if(isset($recid)){echo $recid->description;}else{echo ""; } ?></textarea>
                        </div>

                        @if(isset($recid))
                            <input type="hidden" name="id" id="id" value="{{$recid->id}}">
                        @endif
                        
                        @if(isset($recid))
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="updateTravelAgency">UPDATE</button>
                        @else      
                            <button type="submit" class="btn btn-gradient-primary mr-2" id="addTravelAgency">SAVE</button>
                        @endif
                        <input type="reset" class="btn btn-light" value="Cancel">

                        <div id="loader"></div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="padding:4px;">
                    <h4 class="card-title">Preview Pane</h4>
                    <span id="deleteLoader" style="display:block"></span>
                    {{ $travelAgencyListings->links() }}
                    <table class="table table-bordered" width="100%">
                        <thead class="table-info">
                            <tr class="table-warning">
                                <th colspan="4" style="font-size:11px; font-weight:bold">Travel Agency Lists</th>
                            </tr>
                            <tr>
                                <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                <th width="85%" style="font-size:11px; font-weight:bold">Travel Agency</th>
                                <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                                <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($travelAgencyListings))
                            <?php $count = 0; ?>
                                @foreach($travelAgencyListings as $travelAgency)
                                <?php $count++; 
                                    $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';
                                ?>
                                    <tr class="{{$css}}">
                                        <td>{{$count}}</td>
                                        <td>{{strtoupper($travelAgency->travel_agency_name)}}</td>
                                        <td>
                                            <a href="{{URL('travel-agency/'.$travelAgency->id.'/edit')}}">
                                                <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                            </a>
                                        </td>
                                        <td style="color:red">
                                            <form method="POST" name="frmDeleteTravelAgency" id="frmDeleteTravelAgency">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <i class="mdi mdi-delete-forever deleteTravelAgency" style="font-size:25px; cursor:pointer" title="{{$travelAgency->travel_agency_name}}" value="{{$travelAgency->id}}"></i>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr class="table-danger">
                                <td colspan="6" style="font-size:11px; font-weight:bold">No travel agency has been added yet.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
        
    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/gop/travel-agency.js')}}"></Script>
@stop