@extends('v1.ncaa.design-layout')

@section('title') Local AMO @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            AMO - Local 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">A/C Status</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">ADD Local AMO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmLocalAmo" id="frmLocalAmo" method="POST" action="{{URL('/amo-local')}}">
                {!! csrf_field() !!}
                    <div class="form-group">
                        <select class="form-control" name="aoc_holder_id" id="aoc_holder_id">
                            <option value="0">Amo Holder</option>
                            @foreach($aoclists as $aoc)
                                <option value="{{$aoc->id}}">{{$aoc->aoc_holder}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_approval_number" id="amo_approval_number" class="form-control" placeholder="AMO Approval Number">                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Upload AMO Approval</label>
                        <input type="file" name="amo_approval_number_file" id="file" style="font-size:12px; display:block">
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-control textarea" placeholder="Ratings/Capabilities" name="ratings_capabilities" id="ratings_capabilities"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Maintenance Locations" name="maintenance_locations" id="maintenance_locations">
                    </div>
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Expiry</label>
                        <input type="date" class="form-control" id="expiry" name="expiry">
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_pm_aprvl_pg_lep" id="amo_pm_aprvl_pg_lep" class="form-control" placeholder="AMO PM APRVL PG & LEP" value="APRVL PG & LEP" disabled>                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Upload AMO PM APRVL PG & LEP</label>
                        <input type="file" name="amo_pm_aprvl_pg_lep_file" id="amo_pm_aprvl_pg_lep_file" style="font-size:12px; display:block">
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Extention</label>
                        <input type="date" name="extention" id="extention" class="form-control" placeholder="Extention">
                    </div>

                    <div id="loader"></div>
                    
                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addLocalAmo">ADD</button>
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