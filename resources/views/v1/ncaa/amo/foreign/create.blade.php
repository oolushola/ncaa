@extends('v1.ncaa.design-layout')

@section('title') Foreign AMO @stop

@section('main')
    <div class="page-header">
        <h3 class="page-title">
            AMO - Foreign 
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">A/C Status</a></li> -->
            <li class="breadcrumb-item active" aria-current="page">Add Foreign AMO</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5 grid-margin ">
            <div class="card">
            <div class="card-body">
                <form name="frmForeignAmo" id="frmForeignAmo" method="POST" action="{{URL('/amo-foreign')}}">
                {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="text" class="form-control" name="amo_holder" id="amo_holder" placeholder="AMO Holder">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="regional_country_id" id="regional_country_id">
                            <option value="">Choose Country</option>
                            @foreach($countrylists as $countries)
                                <option value="{{$countries->regional_country_id}}">{{$countries->country}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="moe_reference" id="moe_reference" placeholder="MOE Reference">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="approvals" id="approvals" placeholder="Approvals"></textarea>
                    </div>
                    <div class="form-group">
                    <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Ratings/Capabilities</label>
                        <textarea type="text" class="form-control textarea" placeholder="" name="ratings_capabilities" id="ratings_capabilities"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="amo_number" id="amo_number" class="form-control" placeholder="AMO Number">                    
                    </div>
                    <div class="form-group">
                        <label for="c-of-a status" style="font-size:12px; font-weight:bold; color:green">Upload AMO </label>
                        <input type="file" name="amo" id="file" style="font-size:12px; display:block">
                        <input type="hidden" name="filecheck" id="filecheck" value="0">
                        <input type="hidden" name="ftype" id="ftype" value="pdf">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-size:12px; font-weight:bold; color:green">Expiry</label>
                        <input type="date" class="form-control" id="expiry" name="expiry">
                    </div>

                    <div id="loader"></div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
                    
                            
                    <button type="submit" class="btn btn-gradient-primary mr-2" id="addForeignAmo">ADD</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
            </div>
        </div>
        <!-- partials -->
        @include('v1.ncaa.amo.foreign.partials._listings')
        
    </div>

@stop



@section('scripts')
<script type="text/javascript" src="{{URL::asset('js/jquery.form.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/mediavalidate.js')}}"></script>
  <script type="text/javascript" src="{{URL::asset('js/amo/amo-foreign.js')}}"></script>
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