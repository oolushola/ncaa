@extends('v1.ncaa.design-layout')

@section('title')FCOP @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
             FCOP.
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('economic-licence/fcop/view')}}">View All</a></li>
            <li class="breadcrumb-item active" aria-current="page">@if(isset($recid))Update @else Add @endif FCOP</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin ">
            <div class="card">
            <div class="card-body">
                @if(isset($recid)) 
                <form name="frmFcop" id="frmFcop" method="POST" action="{{URL('economic-licence/fcop',$recid->id)}}" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @else
                <form name="frmFcop" id="frmFcop" method="POST" action="{{URL('/economic-licence/fcop')}}" enctype="multipart/form-data">
                @endif

                {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="labelholder">Foreign Airline *</label>
                        <input type="text" class="form-control" name="foreign_airline" id="foreignAirline" value="@if(isset($recid)){{$recid->foreign_airline}}@else{{old('foreign_airline')}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Licence Number *</label>
                        <input type="text" name="licence_no" id="licenceNumber" value="@if(isset($recid)){{$recid->licence_no}}@else{{old('licence_no')}}@endif" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                        <label class="labelholder">Upload Certificate *</label>
                        <input type="file" name="fcop_certificate" id="file" style="font-size:12px; display:inline-block"  @if(isset($recid))disabled  @endif > @if(isset($recid)) 
                        <span class="labelholder" id="changePhoto" style="cursor:pointer">Change</span> @endif
                        <input type="hidden" name="photoChecker" id="photoChecker" value="@if(isset($recid)){{$recid->atl_certificate}}@else{{0}}@endif">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Part 18 *</label>
                        <div class="form-group-inline">
                            <input type="radio" value="1" class="part18" name="part18" @if(isset($recid) && $recid->part_18 == "1") checked @endif /> Yes
                            <input type="radio" value="0" class="part18" name="part18" @if(isset($recid) && $recid->part_18 == "0") checked @endif  /> No
                            <input type="text" name="part_18" id="part18" value="@if(isset($recid)){{$recid->part_18}}@endif" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Part 10 *</label>
                        <div class="form-group-inline">
                            <input type="radio"  class="part10" value="1" name="part10" @if(isset($recid) && $recid->part_10 == "1") checked @endif /> Yes
                            <input type="radio"  class="part10" value="0" name="part10" @if(isset($recid) && $recid->part_10 == "0") checked @endif /> No
                            <input type="text" name="part_10" id="part10" value="@if(isset($recid)){{$recid->part_10}}@endif" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Part 17 *</label>
                        <div class="form-group-inline">
                            <input type="radio" value="1" class="part17" name="part17" @if(isset($recid) && $recid->part_17 == "1") checked @endif /> Yes
                            <input type="radio" value="0" class="part17" name="part17" @if(isset($recid) && $recid->part_17 == "0") checked @endif /> No
                            <input type="text" name="part_17" id="part17" value="@if(isset($recid)){{$recid->part_17}}@endif" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="labelholder">Status *</label>
                        <div class="form-group-inline">
                            <input type="radio" value="1" class="fcopStatus" name="fcopStatus" @if(isset($recid) && $recid->fcop_status == "1") checked @endif /> Active
                            <input type="radio" value="0" class="fcopStatus" name="fcopStatus" @if(isset($recid) && $recid->fcop_status == "0") checked @endif /> Inactive
                            <input type="text" name="fcop_status" id="fcopStatus" value="@if(isset($recid)){{$recid->fcop_status}}@endif" />
                        </div>
                    </div>
                    
                    <div class="form-group">Fcop Issue Date *</label>
                        <input type="date" name="date_fcop_issued" id="fcopIssuedDate" class="form-control" value="@if(isset($recid)){{$recid->date_fcop_issued}}@else{{old('date_fcop_issued')}}@endif">
                    </div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    <input type="hidden" name="filecheck" id="filecheck" value="0" /> 
                    <input type="hidden" name="ftype" id="ftype" value="pdf" />

                    <div class="form-group">
                        <label class="labelholder">Comments *</label>
                        <textarea name="comments" id="comments" class="form-control">@if(isset($recid)){{$recid->comments}}@else{{old('comments')}}@endif</textarea>
                    </div>

                    <div id="loader"></div>
                    
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    @if(isset($recid))        
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateFcop">UPDATE</button>
                    @else
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addFcop">SAVE</button>
                    @endif
                    <button class="btn btn-light">Cancel</button>
                    @endif
                </form>
            </div>
            </div>
        </div>
        
        <div class="col-md-6 grid-margin">
            <div class="card">
                <div class="card-body" style="padding:4px;">
                    <h4 class="card-title">Preview Pane <span id="deleteLoader"></span></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead class="table-info">
                                <tr class="table-warning">
                                    <th colspan="8" style="font-size:11px; font-weight:bold">Atl Lists</th>
                                    {!! $fcopListings->links() !!}
                                </tr>
                                <tr>
                                    <th width="5%" style="font-size:11px; font-weight:bold">#</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Foreign Airline</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Licence NO.</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Issue Date</th>
                                    <th style="font-size:11px; font-weight:bold" align="center">Status</th>

                                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                                    <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Edit</th>
                                    
                                    <th width="5%" style="font-size:11px; font-weight:bold; text-align:center">Delete</th>
                                    @endif
                                   
                                    @if(Auth::user()->role == 1)
                                    <th width="5%" style="font-size:11px; font-weight:bold;">Created By?</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($fcopListings))
                                <?php $count = 0; ?>
                                    @foreach($fcopListings as $fcop)
                                    <?php $count++; 
                                        $count % 2 == 0 ? $css = 'table-primary' : $css = 'table-secondary';

                                        $convertIssueDate = strtotime($fcop->date_fcop_issued);
                                        $date_of_first_issue = date('d/m/Y', $convertIssueDate);

                                        if($fcop->fcop_status == "0") { $status = 'Inactive';}
                                        if($fcop->fcop_status == "1") { $status = 'Active';}
                                    ?>
                                        <tr class="{{$css}}">
                                            <td>{{$count}}</td>
                                            <td>{{strtoupper($fcop->foreign_airline)}}</td>
                                            <td>{{strtoupper($fcop->licence_no)}}</td>
                                            <td>{{$date_of_first_issue}}</td>
                                            <td>{{$status}}</td>

                                            <td style="color:blue" class="center">
                                                <a href="{{URL('economic-licence/fcop/'.$fcop->id.'/edit')}}">
                                                    <i class="mdi mdi-pen" style="font-size:25px;"></i>
                                                </a>
                                            </td>                                            
                                            <td style="color:red" class="center">
                                                <form method="POST" name="frmDeleteFcop" id="frmDeleteFcop">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <i class="mdi mdi-delete-forever deleteFcop" style="font-size:25px; cursor:pointer" value="{{$fcop->id}}" title="Delete {{$fcop->licence_no}}"></i>
                                                </form>
                                            </td>
                                            <td>{{$fcop->created_by}}</td>
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

    </div>

@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/economic-licence/fcop.js?v=').time()}}"></script>
@stop