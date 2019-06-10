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
                        <select class="form-control" name="aoc_holder_id" id="aoc_holder_id">
                            <option value="0">Amo Holder</option>
                            @foreach($aoclists as $aoc)
                                @if($recid->aoc_holder_id == $aoc->id)
                                    <option value="{{$aoc->id}}" selected>{{$aoc->aoc_holder}}</option>
                                @else
                                    <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_approval_number" id="amo_approval_number" class="form-control" placeholder="AMO Approval Number" value="{{$recid->amo_approval_number}}">                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green; display:block">Upload AMO Approval</label>
                        <input type="file" disabled name="amo_approval_number_file" id="file" style="font-size:12px; display:inline-block"> 
                        <i class="mdi mdi-lock-open-outline" title="Open file upload" id="unlockAmoAprovalNumber"></i> 
                        
                        <input type="hidden" id="approvalNumberChecker" value="0">
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control textarea" placeholder="Ratings/Capabilities" name="ratings_capabilities" id="ratings_capabilities">{!! $recid->ratings_capabilities !!}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Maintenance Locations" name="maintenance_locations" id="maintenance_locations" value="{{$recid->maintenance_locations}}">
                    </div>
                    <div class="form-group">
                        <?php 
                            $converdatetotime = strtotime($recid->expiry); 
                            $choosendate = date('Y-m-d', $converdatetotime);
                        ?>
                        <label style="font-size:12px; font-weight:bold; color:green">Expiry</label>
                        <input type="date" class="form-control" id="expiry" name="expiry" value="{{$choosendate}}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_pm_aprvl_pg_lep" id="amo_pm_aprvl_pg_lep" class="form-control" placeholder="AMO PM APRVL PG & LEP" value="APRVL PG & LEP" disabled>                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green; display:block">Upload AMO PM APRVL PG & LEP </label>
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
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Extention</label>
                        <input type="date" name="extention" id="extention" class="form-control" placeholder="Extention" value="{{$choosendate_}}">
                    </div>

                    <div id="loader"></div>
                    <input type="hidden" name="name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="module" value="amo-local">
                    <input type="hidden" name="actual" value="{{$recid->amo_approval_number}}">
                    <input type="hidden" name="record_id" value="{{$recid->id}}">
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="updateLocalAmo">UPDATE</button>
                    <button class="btn btn-light">Cancel</button>
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