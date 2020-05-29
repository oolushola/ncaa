@extends('v1.ncaa.design-layout')

@section('title') Local AMO - {{$recid->amo_approval_number}} @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            AMO - Local 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL('amo-local')}}">AMO Local</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Local AMO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmLocalAmo" id="frmLocalAmo" method="POST" action="{{URL('/amo-local', $recid->id)}}">
                {!! csrf_field() !!} {!! method_field('PATCH') !!}
                    <div class="form-group">
                        <input type="hidden" name="holder_criteria" id="amoHolderChecker" value="{{$recid->holder_criteria}}">
                        <span class="labelholder" style="display:inline-block; margin-right:10px;" >AMO Holder</span>
                            @if($recid->holder_criteria==1)
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input amoHolder" name="typeofopereation" value="1" checked>
                                    Existing AOC &nbsp; &nbsp; 
                                </label>
                            </div>
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input amoHolder" name="typeofopereation" value="2">
                                    Non AOC Holder
                                </label>
                            </div>
                            @elseif($recid->holder_criteria==2)
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input amoHolder" name="typeofopereation" value="1">
                                    Existing AOC &nbsp; &nbsp; 
                                </label>
                            </div>
                            <div class="form-check" style="display:inline-block;">
                                <label class="form-check-label" style="font-size:11px; font-weight:bold;">
                                    <input type="radio" class="form-check-input amoHolder" name="typeofopereation" value="2" checked>
                                    Non AOC Holder
                                </label>
                            </div>
                            @endif
                    </div>

                    @if($recid->holder_criteria==1)
                        <div class="form-group" id="existingAOCHolderBox">
                            <label class="labelholder">Amo Holder *</label>
                            <select class="form-control" name="aoc_holder_id" id="aoc_holder_id">
                                <option value="0">Choose amo holder</option>
                                @foreach($aoclists as $aoc)
                                    @if($recid->aoc_holder_id == $aoc->aoc_holder)
                                        <option value="{{$aoc->aoc_holder}}" selected>{{$aoc->aoc_holder}}</option>
                                    @else
                                        <option value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @elseif($recid->holder_criteria == 2)
                        <div class="form-group" id="newAOCHolderBox">
                            <label class="labelholder">AMO Holder *</label>
                            <input type="text" name="aoc_holder_id" id="non_aoc_holder" class="form-control" value="{{$recid->aoc_holder_id}}">
                        </div>
                    @endif

                    <div class="form-group" style="display:none" id="existingAOCHolderBox">
                        <label class="labelholder">AMO Holder *</label>
                        <select class="form-control" id="aoc_holder_id">
                            <option value="0">Choose amo holder</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->aoc_holder}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="display:none" id="newAOCHolderBox">
                        <label class="labelholder">AMO Holder *</label>
                        <input type="text" id="non_aoc_holder" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="labelholder">AMO Approval Number *</label>
                        <input type="text" name="amo_approval_number" id="amo_approval_number" class="form-control"  value="{{$recid->amo_approval_number}}">                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" class="labelholder">Upload AMO Approval</label>
                        <input type="file" disabled name="amo_approval_number_file" id="file" style="font-size:12px; display:inline-block"> 
                        <i class="mdi mdi-lock-open-outline" title="Open file upload" id="unlockAmoAprovalNumber"></i> 
                        
                        <input type="hidden" id="approvalNumberChecker" value="0">
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                    </div>

                    <!-- <div class="form-group">
                        <label for="aircraftmake" class="labelholder">Aircraft Maker *</label>
                        <select name="aircraft_maker_id" id="aircraft_maker_id" class="form-control">
                            <option value="">Select Aircraft Maker</option>
                            @foreach($aircraftmakerlist as $aircraftMaker)
                                @if($recid->aircraft_maker_id == $aircraftMaker->aircraft_maker)
                                    <option value="{!! $aircraftMaker->aircraft_maker !!}" selected>
                                        {!! $aircraftMaker->aircraft_maker !!}
                                    </option>
                                @else
                                    <option value="{!! $aircraftMaker->aircraft_maker !!}">
                                        {!! $aircraftMaker->aircraft_maker !!}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="aircraftmake" class="labelholder">Aircraft Type</label>
                        <div  id="aircraftTypeHolder">
                            <select name="aircraft_type_id" id="aircraft_type_id" class="form-control">
                                <option value="">Select Aircraft Type</option>
                                @foreach($aircrafttypelists as $aircrafttype)
                                    @if($recid->aircraft_type_id == $aircrafttype->aircraft_type)
                                        <option value="{!! $aircrafttype->aircraft_type !!}" selected>
                                            {!! $aircrafttype->aircraft_type !!}
                                        </option>
                                    @else
                                        <option value="{!! $aircrafttype->aircraft_type !!}">
                                            {!! $aircrafttype->aircraft_type !!}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="labelholder">Ratings/Capabilities *</label>
                        <input type="text" class="form-control" name="ratings_capabilities" id="ratings_capabilities" value="{!! $recid->ratings_capabilities !!}">
                    </div> -->
                    <div class="form-group">
                        <label class="labelholder">Maintenance Locations *</label>
                        <input type="text" class="form-control" name="maintenance_locations" id="maintenance_locations" value="{{$recid->maintenance_locations}}">
                    </div>
                    <div class="form-group">
                        <?php 
                            $converdatetotime = strtotime($recid->expiry); 
                            $choosendate = date('Y-m-d', $converdatetotime);
                        ?>
                        <label class="labelholder">Expiry *</label>
                        <input type="date" class="form-control" id="expiry" name="expiry" value="{{$choosendate}}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_pm_aprvl_pg_lep" id="amo_pm_aprvl_pg_lep" class="form-control" placeholder="AMO PM APRVL PG & LEP" value="APRVL PG & LEP" disabled>                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" class="labelholder">Upload AMO PM APRVL PG & LEP </label>
                        <input type="file" disabled name="amo_pm_aprvl_pg_lep_file" id="amo_pm_aprvl_pg_lep_file" style="font-size:12px; display:inline-block"> 
                            
                            <i class="mdi mdi-lock-open-outline" title="Open file upload" id="unlock_aprvl_amo"></i>
                            <input type="hidden" value="0" id="aprvlChecker">
                    </div>
                    <div class="form-group">
                        <?php 
                            if($recid->extention !=''){
                                $converdatetotime_ = strtotime($recid->extention); 
                                $choosendate_ = date('Y-m-d', $converdatetotime_);
                            }
                            else{
                                $choosendate_ = '';
                            }
                            
                        ?>
                        <label for="c-of-a status" class="labelholder">Extention</label>
                        <input type="date" name="extention" id="extention" class="form-control" placeholder="Extention" value="{{$choosendate_}}">
                    </div>

                    <div id="loader"></div>
                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="module" value="amo-local">
                    <input type="hidden" name="actual" value="{{$recid->amo_approval_number}}">
                    <input type="hidden" name="record_id" value="{{$recid->id}}">
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateLocalAmo">UPDATE</button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                </form>
            </div>
            </div>
        </div>
        <!-- partials -->
        @include('v1.ncaa.amo.local.partials._listings')
        
    </div>

@stop



@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/amo/amo-local.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/tinymce/tinymce.min.js')}}"></script>  
  <script type="text/javascript">
    tinyMCE.init({
    mode:'exact',
    force_br_newlines:true,
    force_p_newlines:true, 
    forced_root_block : '',selector :'.textarea'
    });
  </script>
@stop